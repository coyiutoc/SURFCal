<?php

if (basename($_SERVER['PHP_SELF']) === 'database.php') {
    require_once('../403.php');
}

ini_set('display_errors', 'On');

$host = 'localhost';
$user = 'hyngan';
$pass = 'hyngan';
$schema = 'surfcal';

$conn = new mysqli($host, $user, $pass, $schema);

if (!$conn) {
    // die('Connection Error: ' . mysqli_connect_error());
    require_once('../500.php');
}
echo "Connection success";
$conn->set_charset('utf-8');


function getContacts() {
	global $conn;

	// Projection on contactId, name
	$query = "SELECT contactId, name FROM Contacts ORDER BY name ASC;";

	$response = mysqli_query($conn, $query);

	if ($response) {
		while($row = mysqli_fetch_array($response)) {
			echo 'contactId: ' . $row["contactId"] . '<br>' .
			'name: ' . $row["name"] . '<br>';
		}
	}
}


/**
 *	Retrieves the contact's information, given the contactId
 *	@param integer 	$contactId the contact's id
 *  @return array 	list of contact information 
 */
function getContactDetails($contactId) {
	global $conn;

	// Projection on contactId, name
	$address_query = "SELECT * FROM ContactAddresses WHERE contactId=$contactId;";
	$email_query = "SELECT * FROM ContactEmails WHERE contactId=$contactId;";
	$phone_query = "SELECT * FROM ContactPhones WHERE contactId=$contactId;";

	$address_response = mysqli_query($conn, $address_query);
	$email_response = mysqli_query($conn, $email_query);
	$phone_response = mysqli_query($conn, $phone_query);

	if ($address_response) {
		while($row = mysqli_fetch_array($address_response)) {
			echo 'street: ' . $row["streetField"] . '<br>' .
			'city: ' . $row["city"] . '<br>' .
			'state: ' . $row["state_"] . '<br>' .
			'country: ' . $row["country"] . '<br>' .
			'postal: ' . $row["postal"] . '<br>';
		}
	}

	if ($email_response) {
		while($row = mysqli_fetch_array($email_response)) {
			echo 'email: ' . $row["email"] . '<br>';
		}
	}

	if ($phone_response) {
		while($row = mysqli_fetch_array($phone_response)) {
			echo 'phoneNum: ' . $row["phoneNum"] . '<br>' .
			'type: ' . $row["type"] . '<br>';
		}
	}
}


/**
 *	Creates an Item and its associated Event/Task if the item type is "event" or "task"
 *	@param integer $calendarId the calendar id
 *	@param string $name     the item's title
 * 	@param string $note   	the item's description
 *	@param string $reminder the item's reminder datetime string
 *  @param string $type 	the item's type (one of "event", "task", "reminder", "note")
 *  @param array $options 	the item's additional information (start_date and end_date for 
 *							event, due_date and completion_date for task)
 * 	@return boolean			true if item is inserted successfully
 */
function createItem($calendarId, $name, $note, $reminder, $type, $options) {
	global $conn;
	
	$item_inserted = false; // return value

	// Fix input
	$name = trim($name);
	$note = trim($note);

	$item_stmt = mysqli_prepare($conn, "INSERT INTO Items VALUES(NULL, ?, ?, NOW(), ?, ?, ?);");
	mysqli_stmt_bind_param($item_stmt, "issss", $calendarId, $name, $note, $reminder, $type);
	mysqli_stmt_execute($item_stmt);

	$item_affected_rows = mysqli_stmt_affected_rows($item_stmt);

	// Get inserted item's itemId (for adding Event or Task, if applicable)
	$item_id = mysqli_insert_id($conn);
	mysqli_stmt_close($item_stmt); // close statement

	$item_inserted = $item_inserted || ($item_affected_rows == 1);

	// Return if item insert fails
	if (!$item_inserted) return false;


	// Handle insert Item Subclass (Event/Task)
	$affected_rows = 0;
	switch($type) {
		case "event":
			$start_date = $options["start_date"];
			$end_date = $options["end_date"];

			$stmt = mysqli_prepare($conn, "INSERT INTO EventItems VALUES(?, ?, ?);");
			mysqli_stmt_bind_param($stmt, "iss", $item_id, $start_date, $end_date);
			mysqli_stmt_execute($stmt);

			$affected_rows = mysqli_stmt_affected_rows($stmt);

			mysqli_stmt_close($stmt); // close statement

			break;
		case "task":
			$due_date = $options['due_date'];
			$completion_date = $options['completion_date'];

			$stmt = mysqli_prepare($conn, "INSERT INTO TaskItems VALUES(?, ?, ?);");
			mysqli_stmt_bind_param($stmt, "iss", $item_id, $due_date, $completion_date);
			mysqli_stmt_execute($stmt);

			$affected_rows = mysqli_stmt_affected_rows($stmt);

			mysqli_stmt_close($stmt); // close statement

			break;
		default: 
			// if item is a reminder or note, then return here
			return $item_inserted;
	}

	// this is for testing only, to be deleted -----------------
	if ($affected_rows == 1) {
		echo "Added Event or Item";
	} else {
		echo "Cannot add Event or Item";
	}
	// ---------------------------------------------------------

	$item_inserted = $item_inserted || ($affected_rows == 1);

	return $item_inserted;
}

?>
