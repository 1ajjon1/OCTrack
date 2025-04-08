<?php 
session_start(); 
include "dbConnection.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];
    $old_email = trim($_POST["oldemail"]);
    $new_email = trim($_POST["newemail"]);
    if (!empty($old_email) && !empty($new_email)) {
        $dbConnection = connect2db();
        $regex = "/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|.(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/";

        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $dbConnection->prepare($query);

        if ($stmt === false) die("Error: ". $dbConnection->error);

        $stmt->bind_param("s", $username);

        if (!$stmt->execute()) die ("Error: ". $stmt->error);
        
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            if ($old_email = $row["email"]) {
                if (preg_match($regex, $new_email)) {
                    $query = "UPDATE users SET email = '$new_email' WHERE username = '".$_SESSION['username']."'";
                    if($dbConnection->query($query)){
                        $_SESSION['success'] = "Email Changed";
                        echo "Email Changed";

                    } else {
                        $_SESSION['error'] = $dbConnection->error;
                        echo 'Backend Error';
                    }
                } else {
                    $_SESSION['error'] = "Invalid Email";
                    echo "Invalid Email";
                }
            }  else {
                $_SESSION['error'] = "Incorrect old email";
                echo "Incorrect old email";
            }
        } else $_SESSION['error'] = "fetch failed";
    } else echo "Please fill in all fields";
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="authors" content="Saravanan Rajeswaran; DJ Watson; Taleiven Goundan; Justin Le">
        <script src="../backend/dbServer.js"></script>
        <script src="../backend/apiHandler.js"></script>
        <link rel="stylesheet" type="text/css" href="Stylesheet/Stylesheet_A1.css">
        <link rel="preconnect" href="https://rsms.me/">
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    </head>
    <body>
        <form action="emailchange.php" method="post" name="emailchange">
            <p>Old Email: <input id="oldemail" name="oldemail"></p>
            <p>New Email: <input id="newemail" name="newemail"></p>
            <input type="submit" value="Change Email">
        </form>
    </body>
</html>