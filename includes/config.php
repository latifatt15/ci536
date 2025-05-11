<?php
$servername = "localhost";
$username = "bld27_market_user";  
$password = "b?i0eE*oa)t]";      
$database = "bld27_marketplace";  

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

