<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

if (basename($_SERVER['PHP_SELF']) === 'database.php') {
    require_once('../403.php');
}

ini_set('display_errors', 'On');

$host = 'localhost';
$user = '';
$pass = '';
$schema = 'surfcal';

$conn = new mysqli($host, $user, $pass, $schema);

if (!$conn) {
    // die('Connection Error: ' . mysqli_connect_error());
    require_once('../500.php');
}

$conn->set_charset('utf-8');

// =============================================================================
//                                CALENDAR
// =============================================================================

// .............................................................................
//                                  GET
// .............................................................................

// Returns all attributes of the specified Calendar.
// ___ RETURNS per Calendar: calendarId, name, description
function getCalendar($calendarId){

    global $conn;

    echo "<br> ******* Getting all info on Calendar with calendarId = " . $calendarId . " ******* <br>";

    $query = "SELECT * FROM Calendars";
    $response = @mysqli_query($conn, $query);

    if ($response){
        while($row = mysqli_fetch_assoc($response)){
            echo "<br> calendarId: " . $row["calendarId"] . 
            "<br>" . "name: " . $row["name"] . 
            "<br>" . "description: " . $row["description"] . 
            "<br>" ;
        }

        return mysqli_fetch_assoc($response);
    }
}

// Returns all Calendars and their attributes associated with a specific accountID.
// ___ RETURNS per Calendar: accID, calendarId, name, description, permissionType
function getAllCalendars($accountId){

    global $conn;

    echo "<br> ******* Getting all Calendars associated with accountId = " . $accountId . " ******* <br>";

    $query = "SELECT G.accId, G.calendarId, G.permissionType, C.name, C.description FROM Groups G, Calendars C WHERE
              G.accId=$accountId && G.calendarId = C.calendarId;";
    $response = @mysqli_query($conn, $query);

    if ($response){
        while($row = mysqli_fetch_assoc($response)){
            echo "<br> accountId: " . $row["accId"] .
            "<br> calendarId: " . $row["calendarId"] . 
            "<br>" . "name: " . $row["name"] . 
            "<br>" . "description: " . $row["description"] . 
            "<br>" . "permissionType: " . $row["permissionType"] .
            "<br>" ;
        }

        return mysqli_fetch_assoc($response);
    }
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

// .............................................................................
//                                 UPDATE
// .............................................................................

// Updates a Calendar given that the accountId doing the updating has 
// permission to do so. 
// ___ ARRAY PARAMETERS: array('name' => 'Calendar',
//                             'description' => 'I am fabulous')
// ___ RETURN: true if update is successful, false otherwise.
//
// ___ NOTE: Not all parameters need to be supplied to update. 
function updateCalendar($accountId, $calendarId, array $args = array()){

    if (hasCalendarPermission($accountId, $calendarId, 'update')){

        global $conn;

        echo "<br> ******* Updating Calendar with CalendarId = " . $calendarId . " ******* <br>";

        $query = "UPDATE Calendars SET ";
        $params = array();
        
        if (isset($args['description'])){
            $description = $args['description'];
            array_push($params, "description = '$description'");
        }

        if (isset($args['name'])){
            $name = $args['name'];
            array_push($params, "name = '$name'");
        }

        // Adding commas in the right places:
        for ($i = 0; $i < count($params)-1; $i++){
            $query .= $params[$i] . ", ";
        }
        $query .= $params[count($params)-1] . " WHERE calendarId = $calendarId";

        echo("RESULTING QUERY: " . $query); 
        mysqli_query($conn, $query);

        return checkUpdateSuccess($conn);
    }
    else{
        echo "<br> !!!!!! COULD NOT Update Calendar with CalendarId = " . $calendarId . " !!!!!! <br>";
        return false;
    }
}

/**
 *	Adds an Account to the given calendar with the given permission level
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

	return $affected_rows > 0;
}

/**
 *	Removes an Account from the given calendar with the given permission level
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

	return $affected_rows > 0;
}

// .............................................................................
//                                 DELETE
// .............................................................................

// Deletes a Calendar (and its associated Items) for the specified calendarId.
function deleteCalendar($accountId, $calendarId){
    if (hasCalendarPermission($accountId, $calendarId, 'delete')){

        global $conn;

        echo "<br> ******* Deleting Calendar with CalendarId = " . $calendarId . " ******* <br>";

        $query = "DELETE FROM Calendars WHERE calendarId = $calendarId";
        echo("RESULTING QUERY: " . $query); 
        mysqli_query($conn, $query);

        return checkUpdateSuccess($conn);
    }
    else{
        echo "<br> !!!!!! COULD NOT Delete Calendar with CalendarId = " . $calendarId . " !!!!!! <br>";
        return false;
    }
}

// =============================================================================
//							      CONTACTS
// =============================================================================

// .............................................................................
//							        GET
// .............................................................................

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

// .............................................................................
//							       UPDATE
// .............................................................................


// .............................................................................
//							       DELETE
// .............................................................................

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
	return $affected_rows > 0;
}


// =============================================================================
//							       ITEMS 
// =============================================================================

// .............................................................................
//							        CREATE
// .............................................................................

/**
 *	Creates an Item and its associated Event/Task if the item type is "event" or "task"
 *	@param integer $calendarId 	the calendar id
 *	@param integer $accId 		the account id of user who creates the item
 *	@param string $name     	the item's title
 * 	@param string $note   		the item's description
 *	@param string $reminder 	the item's reminder datetime string
 *  @param string $type 		the item's type (one of "event", "task", "reminder", "note")
 *  @param integer $colour		the item's colour
 *									0 = grey
 *									1 = red
 *									2 = orange
 *									3 = yellow
 *									4 = green
 *									5 = blue
 *									6 = purple
 *									7 = black
 *  @param string $location     the item's location
 *  @param array $options 		the item's additional information (start_date and end_date for 
 *								event, due_date and completion_date for task)
 * 	@return boolean				true if item is inserted successfully
 */ 
function createItem($calendarId, $createdBy, $name, $note, $reminder, $type, $colour, $location, $options) {
	global $conn;

	$item_inserted = false; // return value

	// Fix input
	$name = trim($name);
	$note = trim($note);

	$item_stmt = mysqli_prepare($conn, "INSERT INTO Items VALUES(0, ?, ?, NOW(), ?, ?, ?, ?, ?, ?);");
	mysqli_stmt_bind_param($item_stmt, "issssiis", $calendarId, $name, $note, $reminder, $type, $createdBy, $location, $colour);
	mysqli_stmt_execute($item_stmt);

	$item_affected_rows = mysqli_stmt_affected_rows($item_stmt);

	// Get inserted item's itemId (for adding Event or Task, if applicable)
	$item_id = mysqli_insert_id($conn);
	mysqli_stmt_close($item_stmt); // close statement

	$item_inserted = $item_inserted || ($item_affected_rows > 0);

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
	if ($affected_rows > 0) {
		echo "Added Event or Task";
	} else {
		echo "Cannot add Event or Task";
	}
	// ---------------------------------------------------------

	$item_inserted = $item_inserted || ($affected_rows > 0);

	return $item_inserted;
}

// .............................................................................
//							        GET
// .............................................................................

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

// Returns all Items and their attributes (Task and Event only) for display in
// the main list view of a given calendar. 
// ___ RETURNS per Item: itemId, name, createDate, note, reminder, type
// 
// ___ NOTE: Does not include attributes specific to Task and Event, use 
//           getItemsByType for this. 
function getItemsForCalendarDisplay($calendarId) {

    global $conn;

    echo "<br> ******* Getting all items with calendarId = " . $calendarId . " ******* <br>";

    $query = "SELECT * FROM Items WHERE calendarId = $calendarId && (type = 'event' || type = 'task')";
    $response = @mysqli_query($conn, $query);

    if ($response){
        while($row = mysqli_fetch_assoc($response)){
        	echo "<br> itemId: " . $row["itemId"] . 
                "<br>" . "name: " . $row["name"] . 
                "<br>" . "createDate: " . $row["createDate"] . 
                "<br>" . "note: " . ($row["note"] ? $row["note"] : "NULL") . 
                "<br>" . "reminder: " . ($row["reminder"] ? $row["reminder"] : "NULL") . 
                "<br>" . "type: " . $row["type"] . 
                "<br>";
        }

        return mysqli_fetch_assoc($response);
    }
}

// Returns all Items and their attributes of a specific type for the given CalendarId. 
// 
// Queries for different types:
// - Event:     Join on Items and Events to return all events corresponding to a specific Calendar, 
//              ordered in ascending order by startDate.
// - Task:      Join on Items and Tasks to return all tasks corresponding to a specific Calendar,
//              ordered in ascending order by dueDate. 
// - Reminder:  Return all Reminders corresponding to a specific Calendar, ordered in ascending order by date.
// - Note:      Return all Notes corresponding to a specific Calendar, ordered in descending order by 
//              creationDate (newest one comes first)
function getItemsByType($type, $calendarId){

	global $conn;

    $queries = array("event"    => "SELECT * FROM Items I, EventItems E WHERE I.type = 'event' && I.itemId = E.itemId 
                                    ORDER BY E.startDate ASC;", 
                     "task"     => "SELECT * FROM Items I, TaskItems E WHERE I.type = 'task' && I.itemId = E.itemId           ORDER BY E.dueDate ASC;", 
                     "reminder" => "SELECT * FROM Items WHERE type = 'reminder' ORDER BY createDate ASC;", 
                     "note"     => "SELECT * FROM Items WHERE type = 'note' ORDER BY createDate DESC;");

    if ($queries[$type]){

        $response = @mysqli_query($conn, $queries[$type]);
        echo "<br> ******* Getting all " . $type . " items with calendarId = " . $calendarId . " ******* <br>";

        if ($response){

            while($row = mysqli_fetch_assoc($response)){
                echo "<br> itemId: " . $row["itemId"] . 
                "<br>" . "name: " . $row["name"] . 
                "<br>" . "createDate: " . $row["createDate"] . 
                "<br>" . "note: " . ($row["note"] ? $row["note"] : "NULL") . 
                "<br>" . "reminder: " . ($row["reminder"] ? $row["reminder"] : "NULL") . 
                "<br>" . "type: " . $row["type"] . 
                "<br>" . "EVENT SPECIFIC: startDate: " . ($row["startDate"] ? $row["startDate"] : "NULL") . ", endDate: " .     ($row["endDate"] ? $row["endDate"] : "NULL") . 
                "<br>" . "TASK SPECIFIC: dueDate: " . ($row["dueDate"] ? $row["dueDate"] : "NULL") . ", completionDate: " .     ($row["completionDate"] ? $row["completionDate"] : "NULL") . 
                "<br>"; 
            }

            return mysqli_fetch_assoc($response);
        }
    }
    else{
        echo("<br> !!!! Incorrect type inputted: " . $type . " !!!! <br>");
    }
}

// Returns the total number of Items created for the specified Calendar.
function countNumCalendarItems($calendarId){

    global $conn;

    echo "<br> ******* Counting all items with calendarId = " . $calendarId . " ******* <br>";

    $query = "SELECT I.calendarId, COUNT(*) AS itemCount 
              FROM Calendars C, Items I 
              WHERE C.calendarId = I.calendarId && C.calendarId = $calendarId 
              GROUP BY C.calendarId;";
    $response = @mysqli_query($conn, $query);

    if ($response){
        $count = mysqli_fetch_assoc($response)["itemCount"];

        if (empty($count)){
            $count = 0;
        }

        echo ("Count: {$count}");

        return $count;
    }
}
// Retrieves the minimum or maximum number of items created by a single Account.
// ___ PARAMS: 'min' or 'max'
function getMinMaxItemsPerAccount($operation){

    global $conn;

    $queries = array("min"    => "SELECT MIN(A.itemCount) AS Result 
                                  FROM (SELECT COUNT(*) as itemCount 
                                        FROM Items GROUP BY createdBy) 
                                        AS A;", 
                     "max"    => "SELECT MAX(A.itemCount) AS Result
                                  FROM (SELECT COUNT(*) as itemCount 
                                        FROM Items GROUP BY createdBy) 
                                        AS A;");
    if ($queries[$operation]){

        $response = @mysqli_query($conn, $queries[$operation]);
        echo "<br> ******* Get " . $operation . " of number of items created per Account. ******* <br>";

        if ($response){

            $result = mysqli_fetch_assoc($response)["Result"];
            echo ("{$operation} : {$result}");

            return $result;
        }
    }
    else{
        echo("<br> !!!! Incorrect type inputted: " . $operation . " !!!! <br>");
    }
}

// .............................................................................
//							       UPDATE
// .............................................................................

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

	$item_edited = $item_edited || ($item_affected_rows > 0);

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
	if ($affected_rows > 0) {
		echo "Edited Event or Task";
	} else {
		echo "Cannot edit Event or Task";
	}
	// ---------------------------------------------------------

	$item_edited = $item_edited || ($affected_rows > 0);

	return $item_edited;
}

// .............................................................................
//							       DELETE
// .............................................................................

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

	return $affected_rows > 0;
}

