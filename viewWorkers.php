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
        <h1>Health Care Workers</h1>

        <!-- Load VaccinationSite names for the dropdown -->
        <?php include 'vaccinationSites.php'; ?>

        <!-- Get vaccination site if selected -->
        <?php $selectedSite = $_POST["siteName"]; ?>

        <!-- Present the dropdown -->
        <form action="showWorkers.php" method="post">
            <div class="form-fields">
                <div class="form-group row">
                    <label for="siteName" class="col-sm-2 col-form-label">Choose a Vaccination Site:</label>
                    <div class="col">
                        <select required class="custom-select" name="siteName" id="siteName">
                            <!-- Add initial option with value -- Select Site -- -->
                            <option value>--Select Site--</option>
                            <?php
                            foreach ($vaccinationSites as $row) :
                                $selected = $row["Name"] === $selectedSite ? " selected" : "";
                            ?>
                                <option<?= $selected ?>> <?= htmlspecialchars($row["Name"]) ?></option>
                                <?php endforeach ?>
                        </select>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" name="submit-full-form" class="btn btn-primary">View workers</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>

<?php include('buttonFooter.html'); ?>

</html>