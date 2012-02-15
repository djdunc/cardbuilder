<?php
require_once ('../config.php');
require_once ('functions.php');
$options = array();

if ($_POST) {
  $kv = array();
  foreach ($_POST as $key => $value) {
     if ($key == 'controller'){
            $controller = $value;
    } elseif($key == 'action'){
        $action = $value;
         //echo($value);
    }else{   
           $options[$key]=$value;
    }
  }
}
  $myURL = $controller.'/'.$action.'?'; 
  $myURL .= http_build_query($options,'','&');
  $myjson = callAPI($myURL); 
  if ($myjson!=''){
       $mydata = json_decode($myjson);
       if ($mydata->id){
           $_SESSION['user_id'] = $mydata->id;
           $nameURL = 'user?id='.$_SESSION['user_id']; 
           $namejson = callAPI($myURL); 
           $user = json_decode($namejson);
           $_SESSION['user_name'] = $user->first_name." ".$user->last_name;
           if ($user->organisation_id){$org_json = callAPI('organisation?id='.$user->organisation_id);
            $org = json_decode($org_json);
            $_SESSION['user_org'] = $org->name;
            }
           $_SESSION['LoggedIn'] = true;
           echo($myjson);
       }else{
         echo 'false';
         die;
        } 
  }else{
        echo 'false';
        die;
   }
  
?>