<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = 'Login';

include('styles/header.php');

// Login content.

include('styles/footer.php');

?>
