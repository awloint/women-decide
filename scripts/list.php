<?php
ini_set('display_errors', 1);
 require '../dbconfig.php';
 // Connect to the Database using PDO

$dsn = "mysql:host=$host;dbname=$db";

//Create PDO Connection with the dbconfig data
$conn = new PDO($dsn, $username, $password);
 //Select all the data of Registered user from the Database
$registeredusers = $conn->query('SELECT * FROM women_decide');
 //fetch the data
$data = $registeredusers->fetchAll();
 //echo the data as JSON Data
echo json_encode($data);