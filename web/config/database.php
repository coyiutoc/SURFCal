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


/**
 *	Retrieves a list of contacts (id and name) given the accountId
 *	@param integer 	$accId 		the account id
 *  @return array 				list of contacts 
 */
function getContacts($accId) {
	global $conn;

	// Projection on contactId, name, sort by alphabetical order
	$stmt = mysqli_prepare($conn, "SELECT contactId, name FROM Contacts WHERE accId=? ORDER BY name ASC;");
	mysqli_stmt_bind_param($stmt, "i", $accId);
	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);

	if ($result) {
		while($row = mysqli_fetch_assoc($result)) {
			echo 'contactId: ' . $row["contactId"] . '<br>' .
			'name: ' . $row["name"] . '<br>';

			// TODO: add to result
		}
	}

	mysqli_stmt_close($stmt); // close statement

	// TODO: return list of contacts
}


/**
 *	Retrieves the contact's information, given the contactId
 *	@param integer 	$contactId 	the contact's id
 *  @return array 				list of contact information 
 */
function getContactDetails($contactId) {
	global $conn;

	// Get Address
	$address_stmt = mysqli_prepare($conn, "SELECT * FROM ContactAddresses WHERE contactId=?;");
	mysqli_stmt_bind_param($address_stmt, "i", $contactId);
	mysqli_stmt_execute($address_stmt);
	$address_result = mysqli_stmt_get_result($address_stmt);
	mysqli_stmt_close($address_stmt); // close statement

	// Get Email
	$email_stmt = mysqli_prepare($conn, "SELECT * FROM ContactEmails WHERE contactId=?;");
	mysqli_stmt_bind_param($email_stmt, "i", $contactId);
	mysqli_stmt_execute($email_stmt);
	$email_result = mysqli_stmt_get_result($email_stmt);
	mysqli_stmt_close($email_stmt); // close statement

	// Get Phone numbers
	$phone_stmt = mysqli_prepare($conn, "SELECT * FROM ContactPhones WHERE contactId=?;");
	mysqli_stmt_bind_param($phone_stmt, "i", $contactId);
	mysqli_stmt_execute($phone_stmt);
	$phone_result = mysqli_stmt_get_result($phone_stmt);
	mysqli_stmt_close($phone_stmt); // close statement

	if ($address_result) {
		while($row = mysqli_fetch_assoc($address_result)) {
			echo 'street: ' . $row["streetField"] . '<br>' .
			'city: ' . $row["city"] . '<br>' .
			'state: ' . $row["state_"] . '<br>' .
			'country: ' . $row["country"] . '<br>' .
			'postal: ' . $row["postal"] . '<br>';

			// TODO: Add to result
		}
	}

	if ($email_result) {
		while($row = mysqli_fetch_assoc($email_result)) {
			echo 'email: ' . $row["email"] . '<br>';

			// TODO: Add to result
		}
	}

	if ($phone_result) {
		while($row = mysqli_fetch_assoc($phone_result)) {
			echo 'phoneNum: ' . $row["phoneNum"] . '<br>' .
			'type: ' . $row["type"] . '<br>';

			// TODO: Add to result
		}
	}

	// TODO: return list of contact information
}

/**
 *	Deletes a Contact
 *	@param integer $contactId 	the contact's id
 * 	@return boolean				true if contact is deleted successfully
 */
function deleteContact($contactId) {
	global $conn;

	$stmt = mysqli_prepare($conn, "DELETE FROM Contacts WHERE contactId=?;");
	mysqli_stmt_bind_param($stmt, "i", $contactId);
	mysqli_stmt_execute($stmt);

	$affected_rows = mysqli_stmt_affected_rows($stmt);
	return $affected_rows == 1;
}

/**
 *	Creates an Item and its associated Event/Task if the item type is "event" or "task"
 *	@param integer $calendarId 	the calendar id
 *	@param string $name     	the item's title
 * 	@param string $note   		the item's description
 *	@param string $reminder 	the item's reminder datetime string
 *  @param string $type 		the item's type (one of "event", "task", "reminder", "note")
 *  @param array $options 		the item's additional information (start_date and end_date for 
 *								event, due_date and completion_date for task)
 * 	@return boolean				true if item is inserted successfully
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
		echo "Added Event or Task";
	} else {
		echo "Cannot add Event or Task";
	}
	// ---------------------------------------------------------

	$item_inserted = $item_inserted || ($affected_rows == 1);

	return $item_inserted;
}

/**
 *	Edit an Item and its associated Event/Task if the item type is "event" or "task"
  *	@param integer $itemId 		the item id
 *	@param string $name     	the item's title
 * 	@param string $note   		the item's description
 *	@param string $reminder 	the item's reminder datetime string
 *  @param string $type 		the item's type (one of "event", "task", "reminder", "note")
 *  @param array $options 		the item's additional information (start_date and end_date for 
 *								event, due_date and completion_date for task)
 * 	@return boolean				true if item is edited successfully
 */
