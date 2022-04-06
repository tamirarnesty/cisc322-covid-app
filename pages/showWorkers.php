<!DOCTYPE html>
<html lang="en">

<head>
    <title>Vaccination Site</title>
</head>

<body>
    <!-- Display the Nurses and Doctors (workers) at the selected site -->
    <?php
    include 'connectdb.php';
    $siteName = $_POST["siteName"];
    echo "<h1>Health Care Workers at ".$siteName."</h1>";

    function getWorkers($query, $connection, $siteName, $type) {
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':siteName', $siteName);
        $result = $stmt->execute();
        $data = $stmt->fetchAll();
        
        // Check if the query returned any results and if not, display a message
        echo "<h2>" . ucfirst($type) . ":</h2>";
        if (empty($data)) {
            echo "No " . $type . " at this site.<br>";
        } else {
            foreach ($data as $row) :
                echo "<li>" . $row["FirstName"] . " " . $row["MiddleName"] . " " . $row["LastName"] . "</li>";
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
</body>
</html>
