<?php

if (basename($_SERVER['PHP_SELF']) === 'functions.php') {
    require_once('../403.php');
}

function destroySession() {
    $_SESSION = array();
    if (session_id() != "" || isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 2592000, '/');
    }
    session_destory();
}

function sanitizeString($conn, $s) {
    global $db;
    $s = strip_tags($s);
    $s = htmlentities($s);
    $s = stripslashes($s);
    return mysqli_real_escape_string($conn, stripslashes($s));
}

function sqlSanitize($conn, $s) {
    return mysqli_real_escape_string($conn, stripslashes($s));
}

?>