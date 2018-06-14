<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = '';

include('styles/header.php');

// Main content.

// Sample function calls for dev testing:
$options = array (
	"due_date" => "2018-12-01 12:40:00",
	"completion_date" => "2018-12-02 16:40:00"
);
createItem(1, "Random Task", "blah blah blah my new task hahah task", null, "task", $options);


include('styles/footer.php');

?>
