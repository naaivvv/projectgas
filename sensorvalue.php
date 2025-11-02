<?php

$servername = "localhost";
$dbname     = "isga";
$username   = "root";
$password   = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve POST data safely
    $node_name  = $_POST["node_name"] ?? '';
    $co        = $_POST["co"] ?? 0;
    $co2_ppm    = $_POST["co2"] ?? 0; // received in ppm
    $o2         = $_POST["o2"] ?? 0;
    $fan        = $_POST["fan"] ?? 0;        // relay 1
    $compressor = $_POST["compressor"] ?? 0; // relay 2

    // ✅ Convert CO2 from ppm → %
    $co2 = $co2_ppm / 10000;

    // Establish a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // ✅ Insert into the `sensor` table (ssr removed)
    $sql = "INSERT INTO sensor (node_name, co, co2, o2, fan, compressor)
            VALUES ('$node_name', '$co', '$co2', '$o2', '$fan', '$compressor')";

    if ($conn->query($sql) === TRUE) {
        echo "Data inserted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    echo "No data posted with HTTP POST.";
}

?>
