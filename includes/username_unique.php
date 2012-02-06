<?php 
 require_once ('../config.php');
 $request = $_POST['username'];
 $myURL = 'username?username='.$request;
 $myjson = callAPI($myURL); 
 if ($myjson!=''){
     echo('false');
 } else {
     echo('true');
 }
?>
