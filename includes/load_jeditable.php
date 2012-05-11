<?php
require_once ('../config.php');
require_once ('functions.php');
$card_id =  $_POST['card_id'];
$id    = $_POST['id'];
$value = urlencode ( $_POST['value']);
if (isset($_POST['orig'])){$orig = $_POST['orig'];}else{$orig='';};
if(!is('user')) {
  //must be logged in for POST|PUT|DELETE
  echo "Not logged in; can't POST|PUT|DELETE";
  die;
}

if ($_POST['controller']=='card'){
    if ($id == 'name' && $value==''){
        echo($orig);
        die;
    }
    $saved_card_json = callAPI("card/put?id=".$card_id.'&'.$id.'='.$value);
    $saved_card = json_decode($saved_card_json);
    if (isset($saved_card)){
        //sucess!
        //create front:
        print $saved_card->$id;
    } else{
        //error
        $saved_card->id." ".$card_id.$_POST; 
    }
} elseif($_POST['controller']=='comment'){
       $post_comment_json = callAPI("comment/post?card_id=".$card_id.'&owner='.$_SESSION['user']->id.'&'.$id.'='.$value);
       $comment = json_decode($post_comment_json);
       if (isset($comment)){
           //if $id="category_id"{
               //return $value;
           //} else{
           echo $post_comment_json;
           //}
       } else{
           $comment->$id." ".$comment.$_POST; 
           //print $card_id.'error, please try again';
       }
} else{
    echo('no controller');
}
?>