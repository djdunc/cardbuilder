<?php
require_once ('../config.php');
require_once ('functions.php');
if (isset($_POST['card_id'])){
    $card_id = $_POST['card_id'];
    $card_json = callAPI("card/get?id=".$card_id);
    if (isset($card_json)) { $card = json_decode($card_json); }else{ echo("false"); die; }
} else{
    echo("false");
    die;
}
//
/* vars */
/* dimensions */
$im_w = 800;
$im_h = 568;
$bkg_w = 720;
$bkg_h= 485;
$bkg_xy = array('x'=>59, 'y'=>21);

/*get background and resize to frame size*/
if (isset($card->image) && file_exists(UPLOADS_DIR.$card->image."_b.jpg")){
        $bkg_im = CroppedThumbnail(UPLOADS_URL.$card->image."_b.jpg",$bkg_w,$bkg_h);
} else{
    //get random background
    $bkg_im = CroppedThumbnail(BASE_URL.'assets/images/card_texture_1.jpg',$bkg_w,$bkg_h);
    //$bkg_im = imagecreatefrompng(BASE_URL."/includes/rounded.php?shape=r&r=24&fgc=cccccc&f=png&bgo=0&fgo=100&w=$bkg_w&h=$bkg_h");
}
//Create empty image
$im = imagecreatetruecolor($im_w, $im_h);
/* colours */
$white = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);
/* fonts */
$font =ABSPATH.'assets/fonts/Helvetica.ttf';
$font_b =ABSPATH.'assets/fonts/HelveticaNeueBold.ttf';
/*place background over white area*/
// Draw a white rectangle
imagefilledrectangle($im, 0, 0, $im_w, $im_h, $white);
//place im on top
imagecopymerge($im, $bkg_im, $bkg_xy['x'], $bkg_xy['y'], 0, 0, $bkg_w, $bkg_h, 100);

/*place logo and frame on top to add rounded corners*/
$frame = imagecreatefrompng(BASE_URL.'assets/images/card_frame.png');
imagealphablending($im, true);
imagealphablending($frame,true);
imagecopyresampled($im, $frame, 0, 0, 0, 0, $im_w, $im_h, $im_w, $im_h);

/*add issue*/
$issue_txt=$card->name;
//$issue_txt='my issue here';
$i_fsize = 20;
//$issue_dims=imagettfbbox($issue_fsize, 0, $font_b, $issue_txt);
ImageTTFText($im, $i_fsize, 0, 78, 542, $black, $font_b, $issue_txt);

/*add cat*/
$category = $steep[$card->category_id];
if($steep_cols[$card->category_id]){$category_col = $steep_cols[$card->category_id];}else{$category_col="FF0000";}
$card_color = imagecolorallocate($im, hexdec('0x'.substr($category_col, 0, 2)), hexdec('0x'.substr($category_col, 2, 2)), hexdec('0x'.substr($category_col, 4, 2)));
//$category = 'social';
$c_fsize = 16;
//$card_color = $black;
$category_dims=imagettfbbox(15, 90, $font_b, $category);
$category_h = abs($category_dims[3]);
ImageFilledRectangle($im, 0, $bkg_xy['y'], 38, $category_h+42, $card_color);
ImageTTFText($im, $c_fsize, 90, 28, $category_h+30, $white, $font_b, $category);

$margin= 20;
/*add question*/
if (isset($card->question)&&($card->question!="")){
    $question=$card->question;
    //$question="how many roads must a man walk down, before he can see the sky?";
    $q_fsize = 22;
    $wrap_question=wordwrap($question, 40, "\n");
    $question_dims=imagettfbbox($q_fsize, 0, $font_b, $wrap_question);

    //question block dimensions
    $r_width = 2*$margin+$question_dims[2];
    $r_height = 1.5*$margin-$question_dims[5];
    $r_x = 80;
    $r_y =$margin;
    $x1=$r_x+$margin;
    $y1=$r_y+$r_height-$margin;

    //create question block
    $q_linewidth=abs($question_dims[4]);
    $q_lineheight=abs($question_dims[5]);
    $q_width=$q_linewidth+2*$margin;
    $q_height=((1+substr_count($wrap_question,"\n"))*1.25*$q_lineheight)+(2*$margin);
    //get rounded corner rect from library
    $qimg = imagecreatefrompng(BASE_URL."/includes/rounded.php?shape=r&r=24&bc=f00&bg=FFF&fgc=$category_col&f=png&bgo=0&fgo=70&w=$q_width&h=$q_height");
    // imagealphablending($qimg, false);
    // imagealphablending($im, true);
    imagecopyresampled($im,$qimg,$bkg_xy['x'],$bkg_xy['y'],0,0,$q_width,$q_height,$q_width,$q_height);
    ImageTTFText($im, $q_fsize, 0, $bkg_xy['x']+$margin, $bkg_xy['y']+$margin+$q_lineheight, $white, $font_b, $wrap_question);
}

