<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Locations</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" type="text/css" href="Stylesheet/Stylesheet_A3.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>
<body>
    <?php include "./ModularPHP/Header.php"; ?>

    <div id="searchContainer">
        <h3>Search for a Bus</h3>
        <p>Stop Number/Name:</p>
        <input id="stopIn" type="text" placeholder="Enter Stop ID">
        <p>Route Number:</p>
        <input id="routeIn" type="text" placeholder="Enter Route Number">
        <button id="searchBtn">Search</button>
    </div>

    <div id="busInfo">
    </div>

    <button id="favoriteBtn" style="display:none; top: 10px; right: 10px;">Add to Favorites</button>

    <?php include "./ModularPHP/Footer.php"; ?>

    <script>
          document.addEventListener("DOMContentLoaded", function () {
            // Get the URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const username = "<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>";
            const stopNoFromURL = urlParams.get('stopNo');
            
            if (stopNoFromURL) {
                document.getElementById('stopIn').value = stopNoFromURL;
            }

            if (!username) {
                document.getElementById('favoriteBtn').style.display = 'none';
            }
            let stopNumber = null;

            document.getElementById("searchBtn").addEventListener("click", async function () {
                const stopNo = document.getElementById("stopIn").value;
                const routeNo = document.getElementById("routeIn").value;
    
                if (!stopNo) {
                    alert("Please enter a stop number.");
                    return;
                }

                try {
                    const response = await fetch(`http://localhost:3001/api/stop?stopNo=${stopNo}`);
                    const data = await response.json();
                    const busInfo = document.getElementById("busInfo");
                    busInfo.innerHTML = "";

                    stopNumber = stopNo;  // Store the stop number for future use

                    // Show the favorite button if stop info exists
                    document.getElementById('favoriteBtn').style.display = 'block';

                    if (!data.buses || data.buses.length === 0) {
                        const noCard = document.createElement("div");
                        noCard.className = "bus-card";

                        const noRoute = document.createElement("div");
                        noRoute.className = "bus-route";
                        noRoute.textContent = "No buses available";

                        const noDetails = document.createElement("div");
                        noDetails.className = "bus-destination";
                        noDetails.textContent = `There are currently no buses scheduled at this stop.`;

                        noCard.appendChild(noRoute);
                        noCard.appendChild(noDetails);
                        busInfo.appendChild(noCard);
                        return;
                    }


                    data.buses.forEach(bus => {
                        // Filter by route number if one was entered
                        if (routeNo && bus.routeNo !== routeNo) return;

                        const direction = bus.routeHeading.split('-')[0].trim();
                        const destination = bus.routeHeading.split('-').slice(1).join('-').trim();
                        const stopDesc = data.stop_description.Description || "Current stop";

                        if (!bus.nextTrips || bus.nextTrips.length === 0) return;
                        bus.nextTrips.forEach(tripObj => {
                            const tripsRaw = tripObj;
                            if (!tripsRaw) return;
                            const trips = Array.isArray(tripsRaw) ? tripsRaw : [tripsRaw];

                            trips.forEach(trip => {
                                if (!trip) return;
                                const card = document.createElement("div");
                                card.className = "bus-card";

                                const route = document.createElement("div");
                                route.className = "bus-route";
                                route.textContent = `${bus.routeNo} ${direction}`;

                                const dest = document.createElement("div");
                                dest.className = "bus-destination";
                                dest.textContent = destination;

                                const time = document.createElement("div");
                                time.className = "bus-time";
                                time.textContent = trip.AdjustedScheduleTime
                                    ? `Next: ${trip.AdjustedScheduleTime} min`
                                    : `No upcoming time`;

                                const stop = document.createElement("div");
                                stop.className = "bus-stop";
                                stop.textContent = stopDesc;

                                card.appendChild(route);
                                card.appendChild(dest);
                                card.appendChild(time);
                                card.appendChild(stop);
                                busInfo.appendChild(card);
                            });
                        });
                    });

                } catch (error) {
                    console.error("Error fetching stop data:", error);
                    alert("Failed to fetch bus data. Please try again.");
                }
            });
            //This is the favorite feature
            document.getElementById("favoriteBtn").addEventListener("click", async function () {
                const stopNo = stopNumber;
                
                //If no stop number is specified, throw an error
                if (!stopNo) {
                    alert("Please select a bus stop first.");
                    return;
                }

                try {
                    const response = await fetch("favoriteStop.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            stop_number: stopNo
                        })
                    });

                    const data = await response.json();
                    console.log(data);

                    if (data.status === "error") {
                        alert(data.message);  // Show the error message (e.g., "Stop already in favorites")
                    } else if (data.status === "success") {
                        alert(data.message);  // Show the success message (e.g., "Stop added to favorites")
                    }

                } catch (error) {
                    console.error("Error adding favorite:", error);
                    alert("Failed to add to favorites. Please try again.");
                }
            });
        });
    </script>
    
    
</body>
</html>