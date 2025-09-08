<?php

$servername = "localhost";
$dbname = "gas_sensor";
$username = "root";
$password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve POST data
    $node_name  = $_POST["node_name"];
    $mq7        = $_POST["mq7"];
    $co2        = $_POST["co2"];
    $o2         = $_POST["o2"];
    $fan        = $_POST["fan"];        // relay 1
    $compressor = $_POST["compressor"]; // relay 2
    $ssr        = $_POST["ssr"];        // relay 3

    // Establish a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert into the `sensor` table
    // Make sure your table has matching columns: node_name, mq7, co2, o2, fan, compressor, ssr
    $sql = "INSERT INTO sensor (node_name, mq7, co2, o2, fan, compressor, ssr)
            VALUES ('$node_name', '$mq7', '$co2', '$o2', '$fan', '$compressor', '$ssr')";

    if ($conn->query($sql) === TRUE) {
        echo "Data inserted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the connection
    $conn->close();
} else {
    echo "No data posted with HTTP POST.";
}

?>
