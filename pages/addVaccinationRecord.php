<!DOCTYPE html>
<html lang="en">

<?php include('../components/header.html'); ?>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Vaccination Records | Add New</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../css/main.css">
</head>

<body>
    <div class="main">
        <h1>Create Vaccination Record</h1>

        <p>Enter the vaccination details below.</p>

        <?php
        // Get the company names from the database
        include 'companyNames.php';

        // Get vaccination sites from the database
        include 'vaccinationSites.php';

        // Get vaccine lot numbers from the database
        include 'vaccinesAtSites.php';
        ?>

        <form name="vaccineRecordForm" class="form-horizontal" role="form" action="../pages/addVaccinationRecord.php" method="post">
            <input type="hidden" name="OHIPNumber" value="<?php echo $_POST["OHIPNumber"]; ?>">
            <div class="form-fields">
                <!-- Patient OHIP Number, disabled -->
                <div class="form-group row">
                    <label for="patientOHIPNumber" class="col-sm-2 col-form-label">OHIP Number</label>
                    <div class="col">
                        <input type="text" class="form-control" id="OHIPNumber" name="OHIPNumber" value="<?php echo $_POST["OHIPNumber"]; ?>" disabled>
                    </div>
                </div>

                <!-- Select Vaccination Site -->
                <div class="form-group row">
                    <form action="../pages/addVaccinationRecord.php" method="post">
                        <input type="hidden" name="OHIPNumber" value="<?php echo $_POST["OHIPNumber"]; ?>">
                        <input type="hidden" name="vaccinationSite" value="<?php echo $_POST["vaccinationSite"]; ?>">

                        <label for="vaccinationSite" class="col-sm-2 col-form-label">Vaccination Site</label>
                        <div class="col">
                            <select class="custom-select" name="vaccinationSite" id="vaccinationSite">
                                <!-- Add initial option with value -- Select Lot Number -- -->
                                <option value="0">--Select Site--</option>
                                <?php foreach ($vaccinationSites as $row) : ?>
                                    <option><?= $row["Name"] ?></option>
                                <?php endforeach ?>
                            </select>
                            <div class="invalid-feedback">Missing vaccine lot number.</div>
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" name="submit-vaccination-site" class="btn btn-primary">Update Lot Numbers</button>
                        </div>
                    </form>
                </div>

                <!-- Get vaccines at a site from vaccinationSite -->
                <?php
                if (isset($_POST["submit-vaccination-site"])) {
                    $vaccinesAtSites = getVaccinesAtSites($connection, $_POST["vaccinationSite"]);
                }
                ?>

                <!-- Select Lot Number -->
                <div class="form-group row">
                    <label for="vaccineLotNumber" class="col-sm-2 col-form-label">Vaccine Lot Number</label>
                    <div class="col">
                        <select class="custom-select" name="vaccineLotNumber" id="vaccineLotNumber">
                            <!-- Add initial option with value -- Select Lot Number -- -->
                            <option value="0">--Select Lot Number--</option>
                            <?php foreach ($vaccinesAtSites as $row) : ?>
                                <option><?= $row["LotNumber"] ?></option>
                            <?php endforeach ?>
                        </select>
                        <div class="invalid-feedback">Missing vaccine lot number.</div>
                    </div>
                </div>

                <!-- Select date -->
                <div class="form-group row">
                    <label for="date" class="col-sm-2 col-form-label">Date</label>
                    <div class="col">
                        <input type="date" class="form-control" name="vaccinationDate" id="vaccinationDate" placeholder="Date">
                        <div class="invalid-feedback">Missing date.</div>
                    </div>
                </div>

                <!-- Select time -->
                <div class="form-group row">
                    <label for="time" class="col-sm-2 col-form-label">Time</label>
                    <div class="col">
                        <input type="time" step="1" class="form-control" name="vaccinationTime" id="vaccinationTime" placeholder="Time">
                        <div class="invalid-feedback">Missing time.</div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" name="submit-full-form" class="btn btn-primary">Submit Vaccination</button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Add vaccination record to the database from the form information. -->
        <?php
        include 'connectdb.php';
        include 'statementUtils.php';

        function addVaccinationRecord($connection, $OHIPNumber, $vaccineLotNumber, $vaccinationSite, $vaccinationDate, $vaccinationTime)
        {
            $query = "insert into Vaccination value (:OHIPNumber, :vaccineLotNumber, :vaccinationSite, :vaccinationDate, :vaccinationTime)";
            $stmt = $connection->prepare($query);
            $stmt->bindParam(':OHIPNumber', $OHIPNumber);
            $stmt->bindParam(':vaccineLotNumber', $vaccineLotNumber);
            $stmt->bindParam(':vaccinationSite', $vaccinationSite);
            $stmt->bindParam(':vaccinationDate', $vaccinationDate);
            $stmt->bindParam(':vaccinationTime', $vaccinationTime);
            return $stmt->execute();
        }

        // Check if form was submitted
        if (!isset($_POST["submit-full-form"])) {
            return;
        }

        // Get all form information
        $OHIPNumber = $_POST['OHIPNumber'];
        $vaccineLotNumber = $_POST['vaccineLotNumber'];
        $vaccinationSite = $_POST['vaccinationSite'];
        $vaccinationDate = $_POST['vaccinationDate'];
        $vaccinationTime = $_POST['vaccinationTime'];

        // Check if all form information is valid

        // If all fields are set, add new patient to the database
        $result = addVaccinationRecord($connection, $OHIPNumber, $vaccineLotNumber, $vaccinationSite, $vaccinationDate, $vaccinationTime);
        if ($result) {
            $result = "<div class='alert alert-success'>The vaccination record was successfully added!</div><br>";
        } else {
            // Else, display error message.
            $result = '<div class="alert alert-danger">An error occured adding the vaccination record. Please try again.</div>';
        }

        echo $result;
        ?>
    </div>

</body>

<?php include('../'); ?>

</html>