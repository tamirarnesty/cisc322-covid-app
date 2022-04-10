<!DOCTYPE html>
<html lang="en">

<?php include('header.html'); ?>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Vaccination Records</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="main.css">
</head>

<body>
    <div class="main">
        <h1>Patient Vaccination Records</h1>

        <!-- Get patient name if selected -->
        <?php $selectedPatient = $_POST["OHIPNumber"]; ?>

        <p>Enter the patient's OHIP Number:</p>

        <!-- Load Name of Companies for the dropdown -->
        <form class="form-horizontal" role="form" action="viewPatientVaccines.php" method="post">
            <div class="form-fields">
                <!-- OHIP Number -->
                <div class="form-group row">
                    <label for="OHIPNumber" class="col-sm-2 col-form-label">OHIP Number</label>
                    <div class="col">
                        <input required type="text" class="form-control" id="OHIPNumber" name="OHIPNumber" value="<?php echo $selectedPatient; ?>" placeholder="OHIP Number">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </form>

        <?php
        include 'connectdb.php';

        $OHIPNumber = $_POST["OHIPNumber"];
        if ($OHIPNumber != "") {
            $query = "select * from Patient where OHIPNumber = :OHIPNumber";
            $stmt = $connection->prepare($query);
            $stmt->bindParam(':OHIPNumber', $OHIPNumber);
            $result = $stmt->execute();
            $patient = $stmt->fetchAll();

            // Check if OHIPNumber exists
            if (empty($patient)) {
                // Present message to ask if they want to add patient
                echo "Patient with OHIP number <b>" . $OHIPNumber . "</b> does not exist.<br>";
                echo "Select the button below to add a patient.<br>";
                echo '
                <form action="addPatient.php" method="post">
                    <input type="hidden" name="OHIPNumber" value="' . $OHIPNumber . '">
                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="submit" name="submit" class="btn btn-primary">Add New Patient</button>
                        </div>
                    </div>
                </form>';
            } else {

                // Get the patient's vaccination information
                $query = "select Company, OHIPNumber, VaccinationSite, VaccinationTime, VaccinationDate, v1.LotNumber from Vaccination as v1 join Vaccine as v2 on v1.LotNumber=v2.LotNumber where v1.OHIPNumber = :OHIPNumber";
                $stmt = $connection->prepare($query);
                $stmt->bindParam(':OHIPNumber', $OHIPNumber);
                $result = $stmt->execute();
                $vaccinations = $stmt->fetchAll();

                echo '
            <form action="addVaccinationRecord.php" method="post">
                <input type="hidden" name="OHIPNumber" value="' . $OHIPNumber . '">
                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" name="submit" class="btn btn-primary">Add Vaccination</button>
                    </div>
                </div>
            </form>';

                // Check if patient has any vaccinations
                if (empty($vaccinations)) {
                    echo "Patient with OHIP number <b>" . $OHIPNumber . "</b> has no vaccinations.<br>";
                } else {
                    // Display the patient's information and vaccinations as a table
                    echo "<h2>Vaccinations</h2>";
                    echo "<table class='table'>";
                    echo "<tr><th>Company</th><th>Vaccination Date</th><th>Vaccination Time</th><th>Vaccination Site</th><th>Vaccine Lot Number</th></tr>";
                    foreach ($vaccinations as $row) {
                        echo "<tr><td>" . $row['Company'] . "</td><td>" . $row['VaccinationDate'] . "</td><td>" . $row['VaccinationTime'] . "</td><td>" . $row['VaccinationSite'] . "</td><td>" . $row['LotNumber'] . "</td></tr>";
                    }
                    echo "</table>";
                }
            }
        }
        ?>
    </div>
</body>

<?php include('buttonFooter.html'); ?>

</html>