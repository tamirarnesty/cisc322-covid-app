<!DOCTYPE html>
<html lang="en">

<head>
    <title>Patient Vaccinations</title>
</head>

<body>
    <h1>View patient vaccination details</h1>

    <!-- Load Patient names for the dropdown -->
    <?php
    include 'connectdb.php';

    $result = $connection->query("SELECT FirstName, MiddleName, LastName FROM Patient;");
    $data = $result->fetchAll();
    ?>

    <!-- Present the dropdown -->
    <form action="viewPatients.php" method="post">
        <form>
            <label for="OHIPNumber">Choose a Patient:</label>
            <select name="Name" id="Name">
                <!-- Add initial option with value -- Select Site -- -->
                <option value="0">--Select Patient--</option>
                <?php foreach ($data as $row) : ?>
                    <option><?= $row["FirstName"] . " " . $row["MiddleName"] . " " . $row["LastName"] ?></option>
                <?php endforeach ?>
            </select>
            <input type="submit">
        </form>

        <?php
        include 'connectdb.php';
        $name = explode(" ", $_POST["Name"]);
        if (count($name) == 2) {
            $firstName = $name[0];
            $middleName = "";
            $lastName = $name[1];
        } else {
            $firstName = $name[0];
            $middleName = $name[1];
            $lastName = $name[2];
        }

        function getOHIP($connection, $firstName, $middleName, $lastName)
        {
            $query = "SELECT OHIPNumber FROM Patient WHERE FirstName=:firstName AND MiddleName=:middleName AND LastName=:lastName";
            $stmt = $connection->prepare($query);
            $stmt->bindParam(':firstName', $firstName);
            $stmt->bindParam(':middleName', $middleName);
            $stmt->bindParam(':lastName', $lastName);
            $result = $stmt->execute();
            $data = $stmt->fetchAll();
            return $data[0]["OHIPNumber"];
        }

        // Get the OHIP number of the patient
        $OHIP = getOHIP($connection, $firstName, $middleName, $lastName);

        // Get the list of vaccinations for the patient
        if (isset($OHIP)) {
            $query = "SELECT * FROM Vaccination as v join Patient as p on p.OHIPNumber=v.OHIPNumber join Vaccine as c on v.LotNumber=c.LotNumber WHERE p.OHIPNumber=:OHIPNumber";
            $stmt = $connection->prepare($query);
            $stmt->bindParam(':OHIPNumber', $OHIP);
            $result = $stmt->execute();
            $data = $stmt->fetchAll();

            // Check if the query returned any results and if not, display a message
            if (empty($data)) {
                echo "No patient with OHIP number " . $OHIP . " exists.<br>";
            } else {
                // Table for the list of vaccinations
                echo "<h2>Vaccination details for: " . $firstName . " " . $middleName . " " . $lastName . "</h2>";

                echo "<table border='1'>";
                // OHIP, Patient Name, Vaccination Date, Vaccination Time, Company, Lot Number
                echo "<tr><th>OHIP Number</th><th>Patient Name</th><th>Vaccination Date</th><th>Vaccination Time</th><th>Company</th><th>Lot Number</th></tr>";
                foreach ($data as $row) :
                    echo "<tr><td>" . $row["OHIPNumber"] . "</td><td>" . $row["FirstName"] . " " . $row["MiddleName"] . " " . $row["LastName"] . "</td><td>" . $row["VaccinationDate"] . "</td><td>" . $row["VaccinationTime"] . "</td><td>" . $row["Company"] . "</td><td>" . $row["LotNumber"] . "</td></tr>";
                endforeach;
            }
        }
        ?>
</body>

<?php include('components/footer.html'); ?>

</html>