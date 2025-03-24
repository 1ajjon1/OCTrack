require("dotenv").config();
import GtfsRealtimeBindings from "gtfs-realtime-bindings";

// endpoint + key
const url = "https://nextrip-public-api.azure-api.net/octranspo/gtfs-rt-vp/beta/v1/VehiclePositions";
const apiKey = process.env.API_KEY;

// data fetch request message
function httpRequest() {
    const reqObj = new XMLHttpRequest();
    reqObj.setRequestHeader("Ocp-Apim-Subscription-Key", apiKey);
    reqObj.open("GET", url, true);
    reqObj.responseType = "arraybuffer";
    if (reqObj.readyState == 4 && reqObj.status == 200) {
        console.log(reqObj.responseText);
    } else {
        console.error(reqObj.statusText);
    }
    // reqObj.send();
}

// function for data parsing here

// function for json conversion here