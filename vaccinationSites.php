<?php
include 'connectdb.php';
include 'statementUtils.php';

try {
    $result = $connection->query("select distinct Name from VaccinationSite");
    $vaccinationSites = $result->fetchAll();
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">An error occured getting the vaccination sites. Please try again.</div>';
}
