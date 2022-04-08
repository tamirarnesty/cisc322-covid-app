<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Vaccination Sites</title>
    <link rel="stylesheet" href="../css/main.css">
</head>

<body>
    <h1>View Vaccines at a Vaccination Site</h1>

    <!-- Load Name of Companies for the dropdown -->
    <?php include 'companyNames.php'; ?>

    <!-- Present the dropdown -->
    <form action="viewVaccines.php" method="post">
        <form>
            <label for="companyName">Choose a Company:</label>
            <select name="companyName" id="companyName">
                <!-- Add initial option with value -- Select Site -- -->
                <option value="0">--Select Company--</option>
                <?php foreach ($companies as $row) : ?>
                    <option><?= $row["Name"] ?></option>
                <?php endforeach ?>
            </select>
            <input type="submit">
        </form>
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

    if (isset($companyName)) {
        $data = getVaccines($connection, $companyName);
        echo "<h2>Vaccines shipped to " . $companyName . ":</h2>";
        if (empty($data)) {
            echo "No vaccines shipped by this company.<br>";
        } else {
            // Table for the list of vaccines at a site
            echo "<table border='1'>";
            echo "<tr><th>Vaccine</th><th>Doses</th></tr>";
            foreach ($data as $row) :
                echo "<tr><td>" . $row["SiteName"] . "</td><td>" . $row["VaccineDoses"] . "</td></tr>";
            endforeach;
        }
    }
    ?>
</body>

<?php include('components/footer.html'); ?>

</html>