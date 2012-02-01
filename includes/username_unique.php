<?php 
 require_once ('../config.php');
 $request = $_POST['username'];
 $myURL = BASE_API.'username?username='.$request;
 $myjson = @file_get_contents($myURL); 
 if ($myjson!=''){
     echo('false');
 } else {
     echo('true');
 }
?>
