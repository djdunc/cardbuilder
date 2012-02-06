<?php 
 require_once ('../config.php');
 require_once ('functions.php');
 $request = $_POST['username'];
 $myURL = 'username?username='.$request;
 $myjson = callAPI($myURL); 
 if ($myjson!=''){
     echo('false');
 } else {
     echo('true');
 }
?>
