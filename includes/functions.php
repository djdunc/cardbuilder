<?php
/**
 * Used to make secure OAuth calls to API
 * @param String $query_url     Query as URL to make to API. e.g. "card/get"
 *                              Could contain parameters e.g. ?id=1
 * @param Array $query_params   Query parameters as assoc_array
 *                              Overrides parameters passed in $query_url
 * @param String $as            Desired format of return [json|obj|assoc]
 */
function callAPI($query_url, $query_params=array(), $as='json') {
	$ret = "";
    try {
    	$request = BASE_API.$query_url;
    	//create OAuth object using private key and secret
        $oauth = new OAuth(PRIVATE_KEY, SECRET, OAUTH_SIG_METHOD_HMACSHA1);
        //parameters passed as data using array 
        $oauth->fetch($request, $query_params, OAUTH_HTTP_METHOD_GET);
        //get JSON response string
        $json_string = $oauth->getLastResponse();   
//var_dump($request);
//var_dump($json_string);
    } catch(OAuthException $E) {
//var_dump($oauth->getLastResponse());
        $json_string = $oauth->getLastResponse();
        $ret = $json_string;
//var_dump($json_string); exit;
//    	$error = json_decode($oauth->getLastResponse());
//        if($error && is_object($error)) {
//            echo $error->error->message;
//        } else {
//            echo $error; 
//        }
//        return;
    }
    
    if($as == "json")
        $ret = $json_string;
    if($as == "obj")
        $ret = json_decode($json_string);
    if($as == "assoc")
        $ret = json_decode($json_string, true);
        
    return $ret;
}
/**
 * Checks $_SESSION to see if user is logged in. Forces login if not
 */
function login($data=null) {
    if(empty($_SESSION['user']) || !$_SESSION['user']->id) {
        view('login', $data);
        die;
    }
}
/**
 * Intended for use with is() function. Takes boolean expression, and parameters
 * for show_error() function to be displayed if boolean expression is false.
 * @param bool $allowed     Whether to allow flow to continue, or show error
 * @param array $error_params   Array containing params for show_error()
 */
function allow($allowed,$error_params=array('Sorry,','You must be an admin to do that..','403')) {
	if(!$allowed) {
		show_error($error_params[0],$error_params[1],$error_params[2]);
		die;
	}
	//if it gets here, action is allowed and program flow is continued
}
/**
 * Used to check on user role or ownership of objects. Uses $_SESSION['user']->id
 * and $_SESSION['user_role'] variables. Handy for Authorisation checks. 
 * @param string $role      [superadmin|admin|user|owner] owner requires $object
 * @param PHPFrame_Object $object   Or any object with ->owner property. Used in
 *                                  conjunction with 'owner' role to check
 *                                  ownership of an object
 */
function is($role='admin', $object=null) {
	$auth = false;
	switch($role) {
		case 'user':
		        if(isset($_SESSION['user'])
		            && is_object($_SESSION['user'])
                    && $_SESSION['user']->group_id == USER_GROUP_ID
                ) {
                    $auth = true;
                }
                //NOTE: no break, falls through to admin
        case 'admin':
                if(isset($_SESSION['user'])
                    && $_SESSION['user']->group_id == ADMIN_GROUP_ID
                ) {
                    $auth = true;
                }
                //NOTE: no break, falls through to superadmin
        case 'super':
		case 'superadmin':
                if(isset($_SESSION['user'])
                    && $_SESSION['user']->group_id == SUPERADMIN_GROUP_ID
                ) {
                    $auth = true;
                }
            break;
		case 'owner':
			    if(isset($object)
			        && is_object($object)    //object passed in
			        && $object->owner        //has owner field
			        && isset($_SESSION['user'])   //session user_id is set
			        && $_SESSION['user']->id      //session user_id not false
			        && $object->owner == $_SESSION['user']->id    //matches
			    ) {
			    	$auth = true;
			    }
		    break;
	}
	return $auth;
}
/**
 * Used to get value from key in $_GET array
 * @param unknown_type $key
 */
