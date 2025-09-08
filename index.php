<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Relay Control</title>
  <link href="./src/output.css" rel="stylesheet">
  <style>
    /* Toggle animation */
    .toggle-bg {
      transition: background-color 0.3s ease;
    }
    .dot {
      transition: transform 0.3s ease;
    }
    input:checked ~ .dot {
      transform: translateX(100%);
      background-color: #10b981;
    }
    input:checked ~ .toggle-bg {
      background-color: #a7f3d0;
    }
  </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center space-y-10 p-6 gap-6">

  <!-- Relay Controls Card -->
  <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-sm text-center space-y-10">
    <h2 class="text-2xl font-bold text-gray-700">Relay Controls</h2>

    <!-- Fan Relay -->
    <div class="space-y-2">
      <p class="text-lg font-medium text-gray-600">Fan</p>
      <label class="flex items-center cursor-pointer justify-center">
        <div class="relative">
          <input type="checkbox" id="fanToggle" class="sr-only" onchange="toggleRelay(0, this.checked)">
          <div class="block bg-gray-300 w-16 h-8 rounded-full toggle-bg"></div>
          <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full shadow-md"></div>
        </div>
      </label>
    </div>

    <!-- Compressor Relay -->
    <div class="space-y-2">
      <p class="text-lg font-medium text-gray-600">Compressor</p>
      <label class="flex items-center cursor-pointer justify-center">
        <div class="relative">
          <input type="checkbox" id="compressorToggle" class="sr-only" onchange="toggleRelay(1, this.checked)">
          <div class="block bg-gray-300 w-16 h-8 rounded-full toggle-bg"></div>
          <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full shadow-md"></div>
        </div>
      </label>
    </div>

    <!-- SSR Relay -->
    <div class="space-y-2">
      <p class="text-lg font-medium text-gray-600">SSR</p>
      <label class="flex items-center cursor-pointer justify-center">
        <div class="relative">
          <input type="checkbox" id="ssrToggle" class="sr-only" onchange="toggleRelay(2, this.checked)">
          <div class="block bg-gray-300 w-16 h-8 rounded-full toggle-bg"></div>
          <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full shadow-md"></div>
        </div>
      </label>
    </div>
  </div>

  <!-- Sensor Data Card -->
  <div class="bg-white p-6 rounded-2xl shadow-xl w-full max-w-4xl text-center">
    <h2 class="text-2xl font-bold text-gray-700 mb-4">Live Sensor Data</h2>
    <div id="sensor-table" class="overflow-x-auto flex justify-center">
      <p class="text-gray-500">Loading sensor data...</p>
    </div>
  </div>
  <script>
    const ESP32_IP = "http://192.168.0.111"; // Update if needed

    // Relay toggle
    function toggleRelay(pin, isChecked) {
      const state = isChecked ? 1 : 0;
      fetch(`${ESP32_IP}/control?pin=${pin}&state=${state}`)
        .then(response => response.text())
        .then(data => console.log("Server response:", data))
        .catch(error => console.error("Error sending request:", error));
    }

    // Live sensor data refresh
    async function loadData() {
      try {
        const response = await fetch("get_data.php");
        const data = await response.text();
        document.getElementById("sensor-table").innerHTML = data;
      } catch (error) {
        document.getElementById("sensor-table").innerHTML = 
          "<p class='text-red-500'>Error loading data.</p>";
      }
    }

    // Initial load + auto-refresh every 3 seconds
    loadData();
    setInterval(loadData, 3000);
  </script>
</body>
</html>
