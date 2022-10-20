<?php

define('DB_HOST', 'localhost');

define('DB_USER','medicsof_users');

define('DB_PASS' ,'Husnamaret()');

define('DB_NAME', 'medicsof_hisdb');

$con = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);

// Check connection

if (mysqli_connect_errno())

{

 echo "Failed to connect to MySQL: " . mysqli_connect_error();

}

?>