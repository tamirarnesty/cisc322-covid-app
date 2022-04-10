<?php
include 'connectdb.php';

try {
    $result = $connection->query("select distinct LotNumber from ShipsTo");
    $vaccinesAtSites = $result->fetchAll();
} catch (PDOException $e) {
    echo "Error!: " . $e->getMessage() . "<br/>";
    die();
}

function getVaccinesAtSites($connection, $SiteName)
{
    try {
    $query = "select LotNumber from ShipsTo where SiteName = :SiteName";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':SiteName', $SiteName);
    $result = $stmt->execute();
    return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">An error occured getting the vaccines at ' . $SiteName . '. Please try again.</div>';
    }
}
