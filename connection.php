<?php

$username = "root";
$password = "312273417";
// 1. Connect to Local MySQL (using XAMPP)
$conn = new mysqli("localhost", $username, $password, "calendar");
$conn->set_charset("utf8mb4");