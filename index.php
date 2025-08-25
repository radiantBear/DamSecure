<?php
echo "Hello, World!<br>";

try {
    $mysqli = new mysqli('honorsthesis_mysql', 'root', 'examplepassword', 'honorsthesis_db');
    
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}


echo "Connected successfully!";
$mysqli->close();