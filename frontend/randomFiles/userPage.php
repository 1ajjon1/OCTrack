<?php 
session_start(); 
include "../dbConnection.php";
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
    <div id="accountname"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
    <section>
        <label>Account</label>
        <form action="" method="post" name="settingsform">
            <select form="settingsform" id="settings">
                <option value="emailchange">Change Email</option>
                <option value="passwordchange">Change Password</option>
                <option value="managefavorites">Manage Favorites</option>
            </select>
        </form>
    </section>
    <iframe id="settingframe" src="emailchange.html" width="100%" height="500px"></iframe>
    <script>
        document.getElementById("settings").addEventListener('change', function() {
            let frame = document.getElementById("settingframe");
            switch(this.value) {
                case "emailchange":
                    frame.setAttribute('src', 'emailchange.php');
                    break;
                case "passwordchange":
                    frame.setAttribute('src', 'passwordchange.php');
                    break;
                case "managefavorites":
                    frame.setAttribute('src', 'managefavorites.php');
                    break;
            }
        });
    </script>
    </body>
</html>
<!-- TO DO: 
 CSS formatting 
 -->