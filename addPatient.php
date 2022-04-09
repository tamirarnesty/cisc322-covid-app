<!DOCTYPE html>
<html lang="en">

<?php include('header.html'); ?>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Add Patient</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="main.css">
</head>

<body>
    <div class="main">
        <h1>Add New Patient</h1>

        <p>Enter the patient's information below.</p>
        <form class="form-horizontal" role="form" action="addPatient.php" method="post">
            <div class="form-fields">
                <!-- Name -->
                <div class="form-group row">
                    <label for="fullName" class="col-sm-2 col-form-label">Full Name</label>
                    <div class="col">
                        <input required type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name">
                    </div>
                    <div class="col">
                        <input required type="text" class="form-control" id="middleName" name="middleName" placeholder="Middle Name">
                    </div>
                    <div class="col">
                        <input required type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name">
                    </div>
                </div>

                <!-- OHIP Number -->
                <div class="form-group row">
                    <label for="OHIPNumber" class="col-sm-2 col-form-label">OHIP Number</label>
                    <div class="col">
                        <input required type="text" class="form-control" id="OHIPNumber" name="OHIPNumber" placeholder="OHIP Number">
                    </div>
                </div>

                <!-- Date of birth -->
                <div class="form-group row">
                    <label for="dateOfBirth" class="col-sm-2 col-form-label">Date of Birth</label>
                    <div class="col">
                        <input required type="date" class="form-control" id="dateOfBirth" name="dateOfBirth">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" name="submit" class="btn btn-primary">Create Patient</button>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <?php echo $result; ?>
                    </div>
                </div>
            </div>
        </form>

        <!-- Add new patient to database from form information. -->
        <?php
        include 'connectdb.php';

        function addPatient($connection, $firstName, $middleName, $lastName, $OHIPNumber, $dateOfBirth)
        {
            $query = "insert into Patient value (:OHIPNumber, :dateOfBirth, :firstName, :middleName, :lastName)";
            $stmt = $connection->prepare($query);
            $stmt->bindParam(':OHIPNumber', $OHIPNumber);
            $stmt->bindParam(':dateOfBirth', $dateOfBirth);
            $stmt->bindParam(':firstName', $firstName);
            $stmt->bindParam(':middleName', $middleName);
            $stmt->bindParam(':lastName', $lastName);
            return $stmt->execute();
        }

        // Check if form was submitted
        if (!isset($_POST['submit'])) {
            return;
        }

        // Get all form information
        $firstName = $_POST["firstName"];
        $middleName = $_POST["middleName"];
        $lastName = $_POST["lastName"];
        $OHIPNumber = $_POST["OHIPNumber"];
        $dateOfBirth = $_POST["dateOfBirth"];

        // If all fields are set, add new patient to database.

        // If adding succeeds, display success message.
        $result = addPatient($connection, $firstName, $middleName, $lastName, $OHIPNumber, $dateOfBirth);
        if ($result) {
            $result = "<div class='alert alert-success'>The patient was successfully added!</div><br>
            <div class='container-fluid'><a href='addVaccinationRecord.php' class='btn btn-primary'>Add Vaccination Record</a></div>";
        } else {
            // Else, display error message.
            $result = '<div class="alert alert-danger">An error occured creating the new patient. Please try again.</div>';
        }
        echo $result;
        ?>
    </div>
</body>

<?php include('buttonFooter.html'); ?>

</html>