/*add factoid*/
if (isset($card->factoid)&&($card->factoid!="")){
    $factoid = $card->factoid;
    //$factoid = "jkljkljkl";
    $f_fsize = 11;
    $wrap_factoid = wordwrap($factoid, 50, "\n");
    $factoid_dims= imagettfbbox($f_fsize, 0, $font, $wrap_factoid);
    //factoid block dimensions
    $f_linewidth=abs($factoid_dims[4]);
    $f_lineheight=abs($factoid_dims[5]);
    $f_width=$f_linewidth+(2*$margin);
    $f_height=((1+substr_count($wrap_factoid,"\n"))*1.25*$f_lineheight)+(2*$margin);
    $f_x = $bkg_xy['x']+$bkg_w-$f_width;
    $f_y = $bkg_xy['y']+$bkg_h-$f_height;
    //get rounded corner rect from library
    $fimg = imagecreatefrompng(BASE_URL."/includes/rounded.php?shape=r&r=24&fgc=525254&f=png&bgo=0&fgo=98&w=$f_width&h=$f_height");
    imagecopyresampled($im,$fimg,$f_x,$f_y,0,0,$f_width,$f_height,$f_width,$f_height);
    ImageTTFText($im, $f_fsize, 0, $f_x+$margin, $f_y+(1.5*$margin), $white, $font, $wrap_factoid);
}


//header('Content-type: image/jpeg');
//imagejpeg($im);
//die;
date_default_timezone_set('GMT');
$date = date( 'U' );
if (isset($card->image) && $size){
    $filename = $card->image;
}else{
    $filename = md5($date.$card->name);
}
// save image to file
imageinterlace($im, true);
imagejpeg($im,UPLOADS_DIR.'fronts/'.$filename.'.jpg',90);
$t_im = CroppedThumbnail(UPLOADS_URL.'fronts/'.$filename.'.jpg',200,142);
imagejpeg($t_im, UPLOADS_DIR.'fronts/'.$filename.'_t.jpg');
//delete old image
if(file_exists(UPLOADS_DIR.'fronts/'.$card->card_front.'.jpg')) unlink(UPLOADS_DIR.'fronts/'.$card->card_front.'.jpg');
if(file_exists(UPLOADS_DIR.'fronts/'.$card->card_front.'_t.jpg')) unlink(UPLOADS_DIR.'fronts/'.$card->card_front.'_t.jpg');

$saved_card_json = callAPI("card/put?id=".$card->id.'&card_front='.$filename);
$saved_card = json_decode($saved_card_json);
echo($saved_card->card_front);

function ImageRectangleWithRoundedCorners(&$im, $x1, $y1, $x2, $y2, $radius, $color) {
    // draw rectangle without corners
    imagefilledrectangle($im, $x1+$radius, $y1, $x2-$radius, $y2, $color);
    imagefilledrectangle($im, $x1, $y1+$radius, $x2, $y2-$radius, $color);
    // draw circled corners
    imagefilledellipse($im, $x1+$radius, $y1+$radius, $radius*2, $radius*2, $color);
    imagefilledellipse($im, $x2-$radius, $y1+$radius, $radius*2, $radius*2, $color);
    imagefilledellipse($im, $x1+$radius, $y2-$radius, $radius*2, $radius*2, $color);
    imagefilledellipse($im, $x2-$radius, $y2-$radius, $radius*2, $radius*2, $color);
}
//  # Save the image to a file
//  imagepng($image, '/path/to/save/image.png');
// 
//  # Output straight to the browser.
//  imagepng($image);
 ?>