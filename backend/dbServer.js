require("dotenv").config();

const appID = process.env.OCTRANSPO_APP_ID;
const apiKey = process.env.OCTRANSPO_API_KEY;
const baseUrl = process.env.URL;

const express = require("express")
const fetch = require("node-fetch");
const app = express()
const session = require('express-session');
app.use(express.json())

app.use((req, res, next) => {
    res.header('Access-Control-Allow-Origin', '*');
    res.header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
    next();
});

app.use(session({
    secret: '123', // Change this to something more secure
    resave: false,
    saveUninitialized: true, // Save uninitialized sessions
    cookie: {
        httpOnly: true,   // Helps prevent client-side JS from accessing session cookie
        maxAge: 3600000   // Set a maxAge for the cookie in milliseconds (1 hour in this example)
    }
}));
app.use(express.urlencoded({ extended: true })); // For form data

app.get("/api/stop", async (req, res) => {
    try {
        const { stopNo } = req.query;
        if (!stopNo) {
            return res.status(400).json({ error: "Missing stopNo query parameter" });
        }

        // Get route summary for the stop
        const routeSummaryUrl = `${baseUrl}/GetRouteSummaryForStop?appID=${appID}&apiKey=${apiKey}&stopNo=${stopNo}&format=json`;
        const routeSummaryResponse = await fetch(routeSummaryUrl);
        if (!routeSummaryResponse.ok) {
            throw new Error(`Error fetching route summary: ${routeSummaryResponse.status}`);
        }
        const routeSummaryData = await routeSummaryResponse.json();

        if (!routeSummaryData.GetRouteSummaryForStopResult || !routeSummaryData.GetRouteSummaryForStopResult.Routes) {
            return res.status(404).json({ error: "Stop not found or no routes available" });
        }
        
        const stopDescription = routeSummaryData.GetRouteSummaryForStopResult.StopDescription;
        const routes = routeSummaryData.GetRouteSummaryForStopResult.Routes.Route;

        // Get next trips for each route
        const buses = await Promise.all(
            routes.map(async (route) => {
                try {
                    const nextTripsUrl = `${baseUrl}/GetNextTripsForStop?appID=${appID}&apiKey=${apiKey}&stopNo=${stopNo}&routeNo=${route.RouteNo}&format=json`;
                    const nextTripsResponse = await fetch(nextTripsUrl);
        
                    if (!nextTripsResponse.ok) {
                        console.error(`Error fetching next trips for route ${route.RouteNo}: ${nextTripsResponse.status}`);
                        return null;
                    }
        
                    const nextTripsData = await nextTripsResponse.json();
                    const routeData = nextTripsData?.GetNextTripsForStopResult?.Route?.RouteDirection;
        
                    if (!routeData || !routeData.Trips || !routeData.Trips.Trip) {
                        return null; // Skip if no valid trip info
                    }
        
                    const rawTrips = routeData.Trips.Trip;
        
                    const trips = Array.isArray(rawTrips)
                        ? rawTrips.filter(trip => trip && trip.AdjustedScheduleTime !== undefined)
                        : [rawTrips].filter(trip => trip && trip.AdjustedScheduleTime !== undefined);
        
                    if (trips.length === 0) return null;
                    console.log(trips);
                    return {
                        routeNo: route.RouteNo,
                        routeHeading: route.RouteHeading,
                        nextTrips: trips
                    };
                } catch (err) {
                    console.error(`Error processing route ${route.RouteNo}:`, err);
                    return null;
                }
            })
        );
        
        res.json({
            stop_no: stopNo,
            stop_description: stopDescription,
            buses: buses.filter(bus => bus !== null)
        });
    } catch (error) {
        console.error("Error:", error);
        res.status(500).json({ error: "Internal server error" });
    }
});

// Start the server by adding this
const PORT = process.env.PORT || 3006; // Default to 3000 if no port is provided in .env file
app.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}`);
});
