<?php session_start();?>    

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
    <div id="accountname">Placeholder</div>
    <section>
        <label>Account</label>
        <form action="loadsetting.php" method="post" name="settingsform">
            <select form="settingsform" name="settings" onchange="submit">
                <option value="emailchange">Change Email</option>
                <option value="passwordchange">Change Password</option>
                <option value="preferences">Preferences</option>
                <option value="logout">Log Out</option>
                <option value="managefavorites">Manage Favorites</option>
                <option value="editnotifs">Edit Notifications</option>
            </select>
        </form>
    </section>
    <?php include"loadsetting.php";?>
    </body>
</html>
<!-- TO DO: 
 Test script to invoke php/html pages based on options selected from dropdowns
 CSS formatting 
 -->