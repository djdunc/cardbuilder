<?php
require_once ('../config.php');
require_once ('functions.php');
date_default_timezone_set('GMT');
$date = date( 'U' );  
$card_id =  $_GET['card_id'];
$bkg_w = 720;
$bkg_h= 485;
//check if there are files uploaded
if ((($_FILES['value']["type"] == "image/gif")
|| ($_FILES['value']["type"] == "image/jpeg")
|| ($_FILES['value']["type"] == "image/png")
|| ($_FILES['value']["type"] == "image/pjpeg"))
&& ($_FILES['value']["size"] < 200000)
&& (!empty($_FILES['value']['tmp_name']))
&& ($_FILES['value']['tmp_name'] != 'none'))
  {       
       list($width, $height) = getimagesize($_FILES['value']['tmp_name']);
       if (($width>=720)&&($height>=486)){
           //$new_filename = md5($date.$_FILES['value']['name']).'.'.pathinfo($_FILES['value']['name'], PATHINFO_EXTENSION);
           $new_filename = md5($date.$_FILES['value']['name']);
           $im = imagecreatefromstring(file_get_contents($_FILES['value']['tmp_name']));
           if (imagejpeg($im, UPLOADS_DIR.$new_filename.'.jpg')){
               $b_im = CroppedThumbnail(UPLOADS_URL.$new_filename.'.jpg',$bkg_w,$bkg_h);
               imageinterlace($b_im, true);
               imagejpeg($b_im, UPLOADS_DIR.$new_filename.'_b.jpg');
               imagedestroy($im);
               imagedestroy($b_im);
              //print "<pre>File Name: " .$new_filename;
              //delete old image from dir
              //if(file_exists(UPLOADS_DIR.'fronts/'.$card->card_front.'.jpg')) unlink(UPLOADS_DIR.'fronts/'.$card->card_front.'.jpg');
              //save card image location on database:
              $saved_card_json = callAPI("card/put?id=".$card_id.'&image='.$new_filename);
              $saved_card = json_decode($saved_card_json);
              if (isset($saved_card)){
                  echo(UPLOADS_URL.$new_filename.'_b.jpg');
                }else{
                 return die ("There was a problem saving the file.");
              }
              //print " File Size: " . @filesize($_FILES['value']['tmp_name']).'</pre>';
              //return 'uploads/'.$new_filename;
          }else{
              return die ("There was a problem saving the file.");
          }
      }else{
           return die ("File too small, minimum size 720x486px.");
      }
          
          
} else {			
//print "No file has been uploaded.";
	return die ("Please upload a valid gif, jpg or png under 200Kb");
	//die();
} 