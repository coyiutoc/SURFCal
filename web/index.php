<?php

session_start();

error_reporting(E_ALL);

if (extension_loaded('zlib')) {
    ini_set('zlib.output_compression_level', 1);
    ob_start('ob_gzhandler');
}

require_once('config/properties.php');
require_once('config/database.php');
require_once('config/functions.php');

$getSection = isset($_GET[$profile]) ? $_GET[$profile] : '';

if (isset($_SESSION['user']) && isset($_SESSION['admin']) && isset($_SESSION['email']) && isset($_SESSION['calId'])) {
    $loggedin = true;
    $user = $_SESSION['user'];
    $admin = $_SESSION['admin'];
    $email = $_SESSION['email'];
    $calId = $_SESSION['calId'];
} else {
    $loggedin = false;
}

switch ($getSection) {
    case NULL:
    case '':
        header("Location: ?$profile=home");
        break;
    case 'home':
        include('sources/public/main.php');
        break;
    case 'login':
        include('sources/public/login.php');
        break;
    case 'register':
        include('sources/public/register.php');
        break;
    case 'calendar':
        include('sources/calendar/main.php');
        break;
    case 'item':
        include('sources/item/main.php');
        break;
    case 'contact':
        include('sources/contact/main.php');
        break;
    case 'acp':
        include('sources/acp/main.php');
        break;
    case 'misc':
        include('sources/misc/main.php');
        break;
    case '403':
        include('403.php');
        break;
    case '404':
        include('404.php');
        break;
    case '418':
        include('418.php');
        break;
    case '500':
        include('500.php');
        break;
    default:
        header("Location: ?$profile=404");
        break;
}

$conn->close();
ob_end_flush();

?>
