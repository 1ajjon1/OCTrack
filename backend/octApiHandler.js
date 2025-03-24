require("dotenv").config();
import GtfsRealtimeBindings from "gtfs-realtime-bindings";
import { load, Message } from "protobufjs";

// endpoint + key
const url = "https://nextrip-public-api.azure-api.net/octranspo/gtfs-rt-vp/beta/v1/VehiclePositions";
const apiKey = process.env.API_KEY;

// data fetch request message
function httpRequest() {
    const reqObj = new XMLHttpRequest();
    reqObj.setRequestHeader("Ocp-Apim-Subscription-Key", apiKey);
    reqObj.open("GET", url, true);
    reqObj.responseType = "arraybuffer";
    reqObj.onload = (e) => {
        if (reqObj.readyState == 4 && reqObj.status == 200) {
        console.log(reqObj.responseText);
        } else {
        console.error(reqObj.statusText);
        }
    }
    reqObj.send();
    const buffer = reqObj.response;
    parseData(buffer);
}

function parseData(buffer) {
    
    var feed = GtfsRealtimeBindings.transit_realtime.FeedMessage.decode(
        new Uint8Array(buffer)
    );
    feed.entity.forEach((entity) => {
        if (entity.tripUpdate) {
            console.log(entity.tripUpdate);
        }
    });
    messagetoObj(feed);
}

function messagetoObj(feed) {
    var feed_json = Message.toObject(feed);
    console.log(feed_json);
    f = new File(feed_json, 'results.json');
}