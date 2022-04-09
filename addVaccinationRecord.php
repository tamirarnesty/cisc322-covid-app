<!DOCTYPE html>
<html lang="en">

<?php include('header.html'); ?>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Vaccination Records | Add New</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="main.css">
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

        <!-- Get vaccination site if selected -->
        <?php $selectedSite = $_POST["vaccinationSite"]; ?>

        <!-- Get lot number if selected -->
        <?php $selectedLotNumber = $_POST["vaccineLotNumber"]; ?>

        <form name="vaccineRecordForm" class="form-horizontal needs-validation" novalidate role="form" action="addVaccinationRecord.php" method="post">
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
                    <form action="addVaccinationRecord.php" method="post">
                        <input type="hidden" name="OHIPNumber" value="<?php echo $_POST["OHIPNumber"]; ?>">
                        <input type="hidden" name="vaccinationSite" value="<?php echo $_POST["vaccinationSite"]; ?>">

                        <label for="vaccinationSite" class="col-sm-2 col-form-label">Vaccination Site</label>
                        <div class="col">
                            <select required class="custom-select" name="vaccinationSite" id="vaccinationSite">
                                <!-- Add initial option with value -- Select Lot Number -- -->
                                <option value>--Select Site--</option>
                                <?php
                                foreach ($vaccinationSites as $row) :
                                    $selected = $row["Name"] === $selectedSite ? " selected" : "";
                                ?>
                                    <option<?= $selected ?>> <?= htmlspecialchars($row["Name"]) ?></option>
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
                        <select required class="custom-select" name="vaccineLotNumber" id="vaccineLotNumber">
                            <!-- Add initial option with value -- Select Lot Number -- -->
                            <option value>--Select Lot Number--</option>
                            <?php
                            foreach ($vaccinesAtSites as $row) :
                                $selected = $row["LotNumber"] === $selectedLotNumber ? " selected" : "";
                            ?>
                                <option<?= $selected ?>> <?= htmlspecialchars($row["LotNumber"]) ?></option>
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

        // Check if form was submitted
        if (isset($_POST["submit-full-form"])) {
            // Get all form information
            $OHIPNumber = $_POST['OHIPNumber'];
            $vaccineLotNumber = $_POST['vaccineLotNumber'];
            $vaccinationSite = $_POST['vaccinationSite'];
            $vaccinationDate = $_POST['vaccinationDate'];
            $vaccinationTime = $_POST['vaccinationTime'];

            // If any of the fields equal "", display error message
            if ($OHIPNumber == "" || $vaccineLotNumber == "" || $vaccinationSite == "" || $vaccinationDate == "" || $vaccinationTime == "") {
                echo '<div class="alert alert-danger">Please fill in every field.</div>';
            } else {

                // Check if all form information is valid
                $query = "insert into Vaccination value (:OHIPNumber, :vaccineLotNumber, :vaccinationSite, :vaccinationDate, :vaccinationTime)";
                $stmt = $connection->prepare($query);
                $stmt->bindParam(':OHIPNumber', $OHIPNumber);
                $stmt->bindParam(':vaccineLotNumber', $vaccineLotNumber);
                $stmt->bindParam(':vaccinationSite', $vaccinationSite);
                $stmt->bindParam(':vaccinationDate', $vaccinationDate);
                $stmt->bindParam(':vaccinationTime', $vaccinationTime);
                // If all fields are set, add new patient to the database
                $result = executeStatement($stmt);

                if ($result) {
                    $message = "<div class='alert alert-success'>The vaccination record was successfully added!</div><br>";
                } else {
                    // Else, display error message.
                    $message = '<div class="alert alert-danger">An error occured adding the vaccination record. Please try again.</div>';
                }

                echo $message;

                // Show all vaccinations in table
                // Get the patient's vaccination information
                $query = "select Company, OHIPNumber, VaccinationSite, VaccinationTime, VaccinationDate, v1.LotNumber from Vaccination as v1 join Vaccine as v2 on v1.LotNumber=v2.LotNumber where v1.OHIPNumber = :OHIPNumber";
                $stmt = $connection->prepare($query);
                $stmt->bindParam(':OHIPNumber', $OHIPNumber);
                $result = executeStatement($stmt);
                $vaccinations = $stmt->fetchAll();

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

        <!-- Optional JavaScript -->
        <script>
            // Example starter JavaScript for disabling form submissions if there are invalid fields
            (function() {
                'use strict'

                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.querySelectorAll('.needs-validation')

                // Loop over them and prevent submission
                // If form does not have name submit-full-form, then skip
                Array.prototype.slice.call(forms)
                    .forEach(function(form) {
                        if (form.name != "submit-full-form") {
                            return
                        }
                        form.addEventListener('submit', function(event) {
                            if (!form.checkValidity()) {
                                event.preventDefault()
                                event.stopPropagation()
                            }

                            form.classList.add('was-validated')
                        }, false)
                    })
            })()
        </script>
    </div>
</body>

<?php include('buttonFooter.html'); ?>

</html>