<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="authors" content="Saravanan Rajeswaran; DJ Watson; Taleiven Goundan; Justin Le">
    <script src="../backend/dbServer.js"></script>
    <link rel="stylesheet" type="text/css" href="Stylesheet/Stylesheet_A2.css" >
    <link rel="preconnect" href="https://rsms.me/">
    <link rel="stylesheet" media="screen and (max-width: 50vw)" href="https://rsms.me/inter/inter.css">
    <title>Favourites</title>

    <style>
        @font-face {
            font-family: Inter;
            src: url(fonts/Inter/Inter-Italic-VariableFont_opsz\,wght.ttf);
        }
       @font-face {
            font-family: Inter;
            src: url(fonts/Inter/static/Inter_18pt-Light.ttf);
        }
        @font-face {
            font-family: Inter;
            src: url(fonts/Inter/static/Inter_24pt-Light.ttf);
        }
        @font-face {
            font-family: Inter;
            src: url(fonts/Inter/static/Inter_28pt-Light.ttf);
        }
    </style>
</head>
<body>
   
       
    <?php include "./ModularPHP/Header.php"; ?>         <!-- Header -->
    <?php include "./ModularPHP/Card.php"; ?>           <!-- Login card on the side of the page -->




   <!-- Layout of the page -->
    <div class="generic-flex-container" id="body">

        <!-- Left side of the page contains the route table and the travel planner element  -->
        <div class="left-side">
            <div class="routes-container">
                <h2>Favourite Routes</h2>
                <div>
                    <h3>Example 1</h3>
                    <h3>Fallowfield Station</h3>
                    <p>8:15 - 271 Tunney's Pasture &lpar;9 min&rpar;</p>
                    <h3>Baseline Station</h3>
                    <p> 8:40 - 88 Hurdanman &lpar;18 min&rpar;</p>
                    <h3>Baseline &#47;Prince of Whales </h3>
                    <p> 8:59 - Walk &lpar;18 min&rpar;</p>

                    <b>Destination - 9:34 - Canada Agriculture and Food Museum<br></b>
                    <b>TOTAL: 52 min &lpar;8:26-9:18&rpar;</b>
                </div>
                <div>
                    <h3>Example 2</h3>
                    <h3>Fallowfield Station</h3>
                    <p>8:30 - 278 Tunney's Pasture &lpar;27 min&rpar;</p>
                    <h3>Tunney's Pasture</h3>
                    <p> 9:01 - Line 1 EAST &lpar;5 min&rpar;</p>
                    <h3>PIMISI B</h3>
                    <p>9:09 - 85 Bayshore &lpar;8 min&rpar;</p>
                    <b>Destination - 9:34 - Canada Agriculture and Food Museum<br></b>
                    <b>TOTAL: 1h 5min &lpar;8:29-9:34&rpar;</b>
                </div>
            </div>
            <div class="travel-planner element">
                <h2>Travel Planner</h2>
                <p>Origin: <input></p>
                <p>Destination: <input></p>
                <button>Change routes</button>
            </div>
        </div>

        <!-- The right side of the page contains an image -->
        <div class="right-side">
            <img src="images/IMG_4782.jpeg" class="background-image" style="z-index: -10;">
        </div>
    </div>



    <?php include "./ModularPHP/IconRedirects.php"; ?>           <!-- If the icon is clicked in the bottom right corner of the page, it will redirect users to OC Transpo social media profile-->
    <?php include "./ModularPHP/Footer.php"; ?>

    <script>
        // If the user hovers over a route in the route table, the route is highlighted in green. 
        const route = document.querySelectorAll(".routes-container div");
    
        route.forEach((cell) => {
            cell.addEventListener("mouseover", function (){
                cell.style.backgroundColor = "rgba(0,150,0,0.15)"; 
            
            }); 
    
            cell.addEventListener("mouseout", function (){
                cell.style.transform = "scale(1)"; 
                cell.style.backgroundColor = "hsl(0,0%,0%,0)"; 
            }); 

        }); 
    </script>

</body>
</html>