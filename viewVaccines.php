<!DOCTYPE html>
<html lang="en">

<?php include('header.html'); ?>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Vaccination Sites</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="main.css">
</head>

<body>
    <div class="main">
        <h1>View Vaccines at a Vaccination Site</h1>

        <!-- Load Name of Companies for the dropdown -->
        <?php include 'companyNames.php'; ?>

        <!-- Get patient name if selected -->
        <?php $selectedCompany = $_POST["companyName"]; ?>

        <!-- Present the dropdown -->
        <form name="vaccineRecordForm" class="form-horizontal" role="form" action="viewVaccines.php" method="post">
            <div class="form-fields">
                <div class="form-group row">
                    <label for="companyName" class="col-sm-2 col-form-label">Choose a Company:</label>
                    <div class="col">
                        <select required class="custom-select" name="companyName" id="companyName">
                            <!-- Add initial option with value -- Select Site -- -->
                            <option value>--Select Company--</option>
                            <?php
                            foreach ($companies as $row) :
                                $selected = $row["Name"] === $selectedCompany ? " selected" : "";
                            ?>
                                <option<?= $selected ?>> <?= htmlspecialchars($row["Name"]) ?></option>
                                <?php endforeach ?>
                        </select>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" name="submit-full-form" class="btn btn-primary">View vaccines</button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Display Vaccines from ShipTo table for selected Company -->
        <?php
        include 'connectdb.php';
        $companyName = $_POST["companyName"];

        function getVaccines($connection, $companyName)
        {
            $query = "select SiteName, sum(DoseCount) as VaccineDoses from Vaccine as v join ShipsTo as s on v.LotNumber=s.LotNumber where v.Company=:companyName group by s.SiteName order by s.SiteName";
            $stmt = $connection->prepare($query);
            $stmt->bindParam(':companyName', $companyName);
            $result = $stmt->execute();
            $data = $stmt->fetchAll();
            return $data;
        }

        if ($companyName != "") {
            $data = getVaccines($connection, $companyName);
            echo "<h3>Vaccines shipped to " . $companyName . ":</h3>";
            if (empty($data)) {
                echo "No vaccines shipped by this company.<br>";
            } else {
                // Table for the list of vaccines at a site
                echo "<table class='table'>";
                echo "<tr><th>Vaccine</th><th>Doses</th></tr>";
                foreach ($data as $row) :
                    echo "<tr><td>" . $row["SiteName"] . "</td><td>" . $row["VaccineDoses"] . "</td></tr>";
                endforeach;
            }
        }
        ?>
    </div>
</body>

<?php include('buttonFooter.html'); ?>

</html>