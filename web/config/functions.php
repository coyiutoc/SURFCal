<?php

if (basename($_SERVER['PHP_SELF']) === 'functions.php') {
    require_once('../403.php');
}

function destroySession() {
    session_destroy();
    $_SESSION = array();
}

function sanitizeString($s) {
    global $conn;
    $s = strip_tags($s);
    $s = htmlentities($s);
    $s = stripslashes($s);
    return mysqli_real_escape_string($conn, stripslashes($s));
}

function sqlSanitize($s) {
    global $conn;
    return mysqli_real_escape_string($conn, stripslashes($s));
}

function createAccountHelper($username, $email, $password, $name, $birthday) {
    $calId = createCalendar(sqlSanitize($name) . '\'s Calendar', '');
    $accId = $calId !== false ? createAccount($username, $email, $password, $name, $birthday, $calId) : false;
    return $accId !== false? addAccountToCalendar($accId, $calId, 'admin') : false;
}

?>