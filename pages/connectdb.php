<?php
$host = 'localhost';
$db   = 'covidDB';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host;dbname=$db";
try {
    $connection = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    echo "Error!: ". $e->getMessage(). "<br/>";
	die();
}
?>
