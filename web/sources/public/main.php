<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = 'Home';

include('styles/header.php');

// Main content.

include('styles/footer.php');

?>
