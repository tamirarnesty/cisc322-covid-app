<?php
include 'statementUtils.php';

function getPatientVaccinations($connection, $OHIPNumber) {
    try {
        // Get the patient's vaccination information
        $query = "select Company, OHIPNumber, VaccinationSite, VaccinationTime, VaccinationDate, v1.LotNumber from Vaccination as v1 join Vaccine as v2 on v1.LotNumber=v2.LotNumber where v1.OHIPNumber = :OHIPNumber";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':OHIPNumber', $OHIPNumber);
        $result = executeStatement($stmt);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}

function getPatient($connection, $OHIPNumber) {
    try {
        $query = "select * from Patient where OHIPNumber = :OHIPNumber";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':OHIPNumber', $OHIPNumber);
        $result = executeStatement($stmt);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}
