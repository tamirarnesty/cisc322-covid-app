<?php
function executeStatement($stmt) {
    try {
        return $stmt->execute();
    } catch (PDOException $e) {
        echo "Error!: " . $e->getMessage() . "<br/>";
        // die();
    }
}

function fetchStatement($stmt) {
    try {
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}
?>
