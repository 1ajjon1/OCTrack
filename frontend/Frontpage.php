<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'dbConnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $dbConnection = connect2db();

        //Query to get user from the DB that the user specified
        $query = "SELECT * FROM users WHERE username = ?";
        //This was rerfenced from https://stackoverflow.com/questions/60174/how-can-i-prevent-sql-injection-in-php
        $stmt = $dbConnection->prepare($query);
        
        if ($stmt === false) {
            die("Prepare failed: " . $dbConnection->error);
        }
        
        $stmt->bind_param('s', $username);
        
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password_hash'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['permissions'];
                header("Location: Favourites_A2.php");
                exit();
            } else {
                $error = "Password is incorrect";
            }
            
        } else {
            $error = "No user found";
        }
        
        $stmt->close();
        $dbConnection->close();
    } else {
        echo "Please fill in all fields";
    }
}
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="authors" content="Saravanan Rajeswaran; DJ Watson; Taleiven Goundan; Justin Le">
    <script src="./scripts/loginReg.js"></script>
    <link rel="stylesheet" type="text/css" href="Stylesheet/Stylesheet_A2.css">
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
            <a href="stopInfo.php">Track Bus</a>
        </div>
    </div>

    <?php include "./ModularPHP/Card.php"; ?>  <!-- Login card on the side of the page -->

    <div class="generic-flex-container">
        <div class="element height-375">
            <h2>Client Registration</h2>
            <p>To book a trip with us, please register using your email.</p>   
            <p>Email: <input id="emailRegistration"></p>
            <p>Name: <input id="nameRegistration"></p>
            <p>Username: <input id="userRegistration"></p>
            <p>Password: <input id="passwordRegistration"></p>
            <p>Confirm password: <input id="passwordConfirmation"></p>
            
            <p id="regMsg"><br></p>
            <button id="registrationButton" onclick=registration()>Register</button>
        </div>

        <div class="element height-200">
        <form method="POST" action="Frontpage.php">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required><br><br>
                    
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required><br><br>
                    
                    <button type="submit">Login</button>
                </form>
        </div>
    </div>

    <?php include "./ModularPHP/IconRedirects.php"; ?>  <!-- If the icon is clicked in the bottom right corner of the page, it will redirect users to OC Transpo social media profile-->
    <?php include "./ModularPHP/Footer.php"; ?>

</body>
</html>
