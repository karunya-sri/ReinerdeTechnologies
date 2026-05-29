<?php

$conn = new mysqli("localhost", "Reinerde", "", "reinerde_contact");

if ($conn->connect_error) {
    die("Connection failed");
}

?>