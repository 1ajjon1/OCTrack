<?php 
session_start(); 
include "dbConnection.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];
    $old_password = trim($_POST["oldpw"]);
    $new_password = trim($_POST["newpw"]);
    $confirm_password = trim($_POST["confpw"]);
    if (!empty($old_password) && !empty($new_password) && !empty($confirm_password)) {
        $dbConnection = connect2db();

        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $dbConnection->prepare($query);

        if ($stmt === false) die("Error: ". $dbConnection->error);

        $stmt->bind_param("s", $username);

        if (!$stmt->execute()) die ("Error: ". $stmt->error);
        
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            if (password_verify($old_password, $row['password_hash'])) {
                $_SESSION["oldpw"] = $old_password;
                $_SESSION["newpw"] = $new_password;
                $_SESSION["confpw"] = $confirm_password;
                if ($new_password == $confirm_password) {
                    $passchange = password_hash($new_password, PASSWORD_DEFAULT);
                    $query = "UPDATE users SET password_hash = '$passchange' WHERE username = '".$_SESSION['username']."'";
                    if($dbConnection->query($query)){
                        $_SESSION['success'] = "Password Updated";
                        echo "Password Updated";

                    } else {
                        $_SESSION['error'] = $dbConnection->error;
                        echo 'Backend Error';
                    }
                } else {
                    $_SESSION['error'] = "New passwords don't match";
                    echo "Passwords don't match";
                }
            }  else {
                $_SESSION['error'] = "Incorrect old password";
                echo "Incorrect old password";
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
        <form action="passwordchange.php" method="post" name="pwchange">
            <p>Old Password: <input type="password" id="oldpw" name="oldpw"></p>
            <p>New Password: <input type="password" id="newpw" name="newpw"></p>
            <p>Confirm Password <input type="password" id="confpw" name="confpw"></p>
            <input type="submit" value="Change Password">
        </form>
    </body>
</html>