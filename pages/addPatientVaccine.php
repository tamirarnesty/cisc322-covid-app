<!DOCTYPE html>
<html lang="en">

<?php include('../components/header.html'); ?>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> -->

    <title>Vaccination Records</title>
    <!-- <link rel="stylesheet" href="css/covid.css"> -->
</head>

<body>
    <h1>Add Vaccination for a Patient</h1>

    <!-- Load Name of Companies for the dropdown -->
    <form action="addPatientVaccine.php" method="post">
        <form>
            <p>Enter the patient's OHIP Number:</p>
            <input type="text" name="OHIPNumber"> <br>
            <input type="submit">
        </form>
    </form>

    <?php
    include 'connectdb.php';
    $OHIPNumber = $_POST["OHIPNumber"];

    if (isset($OHIPNumber)) {
        $query = "select * from Patient where OHIPNumber = :OHIPNumber";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':OHIPNumber', $OHIPNumber);
        $result = $stmt->execute();
        $data = $stmt->fetchAll();

        // Check if OHIPNumber exists
        if (empty($data)) {
            // Present message to ask if they want to add patient
            echo "Patient with OHIP number" . $OHIPNumber . " does not exist.";
            echo "Select the button below to add a patient.<br>";
        } else {
            // Display the patient's name
            echo "Patient: " . $data[0]["FirstName"] . " " . $data[0]["LastName"] . "<br>";

            // Display the patient's OHIPNumber
            echo "OHIPNumber: " . $data[0]["OHIPNumber"] . "<br>";

            // Display the patient's DOB
            echo "DOB: " . $data[0]["Birthdate"] . "<br>";
        }
    }
    ?>
</body>

</html>