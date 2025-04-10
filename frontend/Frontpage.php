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
    <title>OC Transpo Home Page</title>

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
            font-family: Inter-28pt;
            src: url(fonts/Inter/static/Inter_28pt-Light.ttf);
        }
    </style>
</head>
<body>
  
    <div class="hero-image">
        <div class="hero-text">
          <h1>OC Transpo Tracker</h1>
          <p>Plan the day ahead</p>
          <button>something</button>
        </div>
    </div>



    <?php include "./ModularPHP/Card.php"; ?>           <!-- Login card on the side of the page -->

    
   
    <div class="generic-flex-container">            
        <div class="element height-375"> 
            <h2>Client Registration</h2>
            <p>To book a trip with us, please register using your email.</p>   
            <p>Email: <input id="i1"></p>
            <p>Name: <input id="i2"></p>
            <p>Username: <input id="i3"></p>
            <p>Password: <input id="i4"></p>
            <p>Confirm password: <input id="i5"></p>
            
            <p id="msg"><br></p>
            <button>Register</button>
        </div>

        <div class="element height-200">
            <h2>Login</h2>
            <p>Email: <input></p>
            <p>Password: <input></p>
            <button>Register</button>
        </div>  
    
        <div class="travel-planner element height-250">
            <h2>Travel Planner</h2>
            <p>Origin: <input></p>
            <p>Destination: <input></p>
            <button>Change routes</button>
        </div>        
    </div>



    <?php include "./ModularPHP/IconRedirects.php"; ?>           <!-- If the icon is clicked in the bottom right corner of the page, it will redirect users to OC Transpo social media profile-->
    <?php include "./ModularPHP/Footer.php"; ?>

</body>
</html>