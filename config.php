<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nurse_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully"; // You can remove this line once you confirm the connection is successful
?>
