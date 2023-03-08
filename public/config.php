<?php 
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'everest';

$connect = new mysqli($host, $user, $password, $database);

if (!$connect) {
	die("Connection Failed");
}

?>