<!DOCTYPE html>
<html lang="en">

<?php include('components/header.html'); ?>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>COVID-19 Vaccination Site</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body>
    <div class="main">

        <h1>Get Your COVID</h1>
        <p>Hello there! Thank you for visiting!</p>

        <!-- 4 buttons to go to pages -->
        <div class="container-fluid">
            <a href="pages/viewPatientVaccines.php" class="btn btn-primary">Add Vaccination</a>
            <a href="pages/viewPatients.php" class="btn btn-primary">View Patients</a>
            <a href="pages/viewWorkers.php" class="btn btn-primary">View Health Care Workers</a>
            <a href="pages/viewVaccines.php" class="btn btn-primary">View Vaccinations by Company</a>
        </div>
    </div>
</body>

<?php include('components/footer.html'); ?>

</html>