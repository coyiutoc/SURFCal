<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = '';
$pageMode = '';

include_once('functions.php');

if (!$loggedin) {
	echo "NOT LOGGED IN";
	header("Location: ?$profile=login");
} else {
	include('styles/header.php');

	// show list of user's groups
	showGroupList();

	include('styles/footer.php');
}

?>