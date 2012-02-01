<?php
session_start();
/** OpenDecks's config file **/
// ** API settings ** //
date_default_timezone_set('GMT');
define('ABSPATH', dirname(__FILE__).'/');
define('UPLOADS_DIR', ABSPATH.'uploads/');
//$folder = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
define('BASE_URL','http://create.driversofchange.com/');
define('UPLOADS_URL', BASE_URL.'uploads/');
// ** API settings ** //
define('BASE_API', 'api.driversofchange.com/api/');
define('ADMIN_EMAIL','alicia@gmail.com');
define('PRIVATE_KEY', 'eb9e55feac986debc4c5db1e9162f5');
define('SECRET','c2429b8871');

//define steep category names
$steep = array (1=>"social", 2=>"technological", 3=>"economic", 4=>"environmental", 5=>"political");
$steep_cols = array (1=>"39B1D9", 2=>"A681B2", 3=>"F69220", 4=>"8BC53F", 5=>"EC2027");

//session vars
//$_SESSION['user_id']=1;
if(isset($_GET['event'])&&($_SESSION['event_id']!=$_GET['event'])){
    $_SESSION['event_id']=$_GET['event'];
    $_SESSION['event_name']=NULL;
    $_SESSION['event_private']=NULL;
    $_SESSION['event_owner']=NULL;
} elseif ( empty($_SESSION['event_id']) ){
    $_SESSION['event_id'] = 1;
    $_SESSION['event_name']=NULL;
    $_SESSION['event_private']=NULL;
    $_SESSION['event_owner']=NULL;
}
//

?>