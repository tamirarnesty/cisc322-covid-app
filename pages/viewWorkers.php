<!DOCTYPE html>
<html lang="en">

<head>
    <title>Vaccination Site</title>
</head>

<body>
    <h1>View workers at a Vaccination Site</h1>

    <!-- Load VaccinationSite names for the dropdown -->
    <?php include 'vaccinationSites.php'; ?>

    <!-- Present the dropdown -->
    <form action="showWorkers.php" method="post">
        <form>
            <label for="siteName">Choose a Vaccination Site:</label>
            <select name="siteName" id="siteName">
                <!-- Add initial option with value -- Select Site -- -->
                <option value="0">--Select Site--</option>
                <?php foreach ($vaccinationSites as $row) : ?>
                    <option><?= $row["Name"] ?></option>
                <?php endforeach ?>
            </select>
            <input type="submit">
        </form>
</body>

<?php include('components/footer.html'); ?>

</html>