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
?>