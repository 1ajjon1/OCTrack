<?php
//Pulled your the NET3010_25W github
function connect2db(){
    $host = "localhost";
    $user = "root";
    $password = "";
    $name = "users";

    $connection = mysqli_connect($host, $user, $password, $name);
    if (!$connection) {
        exit("Connection Failed: " . mysqli_connect_error());
    }   
    return $connection;
}

function closedb($connection){
    mysqli_close($connection);
}
?>