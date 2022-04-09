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
        ?>

        <!-- Get vaccination site if selected -->
        <?php $selectedSite = $_POST["vaccinationSite"]; ?>

        <!-- Get health care worker option if selected -->
        <?php $selectedWorker = $_POST["workerType"]; ?>

        <form class="form-horizontal" role="form" action="addWorkers.php" method="post">
            <div class="form-fields">
                <!-- Select health care worker type -->
                <div class="form-group row">
                    <label for="workerType" class="col-sm-2 col-form-label">Worker Type</label>
                    <div class="col">
                        <select required class="custom-select" name="workerType" id="workerType">
                            <!-- Add initial option with value -- Select Lot Number -- -->
                            <option value>--Select Worker Type--</option>
                            <?php
                            // Create array of health care worker types: Nurse, Doctor
                            $workerTypes = array("Nurse", "Doctor");

                            foreach ($workerTypes as $row) :
                                $selected = $row === $selectedWorker ? "selected" : "";
                            ?>
                                <option<?= $selected ?>> <?= htmlspecialchars($row) ?></option>
                                <?php endforeach ?>
                        </select>
                        <div class="invalid-feedback">Missing health care worker type.</div>
                    </div>
                </div>

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

                <!-- Submit Button -->
                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" name="submit-full-form" class="btn btn-primary">Create worker</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>

<?php include('buttonFooter.html'); ?>

</html>