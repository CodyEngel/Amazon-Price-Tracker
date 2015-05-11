<?php
error_reporting(0);
//error_reporting(E_ERROR | E_PARSE | E_WARNING | E_NOTICE); // E_WARNING E_NOTICE ignored

$root_directory	= "http://192.168.1.30:52990";

$gmail_username = "unmarketpricealert";  
$gmail_password = "GHFTByxzvmLh8HjdAxYJip7uB7Yc36FDiXGiHpfE";  

$db_user 		= "unmarket";
$db_password	= "Z6hjQ6mP*%rJAJhd";
$db_name		= "unmarket";
$db_host		= "localhost";

$DBO = new mysqli($db_host, $db_user, $db_password, $db_name, 3306);
if ($DBO->connect_errno) {
    echo "Failed to connect to MySQL: (" . $DBO->connect_errno . ") " . $DBO->connect_error;
}

spl_autoload_register(function ($class) {
	
	if(!@include("/includes/model/" . $class . ".class.php"))
	{
		if(!@include("../includes/model/" . $class . ".class.php"))
		{
			//echo "Cannot include class: " . $class . "<br/>";
		}
	}
});