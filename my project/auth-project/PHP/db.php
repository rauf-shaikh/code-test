<?php
//-------------db connect--------------
// TODO store credentials in .env file and add it to git ignore

$server = "localhost";
$username = "project";
$password = "hackur@123";
$database = "users";

$conn = mysqli_connect($server, $username, $password, $database);
if (!$conn){
    die("Error". mysqli_connect_error());
}
?>