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
            <input type="hidden" name="OHIPNumber" value="<?php echo $_POST["OHIPNumber"]; ?>">
            <div class="form-fields">
                <!-- Name -->
                <div class="form-group row">
                    <label for="fullName" class="col-sm-2 col-form-label">Full Name</label>
                    <div class="col">
                        <input required type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" id="middleName" name="middleName" placeholder="Middle Name">
                    </div>
                    <div class="col">
                        <input required type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name">
                    </div>
                </div>

                <!-- OHIP Number -->
                <div class="form-group row">
                    <label for="OHIPNumber" class="col-sm-2 col-form-label">OHIP Number</label>
                    <div class="col">
                        <input required type="text" class="form-control" id="OHIPNumber" name="OHIPNumber" value="<?php echo $_POST["OHIPNumber"]; ?>">
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
                        <button type="submit" name="submit-full-form" class="btn btn-primary">Create Patient</button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Add new patient to database from form information. -->
        <?php
        include 'connectdb.php';
        include 'statementUtils.php';

        // Check if form was submitted
        if (isset($_POST["submit-full-form"])) {

            // Get all form information
            $firstName = $_POST["firstName"];
            $middleName = $_POST["middleName"];
            $lastName = $_POST["lastName"];
            $OHIPNumber = $_POST["OHIPNumber"];
            $dateOfBirth = $_POST["dateOfBirth"];

            // If all fields are set, add new patient to database.

            // If adding succeeds, display success message.
            try {
                $query = "insert into Patient value (:OHIPNumber, :dateOfBirth, :firstName, :middleName, :lastName)";
                $stmt = $connection->prepare($query);
                $stmt->bindParam(':OHIPNumber', $OHIPNumber);
                $stmt->bindParam(':dateOfBirth', $dateOfBirth);
                $stmt->bindParam(':firstName', $firstName);
                $stmt->bindParam(':middleName', $middleName);
                $stmt->bindParam(':lastName', $lastName);
                $result = $stmt->execute();
                if ($result) {
                    echo
                    "<div class='alert alert-success'>The patient was successfully added!</div>
            <div class='container-fluid'><a href='addVaccinationRecord.php?OHIPNumber=$OHIPNumber' class='btn btn-primary'>Add Vaccination Record</a></div>";
                } else {
                    '<div class="alert alert-danger">An error occured adding the patient. Please try again.</div>';
                }
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    echo
                    '<div class="alert alert-danger">A patient with OHIP number ' . $OHIPNumber . ' already exists.</div>';
                } else {
                    echo
                    '<div class="alert alert-danger">An error occured adding the patient. Please try again.</div>';
                }
            }
        }
        ?>
    </div>
</body>

<?php include('buttonFooter.html'); ?>

</html>