function get($key) {
	if(isset($_REQUEST[$key])) {
		return $_REQUEST[$key];
	} else {
		return NULL;
	}
}
/**
 * Used to retrive nice name from user object
 * @param PHPFrame_User $owner
 */
function get_name($owner) {
	$name = "Anon";
	
	if($owner && is_object($owner) && $owner->username) {
		$name = $owner->username; 
	
	    if($owner->first_name && !$owner->last_name) {
	    	$name = $owner->first_name;
	    } elseif($owner->first_name && $owner->last_name) {
	    	$name = $owner->first_name." ".$owner->last_name;
	    } elseif($owner->last_name) {
	    	$name = $owner->last_name;
	    }
	}
	
    return $name;
}

/////////////////resize & crop function
function CroppedThumbnail($imgSrc,$thumbnail_width,$thumbnail_height) { //$imgSrc is a FILE - Returns an image resource.
    //getting the image dimensions  
    list($width_orig, $height_orig) = getimagesize($imgSrc);   
    $myImage = imagecreatefromjpeg($imgSrc);
    $ratio_orig = $width_orig/$height_orig;
    
    if ($thumbnail_width/$thumbnail_height > $ratio_orig) {
       $new_height = $thumbnail_width/$ratio_orig;
       $new_width = $thumbnail_width;
    } else {
       $new_width = $thumbnail_height*$ratio_orig;
       $new_height = $thumbnail_height;
    }
    
    $x_mid = $new_width/2;  //horizontal middle
    $y_mid = $new_height/2; //vertical middle
    
    $process = imagecreatetruecolor(round($new_width), round($new_height)); 
    
    imagecopyresampled($process, $myImage, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);
    $thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height); 
    imagecopyresampled($thumb, $process, 0, 0, ($x_mid-($thumbnail_width/2)), ($y_mid-($thumbnail_height/2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);

    imagedestroy($process);
    imagedestroy($myImage);
    return $thumb;
}
/////////////////resize to max width and height
function resize_image_max($imgSrc,$max_width,$max_height) {
    $image = imagecreatefromjpeg($imgSrc);
    $w = imagesx($image); //current width
    $h = imagesy($image); //current height
    if ((!$w) || (!$h)) { $GLOBALS['errors'][] = 'Image couldn\'t be resized because it wasn\'t a valid image.'; return false; }

    if (($w <= $max_width) && ($h <= $max_height)) { return $image; } //no resizing needed
    
    //try max width first...
    $ratio = $max_width / $w;
    $new_w = $max_width;
    $new_h = $h * $ratio;
    
    //if that didn't work
    if ($new_h > $max_height) {
        $ratio = $max_height / $h;
        $new_h = $max_height;
        $new_w = $w * $ratio;
    }
    
    $new_image = imagecreatetruecolor ($new_w, $new_h);
    imagecopyresampled($new_image,$image, 0, 0, 0, 0, $new_w, $new_h, $w, $h);
    return $new_image;
}
////////////////resize to max width only
function width_tumb($imgSrc,$max_width) {
    $image = imagecreatefromjpeg($imgSrc);
    $w = imagesx($image); //current width
    $h = imagesy($image); //current height
    //calculate the image ratio
    //$imgratio=$w/$h;
    $newwidth = $max_width;
    if ($w>=$max_width){
        $ratio = $w/$max_width;
        $newheight = $h/$ratio;
    } else{
        $ratio = $max_width/$w;
        $newheight = $h*$ratio;
    }
     $new_image = imagecreatetruecolor($newwidth,$newheight);
     imagecopyresampled($new_image, $image, 0, 0, 0, 0, $newwidth, $newheight, $w, $h);
     return $new_image;
}

//from http://snipplr.com/view/14278/dirify/
//This is an improvement of Adam Kalsey's port to PHP of Movable Type's dirification function written in Perl. http://kalsey.com/2004/07/dirifyinphp/
//added to cleanup category names and make them usable as links might use to do clean urls in future
//or perhaps we can do with just spaces??
function dirify($s)
	{
	$s = str_replace('&amp;',' ',$s);       ## convert ampersant to space
	$s = convert_high_ascii($s);            ## convert high-ASCII chars to 7bit.
	$s = strtolower($s);                    ## lower-case.
	$s = strip_tags($s);                    ## remove HTML tags.
	$s = preg_replace('!&[^;\s]+;!','',$s); ## remove HTML entities.
	$s = preg_replace('![^\w\s-]!','',$s);  ## remove non-word/space/hyphen chars
	$s = preg_replace('!\s+!','-',$s);      ## change space chars to dash
	$s = preg_replace('/-+/','-',$s);       ## reduce multiple dashes to one
	$s = preg_replace('/_+/','_',$s);       ## reduce multiple underscores to one
	$s = trim($s,'-_');                     ## trim spaces, tabs, underscores, dashes from beginning & end
	
    return $s;
	}

function convert_high_ascii($s)
	{
 	$HighASCII = array(
 		"!\xc0!" => 'A',    # A`
 		"!\xe0!" => 'a',    # a`
 		"!\xc1!" => 'A',    # A'
 		"!\xe1!" => 'a',    # a'
 		"!\xc2!" => 'A',    # A^
 		"!\xe2!" => 'a',    # a^
 		"!\xc4!" => 'Ae',   # A:
 		"!\xe4!" => 'ae',   # a:
 		"!\xc3!" => 'A',    # A~
 		"!\xe3!" => 'a',    # a~
 		"!\xc8!" => 'E',    # E`
 		"!\xe8!" => 'e',    # e`
 		"!\xc9!" => 'E',    # E'
 		"!\xe9!" => 'e',    # e'
 		"!\xca!" => 'E',    # E^
 		"!\xea!" => 'e',    # e^
 		"!\xcb!" => 'Ee',   # E:
 		"!\xeb!" => 'ee',   # e:
 		"!\xcc!" => 'I',    # I`
 		"!\xec!" => 'i',    # i`
 		"!\xcd!" => 'I',    # I'
 		"!\xed!" => 'i',    # i'
 		"!\xce!" => 'I',    # I^
 		"!\xee!" => 'i',    # i^
 		"!\xcf!" => 'Ie',   # I:
 		"!\xef!" => 'ie',   # i:
 		"!\xd2!" => 'O',    # O`
 		"!\xf2!" => 'o',    # o`
 		"!\xd3!" => 'O',    # O'
 		"!\xf3!" => 'o',    # o'
 		"!\xd4!" => 'O',    # O^
 		"!\xf4!" => 'o',    # o^
 		"!\xd6!" => 'Oe',   # O:
 		"!\xf6!" => 'oe',   # o:
 		"!\xd5!" => 'O',    # O~
 		"!\xf5!" => 'o',    # o~
 		"!\xd8!" => 'Oe',   # O/
 		"!\xf8!" => 'oe',   # o/
 		"!\xd9!" => 'U',    # U`
 		"!\xf9!" => 'u',    # u`
 		"!\xda!" => 'U',    # U'
 		"!\xfa!" => 'u',    # u'
 		"!\xdb!" => 'U',    # U^
 		"!\xfb!" => 'u',    # u^
 		"!\xdc!" => 'Ue',   # U:
 		"!\xfc!" => 'ue',   # u:
 		"!\xc7!" => 'C',    # ,C
 		"!\xe7!" => 'c',    # ,c
 		"!\xd1!" => 'N',    # N~
 		"!\xf1!" => 'n',    # n~
 		"!\xdf!" => 'ss');
		
	$find = array_keys($HighASCII);
	$replace = array_values($HighASCII);
	$s = preg_replace($find,$replace,$s);
	return $s;
	}
?>