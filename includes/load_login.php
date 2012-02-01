<?php
require_once ('../config.php');
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
  $myURL = BASE_API.$controller.'/'.$action.'?'; 
  $myURL .= http_build_query($options,'','&');
     //echo($myURL);
  //die;
  // Create a stream
  $h_opts = array(
    'http'=>array(
      'method'=>$action,
      'header'=>"Accept: text/html\r\n"
    )
  );
  $context = stream_context_create($h_opts);
  $myjson = @file_get_contents($myURL,false, $context); 
  if ($myjson!=''){
       $mydata = json_decode($myjson);
       if ($mydata->id){
           $_SESSION['user_id'] = $mydata->id;
           $nameURL = BASE_API.'user?id='.$_SESSION['user_id']; 
           $namejson = @file_get_contents($myURL); 
           $user = json_decode($namejson);
           $_SESSION['user_name'] = $user->first_name." ".$user->last_name;
           $_SESSION['LoggedIn'] = true;
           echo($myjson);
       }else{
        echo 'false';
        } 
  }else{
        echo 'false';
        die;
   }
  
?>