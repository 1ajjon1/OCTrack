require("dotenv").config();

const express = require("express");
const fetch = require("node-fetch");
const app = express();
app.use(express.json());

const appID = process.env.OCTRANSPO_APP_ID;
const apiKey = process.env.OCTRANSPO_API_KEY;
const baseUrl = "https://api.octranspo1.com/v2.0";

const cors = require("cors");

//Was running into a cors error when loading the login.html page, this fix from Stack seems to have fixed it
const corsOptions = {
  origin: ["http://localhost:5500", "http://127.0.0.1:5500"], // Allow only your frontend
  methods: "GET,POST,PUT,DELETE,OPTIONS", // Allow necessary methods
  allowedHeaders: "Origin, X-Requested-With, Content-Type, Accept",
  credentials: true // Allow cookies & authentication headers
};

// Use CORS Middleware
app.use(cors());

app.get("/api/stop", async (req, res) => {
    try {
        const { stopNo } = req.query;
        if (!stopNo) {
            return res.status(400).json({ error: "Missing stopNo query parameter" });
        }

        // Get route summary for the stop
        const routeSummaryUrl = `https://api.octranspo1.com/v2.0/GetRouteSummaryForStop?appID=a3070a29&apiKey=0c559b4056736cd027607d0ec3b65c58&stopNo=6703&format=json`;
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
        const buses = await Promise.all(routes.map(async (route) => {
            const nextTripsUrl = `${baseUrl}/GetNextTripsForStop?appID=${appID}&apiKey=${apiKey}&stopNo=${stopNo}&routeNo=${route.RouteNo}&format=json`;
            const nextTripsResponse = await fetch(nextTripsUrl);
            if (!nextTripsResponse.ok) {
                console.error(`Error fetching next trips for route ${route.RouteNo}: ${nextTripsResponse.status}`);
                return null;
            }
            const nextTripsData = await nextTripsResponse.json();
            console.log(nextTripsData.GetNextTripsForStopResult.Route.RouteDirection.Trips);
            return {
                routeNo: route.RouteNo,
                routeHeading: route.RouteHeading,
                nextTrips: Array.isArray(nextTripsData.GetNextTripsForStopResult.Route.RouteDirection.Trips)
                    ? nextTripsData.GetNextTripsForStopResult.Route.RouteDirection.Trips
                    : [nextTripsData.GetNextTripsForStopResult.Route.RouteDirection.Trips].filter(Boolean)
            };
        }));

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

const PORT = 3001;
app.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
});
