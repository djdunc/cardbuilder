<?php
//globals
require_once 'config.php';
require_once 'includes/functions.php';
//create OAuth object using private key and secret
$_SESSION['oauth'] = new OAuth(PRIVATE_KEY, SECRET, OAUTH_SIG_METHOD_HMACSHA1);
//requested page
if(isset($_GET['do'])){ $page = $_GET['do']; }else{ $page ='home'; }
//details of event in session, if no event id set to default
// if(empty($_SESSION['event_id'])){
//     $_SESSION['event_id'] = 1;
// }

if(empty($_SESSION['event_id'])||$page=='home'){
    $main_event_json = callAPI("event?id=".$_SESSION['event_id']);
    if (isset($main_event_json )) { 
        $event = json_decode($main_event_json);
        if (isset($event)) { 
            $_SESSION['event_id'] = $event->id;
            $_SESSION['event_name'] = $event->name;
            $_SESSION['event_private']= $event->private;
            $_SESSION['event_owner'] = $event->owner;
            unset($_SESSION['org']);
            $owner_json = callAPI('user?id='.$event->owner);
            if (isset($owner_json)) { 
                $owner = json_decode($owner_json);
                if (isset($owner->organisation_id)){
                    $org_json = callAPI("organisation?id=".$owner->organisation_id);
                    if (isset($org_json)) { 
                        $org = json_decode($org_json);
                        $_SESSION['org'] = $org->name;
                    }
                }
            }
        }
    } else { 
        show_error("Sorry, the event you have requested does not exist.", "Make sure that you have the correct URL and that the owner hasn't deleted it. You can register <a href=\"index.php?do=register\">here</a> to create your own event.");
    }
}
//var_dump ($event);
//if card or create might need card id
if(isset($_GET['card_id'])){ $card_id = $_GET['card_id']; }

