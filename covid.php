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
        <div class="container-fluid">
            <div class="row align-items-center justify-content-start">
                <div class="col-md-auto">
                    <img src="images/globe.gif" width="200" alt="Rotating globe animation" />
                </div>
                <div class="col">
                    <h1>COVID-19 Vaccination Information Database</h1>
                    <p>Select one of the options below to perform different actions that this platform can do.
                        <br>
                        Be sure to have your, or your patient's, OHIP number handy for some of these options.
                    </p>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <br>
            <h3>Patient Actions</h3>
            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                <a href=" viewPatientVaccines.php" class="btn btn-primary">Add Vaccination</a>
                <a href="viewPatients.php" class="btn btn-primary">View Patients</a>
            </div>
        </div>

        <div class="container-fluid">
            <br>
            <h3>Health Care Worker Actions</h3>
            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                <a href="viewWorkers.php" class="btn btn-primary">View Health Care Workers</a>
                <a href="addWorkers.php" class="btn btn-primary">Add Health Care Workers</a>
            </div>
        </div>

        <div class="container-fluid">
            <br>
            <h3>Vaccination Actions</h3>
            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                <a href="viewVaccines.php" class="btn btn-primary">View Vaccinations by Company</a>
            </div>
        </div>

        </div>
</body>

<?php include('footer.html'); ?>

</html>