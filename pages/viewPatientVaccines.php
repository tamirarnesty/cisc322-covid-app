<!DOCTYPE html>
<html lang="en">

<?php include('../components/header.html'); ?>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Vaccination Records</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../css/main.css">
</head>

<body>
    <div class="main">

        <h1>Patient's Vaccinations</h1>

        <p>Enter the patient's OHIP Number:</p>
        <!-- Load Name of Companies for the dropdown -->
        <form class="form-horizontal" role="form" action="viewPatientVaccines.php" method="post">
            <div class="form-fields">
                <div class="form-group row">
                    <label for="ohipNumber" class="col-sm-1 col-form-label">OHIP Number</label>
                    <div class="col">
                        <input type="text" class="form-control" id="OHIPNumber" name="OHIPNumber" placeholder="OHIP Number">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </form>

        <?php
        include 'connectdb.php';
        if (!$_POST["OHIPNumber"]) {
            $errOHIP = "Please enter an OHIP number.";
            return;
        }

        $OHIPNumber = $_POST["OHIPNumber"];

        function runQuery($connection, $query, $OHIPNumber)
        {
            $stmt = $connection->prepare($query);
            $stmt->bindParam(':OHIPNumber', $OHIPNumber);
            $result = $stmt->execute();
            $data = $stmt->fetchAll();
            return $data;
        }
        function getPatient($connection, $OHIPNumber)
        {
            return runQuery($connection, $OHIPNumber, "select * from Patient where OHIPNumber = :OHIPNumber");
        }

        function getVaccinations($connection, $OHIPNumber)
        {
            return runQuery($connection, $OHIPNumber, "select Company, OHIPNumber, VaccinationSite, VaccinationTime, VaccinationDate, v1.LotNumber from Vaccination as v1 join Vaccine as v2 on v1.LotNumber=v2.LotNumber where v1.OHIPNumber = :OHIPNumber");
        }

        if (isset($OHIPNumber)) {
            $query = "select * from Patient where OHIPNumber = :OHIPNumber";
            $stmt = $connection->prepare($query);
            $stmt->bindParam(':OHIPNumber', $OHIPNumber);
            $result = $stmt->execute();
            $patient = $stmt->fetchAll();

            // $patient = getPatient($connection, $OHIPNumber);

            // Check if OHIPNumber exists
            if (empty($patient)) {
                // Present message to ask if they want to add patient
                echo "<br>Patient with OHIP number <b>" . $OHIPNumber . "</b> does not exist.<br>";
                echo "Select the button below to add a patient.<br>";
                echo "<div class='container-fluid'>
                <a href='../pages/addPatient.php' class='btn btn-primary'>Add New Patient</a>
            </div>";
                return;
            }

            // Get the patient's vaccination information
            $query = "select Company, OHIPNumber, VaccinationSite, VaccinationTime, VaccinationDate, v1.LotNumber from Vaccination as v1 join Vaccine as v2 on v1.LotNumber=v2.LotNumber where v1.OHIPNumber = :OHIPNumber";
            $stmt = $connection->prepare($query);
            $stmt->bindParam(':OHIPNumber', $OHIPNumber);
            $result = $stmt->execute();
            $vaccinations = $stmt->fetchAll();

            // $vaccinations = getVaccinations($connection, $OHIPNumber);
            echo '<br>
            <form action="../pages/addVaccinationRecord.php" method="post">
                <input type="hidden" name="OHIPNumber" value="' . $OHIPNumber . '">
                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" name="submit" class="btn btn-primary">Add Vaccination</button>
                    </div>
                </div>
            </form>';

            // Check if patient has any vaccinations
            if (empty($vaccinations)) {
                echo "<br>Patient with OHIP number <b>" . $OHIPNumber . "</b> has no vaccinations.<br>";
            } else {

                // Display the patient's information and vaccinations as a table
                echo "<br><table border='1'>";
                echo "<tr><th>Company</th><th>Vaccination Date</th><th>Vaccination Time</th><th>Vaccination Site</th><th>Vaccine Lot Number</th></tr>";
                foreach ($vaccinations as $row) {
                    echo "<tr><td>" . $row['Company'] . "</td><td>" . $row['VaccinationDate'] . "</td><td>" . $row['VaccinationTime'] . "</td><td>" . $row['VaccinationSite'] . "</td><td>" . $row['LotNumber'] . "</td></tr>";
                }
            }
        }
        ?>
    </div>
</body>

<?php include('components/footer.html'); ?>

</html>