function show_error($h2, $body, $type="404"){
    $page="error";
    isset($h2) ? $message_h2 = $h2 :$message_h2 = "Sorry, the page you requested can't be found.";
    isset($body) ? $message_body = $body :$message_body = "Tere might be a typing error in the address, or you clicked an out-of date link. You can try: <ul><li>Retype the address</li><li>Go back to the <a href=\"".BASE_URL."\">homepage</a></li></ul><small>404 error</small>";
    switch($type){
        case '503':
        header("503 Service Unavailable");
        $title = "Private event, you must login to access contents";
        default:
        header("Status: 404 Not Found");
        $title = "Private event, you must login to access contents";
    }
	require_once('includes/header.php');
    require_once('includes/404.php');
}
if ($_SESSION['event_name']){
	//we have event, safe to display any page
    if ($_SESSION['event_private'] && empty($_SESSION['user'])){
        //do login
		$title = "Private event, you must login to access contents";
		require_once('includes/header.php');
        require_once('includes/login.php');
        require_once('includes/footer.php');
    } else {
        //do required page
        switch($page){
            case 'logout':
                 $_SESSION['user']=NULL;
                 $_SESSION['user_name']=NULL;
                //back to homepage
                header("Location: index.php"); 
                exit;
            break;
            case 'login':
             //do login
             // display the login form
              require_once('includes/header.php');
              require_once('includes/login.php');
              require_once('includes/footer.php');
            //}
             break;
              case 'register':
              // display the registration form
                require_once('includes/header.php');
                require_once('includes/register.php');
                require_once('includes/footer.php');
              break;
             case 'create':
                  $_SESSION['ref_page'] = 'create';
                 //user must always login to create
                 if(empty($_SESSION['user'])){
                     //do login
                       require_once('includes/header.php');
                       require_once('includes/login.php');
                       require_once('includes/footer.php');
                 }else{
                     //get all tags
                       //if (!isset($_SESSION['tags'])){
                           $tags_json = callAPI("tag");
                           if (isset($tags_json)) { 
                               $tags = array();
                               $raw_tags = json_decode($tags_json); 
                               foreach($raw_tags as $tag){
                                   if ($tag->type == "tag"){
                                       $tags[$tag->id] = $tag->name;
                                   }
                               }
                               unset($raw_tags);
                               unset($tag);
                               $_SESSION['tags'] = $tags;
                               unset($tags);
                        //}*/
                           //$tags = array();
                           
                       }
                       //var_dump ($_SESSION['tags']);
                      //
                     if (isset($card_id) && $card_id!='') {
                        //do edit
                        $_SESSION['card_id'] = $card_id;
                        $card_json = callAPI("card/get?id=".$card_id );
                        if (isset($card_json)) {
                            $card = json_decode($card_json);
                            //check logged in user is owner or event admin todo add superadmin
                            if ($card->owner!=$_SESSION['user']->id && $_SESSION['user']->id!=$_SESSION['event_owner']){
                                //404 error
                                show_error("Sorry, only the card owner can edit this card.","You can create your own card <a href=\"index.php?do=create\">here</a>.");
                                die;
                            }
                        }else{
                           //404 error
                           show_error("Sorry, the card you have requested does not exist.", "Make sure that you have the correct URL and that the owner hasn't deleted it. You can create your own card <a href=\"index.php?do=create\">here</a>.");
                        }
                        if (isset($card)) {
                            $c_tags_json =  callAPI("cardtags/get?card_id=".$card_id );
                            if (isset($c_tags_json)) {
                                //unset session card tags
                                $_SESSION['c_tags'] = array();
                                $raw_tags = json_decode($c_tags_json); 
                                   foreach($raw_tags as $tag){
                                     if (isset($_SESSION['tags'][$tag->tag_id])&&$tag->tag_id>=80){
                                           $_SESSION['c_tags'][$tag->tag_id] = $_SESSION['tags'][$tag->tag_id];
                                      }
                                   }
                            }
                           // var_dump($_SESSION['c_tags']);
                            //display card edit form
                            require_once('includes/header.php');
                            require_once('includes/create.php');
                            require_once('includes/footer.php');
                        } else{
                            //404 error
                             show_error("Sorry, the card you have requested does not exist.", "Make sure that you have the correct URL and that the owner hasn't deleted it. You can create your own card <a href=\"index.php?do=create\">here</a>.");
                        }
                      } else{
                          //do new
                          //display new card form
                         $card_id = 0;
                         $_SESSION['card_id'] = $card_id;
                         require_once('includes/header.php');
                         require_once('includes/create.php');
                         require_once('includes/footer.php');
                      }
                  }
             break;
             case 'view':
                $_SESSION['ref_page'] = 'view';
                $_SESSION['card_id'] = $card_id;
                 if (isset($card_id)) {
                    $card_json = callAPI("card/get?id=".$card_id."&include_owner=1");
//                    var_dump($card_json);
                    if (isset($card_json)) {$card = json_decode($card_json);}
                    if (isset($card->id)&&$card->status!='deleted') {
                        //category name
                        //$cat_json = @file_get_contents(BASE_API."category/get?id=".$card->category_id);
                        //owner name
                        $owner = $card->owner_user;
                        if ($owner->last_name || $owner->first_name){
                            $owner_name = $owner->last_name;
                            if ($owner->first_name){
                                $owner_name = $owner->first_name.' '.$owner->last_name;
                            }
                        } else{
                            $owner_name = $owner->username;
                        }
                        //if (isset($card_json)){$cat = json_decode($cat_json)->name;}
                       // if (!isset($card->card_front)){
                            //echo('no image');
                            //require_once('includes/create_card_front.php'); 
                       // } else{
                           $c_tags_json =  callAPI("cardtags/get?card_id=".$card_id );
                           if (isset($c_tags_json)) {
                               $_SESSION['c_tags'] = array();
                               $raw_tags = json_decode($c_tags_json); 
                                  foreach($raw_tags as $tag){
                                    if (isset($_SESSION['tags'][$tag->tag_id])&&$tag->tag_id>=80){
                                          $_SESSION['c_tags'][$tag->tag_id] = $_SESSION['tags'][$tag->tag_id];
                                     } 
                                     //todo: what if tag not in session?? 
                                  }
                           }
                           //var_dump($_SESSION['c_tags']);
                           require_once('includes/header.php');
                           require_once('includes/card.php'); 
                           require_once('includes/footer.php');
                       // }
                        } else{
                        show_error("Sorry, the card you have requested does not exist.", "Make sure that you have the correct URL and that the owner hasn't deleted it. You can create your own card <a href=\"index.php?do=create\">here</a>.","404");
                      }
                  } else{
                      //404 error
                         show_error("Sorry, the card you have requested does not exist.", "Make sure that you have the correct URL and that the owner hasn't deleted it. You can create your own card <a href=\"index.php?do=create\">here</a>.");
                  }
                 break;
             case 'explore':
               $_SESSION['ref_page'] = 'explore';
               $event_cards_json = callAPI("card?event_id=".$_SESSION['event_id']);
                if (isset($event_cards_json)) {$event_cards = json_decode($event_cards_json);}
               require_once('includes/header.php');
                require_once('includes/explore.php');
                require_once('includes/footer.php');
             break;
             case 'admin':
                 $_SESSION['ref_page'] = 'admin';
                //user must always login for admin and be an admin too (@todo)
                 if(empty($_SESSION['user'])){
                     //do login 
                       require_once('includes/header.php');
                       require_once('includes/login.php');
                       require_once('includes/footer.php');
                }else{
                    $events_json = callAPI("event?owner=".$_SESSION['user']->id);
                    if (isset($events_json)) {
                        $events = json_decode($events_json);
                        require_once('includes/header.php');
                        require_once('includes/admin.php');
                        require_once('includes/footer.php');
                   } else{
                    //404 error
                    show_error("Sorry, there was a problem loading the event.", "Please try again later.","503");
                    }
                   
                }
              break;
             case 'form':
             $_SESSION['ref_page'] = 'admin';
             //user must always login for admin and be an admin too (@todo)
              if(empty($_SESSION['user'])){
                   //do login 
                     require_once('includes/header.php');
                     require_once('includes/login.php');
                     require_once('includes/footer.php');
                } else{
                     if(isset($_GET['edit_event'])){
                         $edit_event_id = $_GET['edit_event'];
                     }
                     if (isset($edit_event_id)) {
                         $edit_event_json = callAPI("event/get?id=".$edit_event_id);
                         if (isset($edit_event_json)){$edit_event = json_decode($edit_event_json);}
                         $event_cards_json = callAPI("card?event_id=".$edit_event_id);
                         if (isset($event_cards_json)&&$event_cards_json!='[]') {$event_cards = json_decode($event_cards_json);}
                     }
                     require_once('includes/header.php');
                     require_once('includes/form.php');
                     require_once('includes/footer.php');
             }
             break;
             default:
             $_SESSION['ref_page'] = "";
             if(!isset($event)){
                 $event_json = callAPI("event?id=".$_SESSION['event_id']);
                  $event = json_decode($event_json);
             }
             //get cards for event
             $event_cards_json = callAPI("card?event_id=".$_SESSION['event_id']);
             if (isset($event_cards_json)) {$event_cards = json_decode($event_cards_json);}
             //@todo: get arup cards
             require_once('includes/header.php');
             require_once('includes/home.php');
             require_once('includes/footer.php');
         }
     }
 }
// var_dump($event);
 //todo add error
?>