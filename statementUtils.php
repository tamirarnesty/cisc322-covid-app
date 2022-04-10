<?php
function executeStatement($stmt) {
    try {
        return $stmt->execute();
    } catch (PDOException $e) {
        return handleError($e);
    }
}

function fetchStatement($stmt) {
    try {
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return handleError($e);
    }
}

function handleError($error) {
    // Get error code from PDOException
    return $error->getCode();
}
?>