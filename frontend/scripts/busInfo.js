async function getStopData(stopCode) {
    try {
        const response = await fetch(`http://localhost:3001/api/stop?stopCode=${stopCode}`);
        if (!response.ok) {
            throw new Error(`Error: ${response.status}`);
        }
        const data = await response.json();

        // Assigning API response values to variables
        const stopCodeValue = data.stop_code;
        const stopNameValue = data.stop_name;
        const lat = data.location.lat;
        const lon = data.location.lon

        console.log("Stop Code:", stopCodeValue);
        console.log("Stop Name:", stopNameValue);

        return { stopCodeValue, stopNameValue, lat, lon};
    } catch (error) {
        console.error("Failed to fetch stop data:", error);
    }
}