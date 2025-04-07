<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="authors" content="Saravanan Rajeswaran; DJ Watson; Taleiven Goundan; Justin Le">
    <script src="./scripts/loginReg.js"></script>
    <link rel="stylesheet" type="text/css" href="./Stylesheet/Stylesheet_A2.css">
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
    <?php include "./ModularPHP/Header.php"; ?>
    <?php include "./ModularPHP/Card.php"; ?>

    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

    <!-- Layout of the page -->
    <div class="generic-flex-container" id="body">
        <!-- Left side of the page contains the route table and the travel planner element -->
        <div class="left-side">
        <div class="routes-container">
            <h2>Your Favourite Stops</h2>
            <?php
            include 'dbConnection.php';
            $db = connect2db();

            $username = $_SESSION['username'];
            $query = "SELECT stop_number FROM favorite_stops WHERE username = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
                    $stopNo = htmlspecialchars($row['stop_number']);
            ?>
            <div>
            <form action="stopInfo.php" method="get" style="margin-bottom: 10px;">
                <input type="hidden" name="stopNo" value="<?php echo $stopNo; ?>">
                <button type="submit" class="favorite-stop-btn">
                View Stop <?php echo $stopNo; ?>
                </button>
            </form>
                </div>
            <?php 
                endwhile;
            else:
                echo "<p>You haven't favorited any stops yet.</p>";
            endif;

            $stmt->close();
            $db->close();
            ?>
        </div>
        <!-- The right side of the page contains an image -->
        <div class="right-side">
            <img src="images/IMG_4782.jpeg" class="background-image" style="z-index: -10;">
        </div>
    </div>

    <?php include "./ModularPHP/IconRedirects.php"; ?>
    <?php include "./ModularPHP/Footer.php"; ?>

    <script>
        // Route highlighting functionality
        const route = document.querySelectorAll(".routes-container div");
        route.forEach((cell) => {
            cell.addEventListener("mouseover", function() {
                cell.style.backgroundColor = "rgba(0,150,0,0.15)"; 
            }); 
            cell.addEventListener("mouseout", function() {
                cell.style.transform = "scale(1)"; 
                cell.style.backgroundColor = "hsl(0,0%,0%,0)"; 
            }); 
        });
    </script>
</body>
</html>