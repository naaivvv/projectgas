<?php
$servername = "localhost";
$dbname = "gas_sensor";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("<p class='text-red-500'>Connection failed: " . $conn->connect_error . "</p>");
}

// Get latest 10 rows
$sql = "SELECT * FROM sensor ORDER BY id DESC LIMIT 10";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div class='overflow-x-auto'>
            <table class='min-w-full border border-gray-200 rounded-lg overflow-hidden'>
              <thead class='bg-gray-100'>
                <tr>
                  <th class='px-4 py-2 text-left text-sm font-semibold text-gray-600'>ID</th>
                  <th class='px-4 py-2 text-left text-sm font-semibold text-gray-600'>Node</th>
                  <th class='px-4 py-2 text-left text-sm font-semibold text-gray-600'>CO</th>
                  <th class='px-4 py-2 text-left text-sm font-semibold text-gray-600'>CO2</th>
                  <th class='px-4 py-2 text-left text-sm font-semibold text-gray-600'>O2</th>
                  <th class='px-4 py-2 text-left text-sm font-semibold text-gray-600'>Fan</th>
                  <th class='px-4 py-2 text-left text-sm font-semibold text-gray-600'>Compressor</th>
                  <th class='px-4 py-2 text-left text-sm font-semibold text-gray-600'>SSR</th>
                  <th class='px-4 py-2 text-left text-sm font-semibold text-gray-600'>Timestamp</th>
                </tr>
              </thead>
              <tbody class='divide-y divide-gray-200'>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr class='hover:bg-gray-50'>
                <td class='px-4 py-2 text-sm text-gray-700'>{$row['id']}</td>
                <td class='px-4 py-2 text-sm text-gray-700'>{$row['node_name']}</td>
                <td class='px-4 py-2 text-sm text-gray-700'>{$row['mq7']} ppm</td>
                <td class='px-4 py-2 text-sm text-gray-700'>{$row['co2']} ppm</td>
                <td class='px-4 py-2 text-sm text-gray-700'>{$row['o2']} %</td>
                <td class='px-4 py-2 text-sm text-gray-700'>{$row['fan']}</td>
                <td class='px-4 py-2 text-sm text-gray-700'>{$row['compressor']}</td>
                <td class='px-4 py-2 text-sm text-gray-700'>{$row['ssr']}</td>
                <td class='px-4 py-2 text-sm text-gray-700'>{$row['created_at']}</td>
              </tr>";
    }

    echo "  </tbody>
          </table>
        </div>";
} else {
    echo "<p class='text-gray-500'>No data found.</p>";
}

$conn->close();
?>