function editItem($itemId, $name, $note, $reminder, $type, $options) {
	// TODO: can refactor createItem and editItem
	global $conn;

	$item_edited = false; // return value

	// Fix input
	$name = trim($name);
	$note = trim($note);

	$item_stmt = mysqli_prepare($conn, "UPDATE Items SET name=?, note=?, reminder=? WHERE itemId=?");
	mysqli_stmt_bind_param($item_stmt, "sssi", $name, $note, $reminder, $itemId);
	mysqli_stmt_execute($item_stmt);

	$item_affected_rows = mysqli_stmt_affected_rows($item_stmt);
	mysqli_stmt_close($item_stmt); // close statement
	// echo $item_affected_rows;

	$item_edited = $item_edited || ($item_affected_rows == 1);

	// Handle edit Item Subclass (Event/Task)
	echo $type;
	$affected_rows = 0;
	switch($type) {
		case "event":
			$start_date = $options["start_date"];
			$end_date = $options["end_date"];

			$stmt = mysqli_prepare($conn, "UPDATE EventItems SET startDate=?, endDate=? WHERE itemId=?");
			mysqli_stmt_bind_param($stmt, "ssi", $start_date, $end_date, $itemId);
			mysqli_stmt_execute($stmt);

			$affected_rows = mysqli_stmt_affected_rows($stmt);

			mysqli_stmt_close($stmt); // close statement

			break;
		case "task":
			$due_date = $options['due_date'];
			$completion_date = $options['completion_date'];
			echo "got here";

			$stmt = mysqli_prepare($conn, "UPDATE TaskItems SET dueDate=?, completionDate=? WHERE itemId=?");
			mysqli_stmt_bind_param($stmt, "ssi", $due_date, $completion_date, $itemId);
			// echo var_dump($stmt);
			mysqli_stmt_execute($stmt);

			$affected_rows = mysqli_stmt_affected_rows($stmt);

			mysqli_stmt_close($stmt); // close statement

			break;
		default: 
			// if item is a reminder or note, then return here
			return $item_edited;
	}

	// this is for testing only, to be deleted -----------------
	if ($affected_rows == 1) {
		echo "Edited Event or Task";
	} else {
		echo "Cannot edit Event or Task";
	}
	// ---------------------------------------------------------

	$item_edited = $item_edited || ($affected_rows == 1);

	return $item_edited;
}

/**
 *	Retrieves an Item
 *	@param integer $itemId 		the item id
 * 	@return array				a list of the item's information
 */
function getItem($itemId) {
	global $conn;

	$item_stmt = mysqli_prepare($conn, "SELECT * FROM Items WHERE itemId=?;");
	mysqli_stmt_bind_param($item_stmt, "i", $itemId);
	mysqli_stmt_execute($item_stmt);

	$item_result = mysqli_stmt_get_result($item_stmt);

	mysqli_stmt_close($item_stmt); // close statement

	if ($item_result) {
		$item = mysqli_fetch_assoc($item_result);
		echo 'itemId: ' . $item["itemId"] . '<br>' . 
			'calendarId: ' . $item["calendarId"] . '<br>' .
			'name: ' . $item["name"] . '<br>' .
			'createDate: ' . $item["createDate"] . '<br>' .
			'note: ' . $item["note"] . '<br>' .
			'reminder: ' . $item["reminder"] . '<br>' .
			'type: ' . $item["type"] . '<br>';

		if ($item["type"] == "event") {
			$stmt = mysqli_prepare($conn, "SELECT * FROM EventItems WHERE itemId=?;");
			mysqli_stmt_bind_param($stmt, "i", $itemId);
			mysqli_stmt_execute($stmt);

			$result = mysqli_stmt_get_result($stmt);

			$event = mysqli_fetch_assoc($result);

			mysqli_stmt_close($stmt); // close statement

			// for testing:
			echo 'itemId: ' . $event["itemId"] . '<br>' . 
				'startDate: ' . $event["startDate"] . '<br>' .
				'endDate: ' . $event["endDate"] . '<br>';

			// TODO: Add event information to result

		} else if ($item["type"] == "task") {
			$stmt = mysqli_prepare($conn, "SELECT * FROM TaskItems WHERE itemId=?;");
			mysqli_stmt_bind_param($stmt, "i", $itemId);
			mysqli_stmt_execute($stmt);

			$result = mysqli_stmt_get_result($stmt);

			$task = mysqli_fetch_assoc($result);

			mysqli_stmt_close($stmt); // close statement

			// for testing:
			echo 'itemId: ' . $task["itemId"] . '<br>' . 
				'dueDate: ' . $task["dueDate"] . '<br>' .
				'completionDate: ' . $task["completionDate"] . '<br>';

			// TODO: Add task information to result
		}
	}
	
	// TODO: return item
}

