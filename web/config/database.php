<?php

if (basename($_SERVER['PHP_SELF']) === 'database.php') {
    require_once('../403.php');
}

$host = 'localhost';
$user = 'root';
$pass = '';
$schema = 'surfcal';

$conn = new mysqli($dbhost, $dbuser, $dbpass, $schema);

if (!$conn) {
    // die('Connection Error: ' . mysqli_connect_error());
    require_once('../500.php');
}

$conn->set_charset('utf-8');

?>
