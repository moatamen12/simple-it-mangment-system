<?php
//starting the sesstion 
session_start();
//creat constant to store non repeating values

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "M64050690n";
$dbname = "it_sys";


if (!$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname))
    die("failed to connect!");

?>

