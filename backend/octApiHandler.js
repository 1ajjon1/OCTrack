require("dotenv").config();

// endpoint + key
const url = "https://nextrip-public-api.azure-api.net/octranspo/gtfs-rt-vp/beta/v1/VehiclePositions";
const apiKey = process.env.API_KEY;
const authHeader = {"Ocp-Apim-Subscription-Key":apiKey};

// data fetch request message
function httpRequest() {
    var reqObj = new XMLHttpRequest();
    reqObj.onreadystatechange = function() {
        if (reqObj.readyState == 4 && reqObj.status == 200) {
            // response
        }
    }
    reqObj.setRequestHeader("Ocp-Apim-Subscription-Key", "Bearer " + apiKey);
    reqObj.open("GET", url, true);
    // reqObj.send();
}

// function for data parsing here

// function for json conversion here