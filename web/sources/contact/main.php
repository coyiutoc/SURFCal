<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = '';

include('styles/header.php');

// Main content.

// Sample function calls for dev testing:
getContacts();
getContactDetails(5);

include('styles/footer.php');

?>
