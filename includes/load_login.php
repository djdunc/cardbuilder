<?php
require_once ('../config.php');
require_once ('functions.php');
$options = array();

if ($_POST) {
    foreach ($_POST as $key => $value) {
        if ($key == 'controller' || $key == 'action') {
            $$key = $value;
        } else {
            if ($_SESSION['event_private'] && $key == 'code'){
                if ($_SESSION['event_private'] != $value){
                    echo('Incorrect event code, please try again.');
                    die;
                }
               
            }
            $options[$key]=$value;
        }
    }
}

$myURL = $controller.'/'.$action; 
$userobj = callAPI($myURL, $options, 'obj');

if($userobj && is_object($userobj) && $userobj->id) {
    $_SESSION['user'] = $userobj;
    $_SESSION['user_name'] = $userobj->first_name." ".$userobj->last_name;
    if ($userobj->organisation_id){$org_json = callAPI('organisation?id='.$userobj->organisation_id);
        $org = json_decode($org_json);
        $_SESSION['user_org'] = $org->name;
    }
    echo(json_encode($userobj));
} else {
    echo $userobj;
    die;
}
  
?>