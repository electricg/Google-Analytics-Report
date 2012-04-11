<?php
// DB connection
define('HOST', 'localhost');
define('USER', 'my_user');
define('PWD', 'my_password');
define('DB', 'my_db');


// Google
define('ga_email','my_gmail');// username
define('ga_password','my_password');	// password
define('ga_profile_id','00000000');		// profile id - do not change this
define('ga_no_external', 000000000);    // 'no external' advanced segment setted in the account


// PHP settings
error_reporting(E_ALL ^ E_NOTICE); // report all errors except E_NOTICE
set_time_limit(60*5); // number of seconds the script is allowed to run, fetching data from GA takes a while


// Connect to DB
$conn = mysql_connect(HOST, USER, PWD);
if (!$conn) {
	die('Could not connect: ' . mysql_error());
}
mysql_select_db(DB);
?>