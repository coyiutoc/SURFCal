<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = '';
$pageMode = '';

include('styles/header.php');

// Main content.

// Sample function calls for dev testing:
$options = array (
	"start_date" => "2018-12-22 22:22:22",
	"end_date" => "2018-12-23 22:22:22"
);
createItem(1, 2, "Random note", "blah blah blah my new note", null, "note", null);
// deleteItem(34);
// editItem(28, "Not so random task", "This is a new task!!!!!!", "2018-02-22 22:22:22", "task", $options);
// getItem(28);

// getExperiencedAccounts();
getAverageItemsPerAccount();



include('styles/footer.php');

?>
