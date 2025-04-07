
<?php
    if (isset($_POST['settings'])) {
        $selected_page = $_POST['settings'];
        switch($selected_page) {
            case "emailchange":
                include("emailchange.php");
                break;
            case "passwordchange":
                include("passwordchange.php");
                break;
            case "logout":
                include("logoutpage.php");
                break;
            case "managefavorites.php":
                include("managefavorites.php");
                break;
        }
    }
    ?>