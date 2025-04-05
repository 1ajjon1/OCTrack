require("dotenv").config();

const express = require("express");
const fetch = require("node-fetch");
const gtfs = require("gtfs-realtime-bindings");

const app = express();
app.use(express.json());

const PORT = process.env.PORT || 3002;
const API_URL = "https://nextrip-public-api.azure-api.net/octranspo/gtfs-rt-tp/beta/v1/TripUpdates";
const API_KEY = process.env.OCTRANSPO_API_KEY;

// Endpoint to get trip updates for a specific route
app.get("/trip-updates/:routeId", async (req, res) => {
    try {
        const { routeId } = req.params;

        const response = await fetch(API_URL, {
            headers: { "Ocp-Apim-Subscription-Key": API_KEY }
        });
        
        if (!response.ok) {
            return res.status(response.status).json({ error: "Failed to fetch trip updates" });
        }

        const buffer = await response.arrayBuffer();
        const feed = gtfs.transit_realtime.FeedMessage.decode(new Uint8Array(buffer));
        
        // Filter trip updates for the specified route ID
        console.log(feed.entity);
        const filteredTrips = feed.entity.filter(entity => 
            entity.tripUpdate && entity.tripUpdate.trip && entity.tripUpdate.trip.route_id === routeId
        );

        res.json(filteredTrips);
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

app.listen(PORT, () => console.log(`Server running on port ${PORT}`));