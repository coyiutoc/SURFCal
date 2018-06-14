<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = '';

include('styles/header.php');

// Main content.

// Sample function calls for dev testing:
$options = array (
	"due_date" => "2018-12-22 22:22:22",
	"completion_date" => "2018-12-23 22:22:22"
);
// createItem(1, "Random Task", "blah blah blah my new task hahah task", null, "task", $options);
// deleteItem(22);
// editItem(28, "Not so random task", "This is a new task!!!!!!", "2018-02-22 22:22:22", "task", $options);
getItem(28);


include('styles/footer.php');

?>
