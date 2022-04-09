<!DOCTYPE html>
<html lang="en">

<?php include('header.html'); ?>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>COVID-19 Vaccination Site</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="main.css">
</head>

<body>
    <div class="main">
        <h1>Add New Health Care Worker</h1>

        <p>Enter the health care worker's details below.</p>

        <?php
        // Get vaccination sites from the database
        include 'vaccinationSites.php';

        // Get medical practices from the database
        include 'medicalPractices.php';
        ?>

        <!-- Get vaccination site if selected -->
        <?php $selectedSite = $_POST["vaccinationSite"]; ?>

        <!-- Get health care worker option if selected -->
        <?php $selectedWorker = $_POST["workerType"]; ?>

        <!-- Get medical practice if selected -->
        <?php $selectedPractice = $_POST["medicalPractice"]; ?>

        <form class="form-horizontal needs-validation" novalidate role="form" action="addWorkers.php" method="post">
            <!-- Select worker type and location -->
            <h4>Worker Type and Location</h4>
            <div class="form-fields">
                <!-- Select health care worker type -->
                <fieldset class="row mb-3">
                    <legend class="col-form-label col-sm-2 pt-0">Worker Type</legend>
                    <div class="col-sm-10">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" onclick="javascript:showDoctorFields();" type="radio" name="workerRadios" id="nurseRadio" <?php if ($selectedWorker == "Nurse") {
                                                                                                                                                            echo ' checked="checked"';
                                                                                                                                                        } ?> value="nurse">
                            <label class="form-check-label" for="nurseRadio">
                                Nurse
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" onclick="javascript:showDoctorFields();" type="radio" name="workerRadios" id="doctorRadio" <?php if ($selectedWorker == "Doctor") {
                                                                                                                                                            echo ' checked="checked"';
                                                                                                                                                        } ?>value="doctor">
                            <label class="form-check-label" for="doctorRadio">
                                Doctor
                            </label>
                            <div class="invalid-feedback">Select a worker type.</div>
                        </div>
                    </div>
                </fieldset>

                <!-- Select Vaccination Site -->
                <div class="form-group row">
                    <label for="vaccinationSite" class="col-sm-2 col-form-label">Vaccination Site</label>
                    <div class="col">
                        <select required class="custom-select" name="vaccinationSite" id="vaccinationSite">
                            <!-- Add initial option with value -- Select Lot Number -- -->
                            <option value>--Select Site--</option>
                            <?php
                            foreach ($vaccinationSites as $row) :
                                $selected = $row["Name"] === $selectedSite ? "selected" : "";
                            ?>
                                <option<?= $selected ?>> <?= htmlspecialchars($row["Name"]) ?></option>
                                <?php endforeach ?>
                        </select>
                        <div class="invalid-feedback">Missing vaccination site.</div>
                    </div>
                </div>
            </div>

            <!-- Select doctor practice details if doctor selected -->
            <div id="doctorPractice" style="display:none">
                <h4>Medical Practice Details</h4>
                <div class="form-fields">
                    <!-- Select Medical Practice -->
                    <div class="form-group row">
                        <label for="medicalPractice" class="col-sm-2 col-form-label">Practice Name</label>
                        <div class="col">
                            <select required class="custom-select" name="medicalPractice" id="medicalPractice">
                                <!-- Add initial option with value -- Select Practice -- -->
                                <option value>--Select Practice--</option>
                                <?php
                                foreach ($medicalPractices as $row) :
                                    $selected = $row["Name"] === $selectedPractice ? " selected" : "";
                                ?>
                                    <option<?= $selected ?>> <?= htmlspecialchars($row["Name"]) ?></option>
                                    <?php endforeach ?>
                            </select>
                            <div class="invalid-feedback">Missing vaccine lot number.</div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Enter health care worker details -->
            <h4>Health Care Worker Details</h4>
            <div class="form-fields">
                <!-- ID -->
                <div class="form-group row">
                    <label for="id" class="col-sm-2 col-form-label">ID</label>
                    <div class="col-sm-10">
                        <input required type="text" class="form-control" id="id" name="id" placeholder="ID">
                        <div class="invalid-feedback">Missing ID.</div>
                    </div>
                </div>

                <!-- Name -->
                <div class="form-group row">
                    <label for="fullName" class="col-sm-2 col-form-label">Full Name</label>
                    <div class="col">
                        <input required type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name">
                        <div class="invalid-feedback">Missing first name.</div>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" id="middleName" name="middleName" placeholder="Middle Name">
                    </div>
                    <div class="col">
                        <input required type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name">
                        <div class="invalid-feedback">Missing last name.</div>
                    </div>
                </div>
            </div>

            <!-- Enter worker credentials -->
            <h4>Health Care Credentials</h4>
            <div class="form-fields">
                <!-- Credentials field with button to add more -->
                <div class="form-group row">
                    <label for="credentials" class="col-sm-2 col-form-label">Credentials</label>
                    <div class="col-sm-10">
                        <input required type="text" class="form-control" id="credentials" name="credentials" placeholder="Credentials" aria-describedby="credentialsHelp">
                        <div id="credentialsHelp" class="form-text">Enter all credentials separated by commas. Eg. RN, NP, CPN, etc.</div>
                        <div class="invalid-feedback">Missing credentials.</div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" name="submit-full-form" class="btn btn-primary">Create worker</button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Add new worker to the database from the form information. -->
        <?php
        include 'connectdb.php';
        include 'statementUtils.php';

        // Check if form was submitted
        if (isset($_POST["submit-full-form"])) {
            // Get all form information
            $id = $_POST["id"];
            $firstName = $_POST["firstName"];
            // Check if middle name is set
            if (isset($_POST["middleName"])) {
                $middleName = $_POST["middleName"];
            } else {
                $middleName = "";
            }
            $lastName = $_POST["lastName"];
            $credentials = $_POST["credentials"];
            $workerType = $_POST["workerRadios"];
            $vaccinationSite = $_POST["vaccinationSite"];

            // If any of the fields equal "", display error message
            if ($id == "" || $firstName == "" || $lastName == "" || $credentials == "" || $workerType == "" || $vaccinationSite == "") {
                echo '<div class="alert alert-danger">Please fill in every field.</div>';
            } else {
                // Check if doctor was selected
                if ($workerType == "Doctor") {
                    $practiceName = $_POST["medicalPractice"];

                    // Create doctor

                    // add new doctor to the database
                    $result = executeStatement($stmt);

                    if ($result) {
                        $message = "<div class='alert alert-success'>The doctor was successfully added!</div><br>";
                    } else {
                        // Else, display error message.
                        $message = '<div class="alert alert-danger">An error occured adding the doctor to the database. Please try again.</div>';
                    }

                    echo $message;

                    // Set credentials table to DoctorCredentials
                    $credentialsTable = "DoctorCredentials";
                }
                // Else check if nurse was selected
                else if ($workerType == "Nurse") {
                    // Create nurse

                    // add new doctor to the database
                    $result = executeStatement($stmt);

                    if ($result) {
                        $message = "<div class='alert alert-success'>The doctor was successfully added!</div><br>";
                    } else {
                        // Else, display error message.
                        $message = '<div class="alert alert-danger">An error occured adding the doctor to the database. Please try again.</div>';
                    }

                    echo $message;
                    // Set credentials table to NurseCredentials
                    $credentialsTable = "NurseCredentials";
                }

                // Add credentials to $credentialsTable table 
                // Iterate over list to add each one as (ID, Credential)
            }
        }
        ?>

        <!-- Optional JavaScript -->
        <script type="text/javascript">
            function showDoctorFields() {
                if (document.getElementById('doctorRadio').checked) {
                    document.getElementById('doctorPractice').style.display = 'block';
                } else {
                    document.getElementById('doctorPractice').style.display = 'none';
                }

            }

            (function() {
                'use strict'

                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.querySelectorAll('.needs-validation')

                // Loop over them and prevent submission
                Array.prototype.slice.call(forms)
                    .forEach(function(form) {
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