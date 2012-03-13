<?php
require_once ('../config.php');
require_once ('functions.php');
$options = array();

if ($_POST) {
    foreach ($_POST as $key => $value) {
        if ($key == 'controller' || $key == 'action') {
            $$key = $value;
        } else {   
            $options[$key]=$value;
        }
    }
}

$myURL = $controller.'/'.$action; 
$userobj = callAPI($myURL, $options, 'obj');

if($userobj && is_object($userobj) && $user->id) {
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