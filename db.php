<?php
$servername = "brainbench-server.mysql.database.azure.com";
$username = "bxydgutnrg";
$password = "bSlKoKioZ$5qzx$7";
$dbname = "brainbench";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
