<?php
$servername = $configs->SERVERNAME;
$username = $configs->USERNAME;
$password = $configs->PASSWORD;
$dbname = $configs->DBNAME;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

/* change character set to utf8 */
if (!$conn->set_charset("utf8mb4")) {
    printf("Error loading character set utf8mb4: %s\n", $conn->error);
    exit();
} else {
   // printf("Current character set: %s\n", $conn->character_set_name());
}
?>