/**
 *	Deletes an Item
 *	@param integer $itemId 		the item id
 * 	@return boolean				true if item is deleted successfully
 */
function deleteItem($itemId) {
	global $conn;

	$stmt = mysqli_prepare($conn, "DELETE FROM Items WHERE itemId=?;");
	mysqli_stmt_bind_param($stmt, "i", $itemId);
	mysqli_stmt_execute($stmt);

	$affected_rows = mysqli_stmt_affected_rows($stmt);

	mysqli_stmt_close($stmt); // close statement

	return $affected_rows == 1;
}

/**
 *	Retrieves a list of account information (username, email) associated with a given calendarId
 *	@param integer $calendarId 	the calendar id
 * 	@return array				list of account information (username, email)
 */
function getAccountsInCalendar($calendarId) {
	global $conn;

	$stmt = mysqli_prepare($conn, "SELECT username, email FROM Groups G, Accounts A WHERE G.calendarId=? && G.accId=A.id;");
	mysqli_stmt_bind_param($stmt, "i", $calendarId);
	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);
	mysqli_stmt_close($stmt); // close statement

	if ($result) {
		while($row = mysqli_fetch_assoc($result)) {
			echo 'username: ' . $row["username"] . '<br>' . 
			'email: ' . $row["email"] . '<br>';
		}
	}

	// TODO: return list of accounts
}


/**
 *	Retrieves all accounts in the database (for Admin Panel)
 * 	@return array		list of account information (id, username, email, name)
 */
function getAllAccounts() {
	global $conn;

	$query = "SELECT id, username, email, name FROM Accounts ORDER BY id ASC;";

	$result = @mysqli_query($conn, $query);

	if ($result) {
		while($row = mysqli_fetch_assoc($result)) {
			echo 'id: ' . $row["id"] . '<br>' . 
			'username: ' . $row["username"] . '<br>' . 
			'email: ' . $row["email"] . '<br>' . 
			'name: ' . $row["name"] . '<br>';
		}
	}

	// TODO: return list of accounts
}


/**
 *	Adds a Account to the given calendar with the given permission level
 *	@param integer $accId 			the account id
 *	@param integer $calendarId 		the calendar id
 *	@param string $permissionType 	the permission level ("viewer", "user", "admin")
 * 	@return boolean					true if the Account is added successfully
 */
function addAccountToCalendar($accId, $calendarId, $permissionType) {
	global $conn;

	$stmt = mysqli_prepare($conn, "INSERT INTO Groups VALUES(?, ?, ?);");
	mysqli_stmt_bind_param($stmt, "iis", $accId, $calendarId, $permissionType);
	mysqli_stmt_execute($stmt);

	$affected_rows = mysqli_stmt_affected_rows($stmt);

	mysqli_stmt_close($stmt); // close statement

	return $affected_rows == 1;
}

/**
 *	Adds a Account to the given calendar with the given permission level
 *	@param integer $accId 			the account id
 *	@param integer $calendarId 		the calendar id
 *	@param string $permissionType 	the permission level ("viewer", "user", "admin")
 * 	@return boolean					true if the Account is added successfully
 */
function removeAccountFromCalendar($accId, $calendarId) {
	global $conn;

	$stmt = mysqli_prepare($conn, "DELETE FROM Groups WHERE accId=? && calendarId=?;");
	mysqli_stmt_bind_param($stmt, "ii", $accId, $calendarId);
	mysqli_stmt_execute($stmt);

	$affected_rows = mysqli_stmt_affected_rows($stmt);

	mysqli_stmt_close($stmt); // close statement

	return $affected_rows == 1;
}

/**
 *	Retrieves list of accounts that have created all item types (i.e. experienced users)
 * 	@return array	list of accounts
 */
function getExperiencedAccounts() {

}

?>
