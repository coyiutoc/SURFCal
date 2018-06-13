<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = '';

include('styles/header.php');
ini_set("log_errors", 1);
ini_set("error_log", "/tmp/php-error.log");
error_log( "Php Errors!" );

// Main content.


include('functions.php');

getContacts();
getContactDetails(5);

include('styles/footer.php');

?>
