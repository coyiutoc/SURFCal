<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = 'Misc';
$pageMode = '';

$getSection = isset($_GET['misc']) ? $_GET['misc'] : '';
switch ($getSection) {
    case NULL:
    case '':
    case 'main':
        include('styles/header.php');
        include('styles/footer.php');
        break;
    case 'logout':
        include('sources/misc/logout.php');
        break;
    default:
        header("Location: ?$profile=404");
        break;
}

?>
