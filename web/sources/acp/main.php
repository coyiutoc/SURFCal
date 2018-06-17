<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = 'Site Admin Panel';
$pageMode = '';

$loadMain = false;

if ($admin !== true) {
    header("Location: ?$profile=404");
} else {
    $getSection = isset($_GET['acp']) ? $_GET['acp'] : '';
    switch ($getSection) {
        case NULL:
            case '':
            header("Location: ?$profile=acp&acp=main");
            break;
        case 'main':
            $loadMain = true;
            break;
        case 'accounts':
            include('sources/acp/accounts.php');
            break;
        case 'stats':
            include('sources/acp/stats.php');
            break;
        default:
            header("Location: ?$profile=404");
            break;
    }
    
    if ($loadMain === true) {
        include('styles/header.php');
        
        // Main content.        
        include('styles/footer.php');
    }
}

?>
