<?php
function callAPI($query_params) {
	$json_string = "";
    try {
    	$request = BASE_API.$query_params;
        //create OAuth object using private key and secret
        $oauth = new OAuth(PRIVATE_KEY, SECRET, OAUTH_SIG_METHOD_HMACSHA1);
        //parameters passed as data using array 
        $oauth->fetch($request ,array(), OAUTH_HTTP_METHOD_GET);

        //get JSON response string
        $json_string = $oauth->getLastResponse();   
//        OR go straight to PHP object (if JSON string isn't required for JS).
//        $php_object = json_decode($oauth->getLastResponse());
//        OR get an associative array passing true as the second parameter
//        $php_assoc_array = json_decode($oauth->getLastResponse(), true);

    } catch(OAuthException $E) {
          $error = json_decode($oauth->getLastResponse());
          echo $error->error->message;
    }
    return 	$json_string;
}
function showThumbnail($imgSrc,$thumbnail_width,$thumbnail_height){
   $tmp_img = CroppedThumbnail($imgSrc,$thumbnail_width,$thumbnail_height);
   # Display the image
   echo header("Content-type: image/jpeg");
   echo imagejpeg($tmp_img);
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