<!DOCTYPE html>
<html lang="en">

<head>
    <title>Vaccination Site</title>
</head>

<body>
    <h1>View workers at a Vaccination Site</h1>

    <!-- Load VaccinationSite names for the dropdown -->
    <?php
    include 'connectdb.php';

    $result = $connection->query("SELECT Name FROM VaccinationSite");
    $data = $result->fetchAll();
    ?>

    <!-- Present the dropdown -->
    <form action="showWorkers.php" method="post">
        <form>
            <label for="siteName">Choose a Vaccination Site:</label>
            <select name="siteName" id="siteName">
                <?php foreach ($data as $row) : ?>
                    <option><?= $row["Name"] ?></option>
                <?php endforeach ?>
            </select>
            <input type="submit">
        </form>
</body>

</html>
