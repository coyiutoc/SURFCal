<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = 'Register';

include('styles/header.php');

// Register content.

include('styles/footer.php');

?>
