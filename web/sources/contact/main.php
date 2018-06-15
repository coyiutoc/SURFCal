<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = '';
$pageMode = '';

include('styles/header.php');

// Main content.

// Sample function calls for dev testing:
// getContacts(2);
getContactDetails(5);
// deleteContact(6);

include('styles/footer.php');

?>
