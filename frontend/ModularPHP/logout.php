<?php
session_start();
session_destroy();
header("Location: ../Frontpage.php");
exit();
?>