<!DOCTYPE html>
<html lang="en">

<?php include('header.html'); ?>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Patient Vaccinations</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="main.css">
</head>

<body>
    <div class="main">
        <h1>Patient Vaccination Details</h1>

        <!-- Load Patient names for the dropdown -->
        <?php
        include 'connectdb.php';
        include 'statementUtils.php';

        try {
            $query = 'select OHIPNumber, FirstName, MiddleName, LastName from Patient';
            $result = $connection->query($query);
            $data = fetchStatement($result);
        } catch (PDOException $e) {
            echo "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        ?>

        <!-- Get patient name if selected -->
        <?php $selectedPatient = $_POST["Name"]; ?>

        <!-- Present the dropdown -->
        <form action="viewPatients.php" method="post">
            <div class="form-fields">
                <div class="form-group row">
                    <label for="OHIPNumber" class="col-sm-2 col-form-label">Choose a Patient:</label>
                    <div class="col">
                        <select required class="custom-select" name="Name" id="Name">
                            <!-- Add initial option with value -- Select Site -- -->
                            <option value>--Select Patient--</option>
                            <?php
                            foreach ($data as $row) :
                                if ($row["MiddleName"] == null) {
                                    $name = $row["FirstName"] . " " . $row["LastName"];
                                } else {
                                    $name = $row["FirstName"] . " " . $row["MiddleName"] . " " . $row["LastName"];
                                }
                                $selected = $name === $selectedPatient ? " selected" : "";
                            ?>
                                <option<?= $selected ?>> <?= htmlspecialchars($name) ?></option>
                                <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <!-- Submit Button -->
                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" name="submit-full-form" class="btn btn-primary">View Patient</button>
                    </div>
                </div>
            </div>

            <?php
            include 'connectdb.php';
            $name = explode(" ", $_POST["Name"]);
            if (count($name) == 2) {
                $firstName = $name[0];
                $middleName = "";
                $lastName = $name[1];
            } else {
                $firstName = $name[0];
                $middleName = $name[1];
                $lastName = $name[2];
            }

            function getOHIP($connection, $firstName, $middleName, $lastName)
            {
                try {
                    if ($middleName == "") {
                        $query = "select OHIPNumber from Patient where FirstName = :firstName and LastName = :lastName";
                    } else {
                        $query = "select OHIPNumber from Patient where FirstName = :firstName and MiddleName = :middleName and LastName = :lastName";
                    }
                    $stmt = $connection->prepare($query);
                    $stmt->bindParam(':firstName', $firstName);
                    if ($middleName != "") {
                        $stmt->bindParam(':middleName', $middleName);
                    }
                    $stmt->bindParam(':lastName', $lastName);
                    $result = executeStatement($stmt);

                    // check if query worked
                    if ($result) {
                        return fetchStatement($stmt)[0]["OHIPNumber"];
                    }
                } catch (PDOException $e) {
                    echo '<div class="alert alert-danger">An error occured getting the OHIP number. Please try again.</div>';
                }
            }

            // Get the OHIP number of the patient
            $OHIP = getOHIP($connection, $firstName, $middleName, $lastName);

            // Get the list of vaccinations for the patient
            if (isset($OHIP)) {
                try {
                    $query = "select * from Vaccination as v join Patient as p on p.OHIPNumber=v.OHIPNumber join Vaccine as c on v.LotNumber=c.LotNumber where p.OHIPNumber=:OHIPNumber";
                    $stmt = $connection->prepare($query);
                    $stmt->bindParam(':OHIPNumber', $OHIP);
                    $result = executeStatement($stmt);
                    $data = fetchStatement($stmt);

                    // Check if the query returned any results and if not, display a message
                    if (empty($data)) {
                        echo "Patient <b>" . $selectedPatient . "</b> has no vaccinations.<br>";
                    } else {
                        // Table for the list of vaccinations
                        echo "<h2>Vaccinations</h2>";
                        echo "<table class='table'>";
                        // OHIP, Patient Name, Vaccination Date, Vaccination Time, Company, Lot Number
                        echo "<tr><th>OHIP Number</th><th>Patient Name</th><th>Vaccination Date</th><th>Vaccination Time</th><th>Company</th><th>Lot Number</th></tr>";
                        foreach ($data as $row) :
                            echo "<tr><td>" . $row["OHIPNumber"] . "</td><td>" . $row["FirstName"] . " " . $row["MiddleName"] . " " . $row["LastName"] . "</td><td>" . $row["VaccinationDate"] . "</td><td>" . $row["VaccinationTime"] . "</td><td>" . $row["Company"] . "</td><td>" . $row["LotNumber"] . "</td></tr>";
                        endforeach;
                        echo "</table>";
                    }
                } catch (PDOException $e) {
                    echo '<div class="alert alert-danger">An error occured getting the list of vaccinations for patient with OHIP number ' . $OHIP . '. Please try again.</div>';
                }
            }
            ?>
        </form>
    </div>
</body>

<?php include('buttonFooter.html'); ?>

</html>