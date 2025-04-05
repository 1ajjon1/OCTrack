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
        <form method="post">
            <select id="account" onchange="submit">
                <option value="emailchange">Change Email</option>
                <option value="passwordchange">Change Password</option>
                <option value="preferences">Preferences</option>
                <option value="logout">Log Out</option>
            </select><br>
            <label>Preferences</label>
            <select id="preferences" onchange="submit">
                <option value="managefavorites">Manage Favorites</option>
                <option value="editnotifs">Edit Notifications</option>
            </select>
        </form>
    </section>
    <?php
        if(isset($_POST['select']) && $_POST['select']) {
            if ($_POST['select'] == 'emailchange') include 'emailchange.php';
            else if ($_POST['select'] == 'passwordchange') include 'passwordchange.php';
            else if ($_POST['select'] == 'logout') include 'logoutpage.php';
            else if ($_POST['select'] == 'managefavorites') include 'managefavorites.php';
        }
    ?>
    </body>
</html>
<!-- TO DO: 
 Test script to invoke php/html pages based on options selected from dropdowns
 CSS formatting 
 -->