// =============================================================================
//							       ACCOUNT
// =============================================================================


// .............................................................................
//							        GET
// .............................................................................

/**
 *	Retrieves all accounts in the database (for Admin Panel)
 * 	@return array		list of account information (id, username, email, name)
 */
function getAllAccounts() {
	global $conn;

	$query = "SELECT id, username, email, name FROM Accounts ORDER BY id ASC;";

	$result = mysqli_query($conn, $query);

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
 *	Retrieves accounts by username
 * 	@return array		list of account information (id, username, email, name)
 */
function getAccountByUser($user) {
	global $conn;
	$query = "SELECT * FROM `Accounts` WHERE `username`='$user';";
	return mysqli_fetch_array(mysqli_query($conn, $query));
}

/**
 *	Retrieves list of accounts that have created all item types (i.e. experienced users)
 * 	@return array	list of accounts
 */
function getExperiencedAccounts() {
	global $conn;

	$query = "	SELECT id, username, email, name 
				FROM Accounts A
				WHERE NOT EXISTS
						(SELECT T.type
						 FROM ItemType T
						 WHERE NOT EXISTS (SELECT I.type
						   				FROM Items I
										WHERE I.createdBy=A.id AND T.type=I.type));";

	$result = mysqli_query($conn, $query);

	if ($result) {
		while($row = mysqli_fetch_assoc($result)) {
			echo 'id: ' . $row["id"] . '<br>' . 
			'username: ' . $row["username"] . '<br>' . 
			'email: ' . $row["email"] . '<br>' . 
			'name: ' . $row["name"] . '<br>';

			// TODO: Add to result
		}
	}

	// TODO: return result
}


// Returns number of Contacts for a given Account.
function countNumContact($accountId){
    global $conn;

    echo "<br> ******* Counting number of Contacts for AccountId = " . $accountId . " ******* <br>";

    $query = "SELECT A.id, COUNT(*) AS contactCount 
              FROM Accounts A, Contacts C 
              WHERE A.id = C.accId && A.id = $accountId 
              GROUP BY A.id;";
    $response = @mysqli_query($conn, $query);

    if ($response){
        $count = mysqli_fetch_assoc($response)["contactCount"];

        if (empty($count)){
            $count = 0;
        }

        echo ("Count: {$count}");

        return $count;
    }
}

/**
 *	Retrieves average number of items created by accounts (for Admin Panel)
 * 	@return Float 	Average number of items
 */
function getAverageItemsPerAccount() {
	global $conn;

	$query = "SELECT AVG(C.count)
			  FROM (SELECT COUNT(*) as count
					FROM Items
					GROUP BY createdBy) C";

	$result = mysqli_query($conn, $query);

	if ($result) {
		$row = mysqli_fetch_assoc($result);
		echo $row["AVG(C.count)"];
		return $row["AVG(C.count)"];
	}

	return 0;
}

/**
 *	Retrieves average number of contacts created by accounts (for Admin Panel)
 * 	@return Float 	Average number of contacts
 */
function getAverageContactsPerAccount() {
	global $conn;

	$query = "SELECT AVG(C.count)
			  FROM (SELECT COUNT(*) as count
					FROM Contacts
					GROUP BY accId) C";

	$result = mysqli_query($conn, $query);

	if ($result) {
		$row = mysqli_fetch_assoc($result);
		echo $row["AVG(C.count)"];
		return $row["AVG(C.count)"];
	}

	return 0;
}

/**
 *	Retrieves min and max number of contacts created per account (for Admin Panel)
 * 	@return Float 	Average number of contacts
 */
function getMinMaxContactsPerAccount() {
	global $conn;

	$query = "SELECT MIN(C.count), MAX(C.count)
			  FROM (SELECT COUNT(*) as count
					FROM Contacts
					GROUP BY accId) C";

	$result = mysqli_query($conn, $query);

	if ($result) {
		$row = mysqli_fetch_assoc($result);
		echo $row["MIN(C.count)"];
		echo $row["MAX(C.count)"];
		// TODO: return result
	}

	return 0;
}

// .............................................................................
//							       UPDATE
// .............................................................................

// Updates an Account with the specified Id.
// ___ ARRAY PARAMETERS: array('password' => 'doop',
//                             'name'     => 'bob',
//                             'birthday' => '1998-5-13')
// ___ RETURN: true if update is successful, false otherwise.
//
// ___ NOTE: Not all parameters need to be supplied to update. 
function updateAccount($accountId, array $args = array()){

    global $conn;

    echo "<br> ******* Updating Account with AccountId = " . $accountId . " ******* <br>";

    $query = "UPDATE Accounts SET ";
    $params = array();
    
    if (isset($args['password'])){
        $password = $args['password'];
        array_push($params, "password = '$password'");
    }

    if (isset($args['name'])){
        $name = $args['name'];
        array_push($params, "name = '$name'");
    }

    if (isset($args['birthday'])){
        $birthday = $args['birthday'];
        array_push($params, "birthday = '$birthday'");
    }

    // Adding commas in the right places:
    for ($i = 0; $i < count($params)-1; $i++){
        $query .= $params[$i] . ", ";
    }
    $query .= $params[count($params)-1] . " WHERE id = $accountId";

    // Do update query and check if rows were affected:
    echo("RESULTING QUERY: " . $query); 
    mysqli_query($conn, $query);

    return checkUpdateSuccess($conn);
}

// .............................................................................
//							       DELETE
// .............................................................................

// =============================================================================
//                               HELPER FXNS
// =============================================================================

// Validates permission of an account modifying a specific calendar.
// If type = "update", only admin and users can update.
// If type = "delete", only admin can delete. 
//
// ___ PARAMS: type = 'update' or 'delete'.
// ___ RETURNS: true if has permission, 
//              false otherwise. 
function hasCalendarPermission($accountId, $calendarId, $operationType){

    global $conn;

    // Check valid type input:
    if ($operationType !== 'update' && $operationType !== 'delete'){
        echo("<br> !!!! Incorrect type inputted: " . $type . " !!!! <br>");
        return false;
    }

    // Permissions for different operations:
    $operationPermission = array('update' => ['admin', 'user'],
                                 'delete' => ['admin']);

    $query = "SELECT permissionType FROM Groups WHERE (calendarId = $calendarId && accId = $accountId)";
    $response = @mysqli_query($conn, $query);

    if ($response){

        $permissionType = mysqli_fetch_assoc($response)["permissionType"];

        if (in_array($permissionType, $operationPermission[$operationType])){
            echo "<br> [Permission Check: AccountId " . $accountId . " has permission to 
            modify Calendar with id = " . $calendarId . ".]<br>";

            return true;
        }
        else{
            echo "<br> [Permission Check: AccountId " . $accountId . " DOES NOT have permission to "
            . $operationType . " modify Calendar with id = " . $calendarId . ".]<br>";
            
            return false;
        }
    }  
    else { return false; }  
}

// Checks whether an update to the DB was successful.
// ___ RETURNS: true if update was successful,
//              otherwise false.
function checkUpdateSuccess($conn){

    if (mysqli_affected_rows($conn) > 0) {
        echo "<br> Record updated successfully. Rows affected: " . mysqli_affected_rows($conn) . "<br>";
        return true;
        } 
    else {
        echo "<br> !!!! Record not updated. No rows affected. Check query. <br>";
        return false;
    }
}

?>
