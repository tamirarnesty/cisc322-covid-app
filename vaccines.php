<?php
include 'connectdb.php';

try {
    $result = $connection->query("SELECT * FROM Vaccine");
    $vaccines = $result->fetchAll();
} catch (PDOException $e) {
    echo "Error!: " . $e->getMessage() . "<br/>";
    die();
}
