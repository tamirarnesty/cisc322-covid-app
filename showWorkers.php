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

        <!-- Display the Nurses and Doctors (workers) at the selected site -->
        <?php
        include 'connectdb.php';
        $siteName = $_POST["siteName"];
        echo "<h1>Health Care Workers at " . $siteName . "</h1>";
        
        function getWorkers($query, $connection, $siteName, $type)
        {
            $stmt = $connection->prepare($query);
            $stmt->bindParam(':siteName', $siteName);
            $result = $stmt->execute();
            $data = $stmt->fetchAll();

            // Check if the query returned any results and if not, display a message
            echo "<h3>" . ucfirst($type) . ":</h3>";
            if (empty($data)) {
                echo "No " . $type . " at this site.<br>";
            } else {
                echo '<ul class="list-group">';
                foreach ($data as $row) :
                    echo "<li class='list-group-item'>" . $row["FirstName"] . " " . $row["MiddleName"] . " " . $row["LastName"] . "</li>";
                endforeach;
            }
        }

        if (isset($siteName)) {

            // Get the list of nurses
            $nurseQuery = "select n.FirstName, n.MiddleName, n.LastName from Nurse as n, NurseWorksAt as nw where nw.SiteName=:siteName and nw.NurseID=n.ID";
            getWorkers($nurseQuery, $connection, $siteName, "nurses");

            echo "<br>";

            // Get the list of doctors
            $doctorQuery = "select d.FirstName, d.MiddleName, d.LastName from Doctor as d, DoctorWorksAt as dw where dw.SiteName=:siteName and dw.DoctorID=d.ID";
            getWorkers($doctorQuery, $connection, $siteName, "doctors");
        }
        ?>
    </div>
</body>

<?php include('buttonFooter.html'); ?>

</html>