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
        <h1>Add New Patient</h1>

        <p>Enter the patient's information below.</p>
        <form class="form-horizontal" role="form" action="../pages/addPatient.php" method="post">
            <div class="form-fields">
                <!-- Name -->
                <div class="form-group row">
                    <label for="fullName" class="col-sm-2 col-form-label">Full Name</label>
                    <div class="col">
                        <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name">
                        <?php echo "<p class='text-danger'>$errFirstName</p>"; ?>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" id="middleName" name="middleName" placeholder="Middle Name">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name">
                        <?php echo "<p class='text-danger'>$errLastName</p>"; ?>
                    </div>
                </div>

                <!-- OHIP Number -->
                <div class="form-group row">
                    <label for="OHIPNumber" class="col-sm-2 col-form-label">OHIP Number</label>
                    <div class="col">
                        <input type="text" class="form-control" id="OHIPNumber" name="OHIPNumber" placeholder="OHIP Number">
                        <?php echo "<p class='text-danger'>$errOHIP</p>"; ?>
                    </div>
                </div>

                <!-- Date of birth -->
                <div class="form-group row">
                    <label for="dateOfBirth" class="col-sm-2 col-form-label">Date of Birth</label>
                    <div class="col">
                        <input type="date" class="form-control" id="dateOfBirth" name="dateOfBirth">
                        <?php echo "<p class='text-danger'>$errDOB</p>"; ?>
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

        // Check if each field is set, otherwise display error message
        if (!isset($firstName)) {
            $errFirstName = "Please enter a first name.";
            echo "not set";
        }

        // If all fields are set, add new patient to database.

        // If adding succeeds, display success message.
        $result = addPatient($connection, $firstName, $middleName, $lastName, $OHIPNumber, $dateOfBirth);
        if ($result) {
            $result = "<div class='alert alert-success'>The patient was successfully added!</div><br>
            <div class='container-fluid'><a href='../pages/addVaccinationRecord.php' class='btn btn-primary'>Add Vaccination Record</a></div>";
        } else {
            // Else, display error message.
            $result = '<div class="alert alert-danger">An error occured creating the new patient. Please try again.</div>';
        }
        echo $result;
        ?>
    </div>
</body>

<?php include('components/footer.html'); ?>

</html>