<?php

if (basename($_SERVER['PHP_SELF']) === 'database.php') {
    require_once('../403.php');
}

class Database {
    private $dbhost = 'localhost';
    private $dbuser = 'root';
    private $dbpass = '';
    private $schema = 'surfcal';
    public static $conn = NULL;

    function connect() {
        if (!$conn) {
            $conn = new mysqli($dbhost, $dbuser, $dbpass, $schema);
            if (!$conn) {
                // die('Connection Error: ' . mysqli_connect_error());
                require_once('../500.php');
            }
            $conn->set_charset('utf-8');
        }
    }
    
    function disconnect() {
        if ($conn) {
            $conn->close();
            $conn = NULL;
        }
    }
}

?>
