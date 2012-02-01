<?php
function callAPI($query_params) {
	$json_string = "";
    try {
    	$request = BASE_API.$query_params;
        //create OAuth object using private key and secret
        $oauth = new OAuth(PRIVATE_KEY, SECRET, OAUTH_SIG_METHOD_HMACSHA1);
        //parameters passed as data using array 
        $oauth->fetch($request ,array('id'=>1), OAUTH_HTTP_METHOD_GET);

        //get JSON response string
        $json_string = $oauth->getLastResponse();   
//        OR go straight to PHP object (if JSON string isn't required for JS).
//        $php_object = json_decode($oauth->getLastResponse());
//        OR get an associative array passing true as the second parameter
//        $php_assoc_array = json_decode($oauth->getLastResponse(), true);

    } catch(OAuthException $E) {
        echo "Exception caught!\n";
        echo "Response: ". $E->lastResponse . "\n";
        echo "Response: ". $E->getMessage() . "\n";
    }
    return 	$json_string;
}