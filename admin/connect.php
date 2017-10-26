<?php

$dsn = 'mysql:host=localhost;dbname=shop'; // data source name 
$user = 'root'; // database name 
$pass = ''; //  database password
$opation = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);

try {
    $conn = new PDO($dsn, $user, $pass, $opation);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo 'You are connected';
} catch (PDOExcption $e) {

    echo 'Error in connecton' . $e->getMessage();
}


