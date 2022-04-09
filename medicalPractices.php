<?php
include 'connectdb.php';

try {
    $result = $connection->query("select distinct Name from MedicalPractice");
    $medicalPractices = $result->fetchAll();
} catch (PDOException $e) {
    echo "Error!: " . $e->getMessage() . "<br/>";
    die();
}
