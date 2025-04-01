<!-- Session Start -->
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
            <select id="account">
                <option>Change Email</option>
                <option>Change Password</option>
                <option>Preferences</option>
                <option>Log Out</option>
            </select><br>
            <label>Preferences</label>
            <select id="preferences">
                <option>Manage Favorites</option>
                <option>Edit Notifications</option>
            </select>
        </section>
    </body>
</html>
<!-- TO DO: 
 script to invoke php/html pages based on options selected from dropdowns
 creation of aformentioned php/html pages
 CSS formatting 
 decide whether this page should stay php or be converted to html 
 -->