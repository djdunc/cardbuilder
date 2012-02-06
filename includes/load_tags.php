<?php
require_once '../config.php';
require_once ('functions.php');
   $new_tags = array();
   $old_tags = $_SESSION['c_tags'];
   if(isset($_POST['tags'])){
      $new_tags = $_POST['tags'];
   }
    //if (count($new_tags)>0){
        $to_add = array_diff($new_tags, $old_tags);
        $to_delete = array_diff($old_tags, $new_tags);
   // } else{
       // $to_delete = $old_tags;
    //}
    
    //var_dump($_SESSION['c_tags']);
    if (count($to_delete)>0){
        foreach ($to_delete as $tag){
            //delete cardtag
            $tag_id = array_search($tag, $_SESSION['c_tags']);
            callAPI("cardtags/delete?card_id=".$_POST['card_id']."&tag_id=".$tag_id);
            //echo(BASE_API."cardtags/delete?card_id=".$_POST['card_id']."&tag_id=".$tag_id);
            unset($_SESSION['c_tags'][$tag_id]);
           // echo($_SESSION['c_tags'][$tag_id]);
            unset($tag);
            unset($tag_id);
        }
    }
    if (count($to_add)>0){
        foreach ($to_add as $tag){
            //if adding tag
            if (!array_search($tag, $_SESSION['c_tags'])){
                //if it's in system add to card else, add tag then get id and add to card
                if(array_search($tag, $_SESSION['tags'])){
                    $tag_json= callAPI("cardtags/post?card_id=".$_POST['card_id']."&tag_id=".array_search($tag, $_SESSION['tags']));
                    if (isset($newCard_json)){
                        echo("true");
                    } else{
                        echo(false);
                    }
                } else{
                    $newCard_json = callAPI("tag/post?name=".$tag."&type=tag");
                    if (isset($newCard_json)){
                        $newCard = json_decode($newCard_json);
                        if (isset($newCard->id)){
                            callAPI("cardtags/post?card_id=".$_POST['card_id']."&tag_id=".$newCard->id);
                            $_SESSION['tags'][$newCard->id] = $newCard->name;
                            echo("true");
                        }else{
                             echo(false);
                     }
                    }
                }
            }
            unset($tag);
         }
    }

  
?>