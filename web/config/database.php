<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

if (basename($_SERVER['PHP_SELF']) === 'database.php') {
    require_once('../403.php');
}

$host = 'localhost';
$user = 'root';
$pass = '';
$schema = 'surfcal';

$conn = new mysqli($host, $user, $pass, $schema);

if (!$conn) {
    // die('Connection Error: ' . mysqli_connect_error());
    require_once('../500.php');
}
echo "Connection success";
$conn->set_charset('utf-8');

// =============================================================================
//							      CONTACTS
// =============================================================================

// .............................................................................
//							        GET
// .............................................................................

function getContacts() {
  global $conn;

  // Projection on contactId, name
  $query = "SELECT contactId, name FROM Contacts";

	$response = mysqli_query($conn, $query);

	if ($response) {
		while($row = mysqli_fetch_array($response)) {
			echo 'contactId: ' . $row[contactId] . '<br>' .
			'name: ' . $row[name] . '<br>';
		}
	}
}

function getContactDetails($contactId) {
  global $conn;

  // Projection on contactId, name
  $address_query = "SELECT * FROM ContactAddresses WHERE contactId=$contactId";
  $email_query = "SELECT * FROM ContactEmails WHERE contactId=$contactId";
  $phone_query = "SELECT * FROM ContactPhones WHERE contactId=$contactId";

	$address_response = mysqli_query($conn, $address_query);
	$email_response = mysqli_query($conn, $email_query);
	$phone_response = mysqli_query($conn, $phone_query);

	if ($address_response) {
		while($row = mysqli_fetch_array($address_response)) {
			echo 'street: ' . $row[streetField] . '<br>' .
			'city: ' . $row[city] . '<br>' .
			'state: ' . $row[state_] . '<br>' .
			'country: ' . $row[country] . '<br>' .
			'postal: ' . $row[postal] . '<br>';
		}
	}

	if ($email_response) {
		while($row = mysqli_fetch_array($email_response)) {
			echo 'email: ' . $row[email] . '<br>';
		}
	}

	if ($phone_response) {
		while($row = mysqli_fetch_array($phone_response)) {
			echo 'phoneNum: ' . $row[phoneNum] . '<br>' .
			'type: ' . $row[type] . '<br>';
		}
	}
}

// .............................................................................
//							       UPDATE
// .............................................................................


// .............................................................................
//							       DELETE
// .............................................................................


// =============================================================================
//							       ITEMS 
// =============================================================================

// .............................................................................
//							        GET
// .............................................................................

function getAllItems($calendarId) {

    global $conn;

    echo "<br> ******* Getting all items with calendarId = " . $calendarId . " ******* <br>";

    $query = "SELECT * FROM Items WHERE calendarId = $calendarId";
    $response = @mysqli_query($conn, $query);

    while($row = mysqli_fetch_assoc($response)){
    	echo "<br> itemId: " . $row["itemId"] . " name: " . $row["name"] . " createDate: " . $row["createDate"] . " note: " . ($row["note"] ? $row["note"] : "NULL") . " reminder: " . ($row["reminder"] ? $row["reminder"] : "NULL") . " type: " . $row["type"] . "<br>";
    }
 
}

// Queries for different types:
// - Event:     Join on Items and Events to return all events corresponding to a specific Calendar, 
//              ordered in ascending order by startDate.
// - Task:      Join on Items and Tasks to return all tasks corresponding to a specific Calendar,
//              ordered in ascending order by dueDate. 
// - Reminder:  Return all Reminders corresponding to a specific Calendar, ordered in ascending order by date.
// - Note:      Return all Notes corresponding to a specific Calendar, ordered in descending order by 
//              creationDate (newest one comes first)

function getAllItemsType($type, $calendarId){

	global $conn;

    echo "<br> ******* Getting all " . $type . " items with calendarId = " . $calendarId . " ******* <br>";

    switch ($type) {

    case 'event':

        $query = "SELECT * FROM Items I, EventItems E WHERE I.type = 'event' && I.itemId = E.itemId ORDER BY E.startDate ASC;";
        $response = @mysqli_query($conn, $query);

        while($row = mysqli_fetch_assoc($response)){
            echo "<br> itemId: " . $row["itemId"] . " name: " . $row["name"] . " createDate: " . $row["createDate"] . " note: " . ($row["note"] ? $row["note"] : "NULL") . " reminder: " . ($row["reminder"] ? $row["reminder"] : "NULL") . " type: " . $row["type"] . "<br>" . " startDate: " . $row["startDate"] . " endDate: " . $row["endDate"] . "<br>";
        }

        break;

    case "task":
        
        $query = "SELECT * FROM Items I, TaskItems E WHERE I.type = 'task' && I.itemId = E.itemId ORDER BY E.dueDate ASC;";
        $response = @mysqli_query($conn, $query);

        while($row = mysqli_fetch_assoc($response)){
            echo "<br> itemId: " . $row["itemId"] . " name: " . $row["name"] . " createDate: " . $row["createDate"] . " note: " . ($row["note"] ? $row["note"] : "NULL") . " reminder: " . ($row["reminder"] ? $row["reminder"] : "NULL") . " type: " . $row["type"] . "<br>" . " dueDate: " . $row["dueDate"] . " completionDate: " . $row["completionDate"] . "<br>";
        }

        break;

    case "reminder":
        
        $query = "SELECT * FROM Items WHERE type = 'reminder' ORDER BY createDate ASC;";
        $response = @mysqli_query($conn, $query);

        while($row = mysqli_fetch_assoc($response)){
            echo "<br> itemId: " . $row["itemId"] . " name: " . $row["name"] . " createDate: " . $row["createDate"] . " note: " . ($row["note"] ? $row["note"] : "NULL") . " reminder: " . ($row["reminder"] ? $row["reminder"] : "NULL") . " type: " . $row["type"] . "<br>";
        }

        break;

    case "note":

        $query = "SELECT * FROM Items WHERE type = 'note' ORDER BY createDate DESC;";
        $response = @mysqli_query($conn, $query);

        while($row = mysqli_fetch_assoc($response)){
            echo "<br> itemId: " . $row["itemId"] . " name: " . $row["name"] . " createDate: " . $row["createDate"] . " note: " . ($row["note"] ? $row["note"] : "NULL") . " reminder: " . ($row["reminder"] ? $row["reminder"] : "NULL") . " type: " . $row["type"] . "<br>";
        }

        break;

    default:
        echo "getAllItemsType has invalid $type parameter.";
    }

}

// .............................................................................
//							       UPDATE
// .............................................................................


// .............................................................................
//							       DELETE
// .............................................................................


// =============================================================================
//							       ACCOUNT
// =============================================================================


// .............................................................................
//							        GET
// .............................................................................


// .............................................................................
//							       UPDATE
// .............................................................................


// .............................................................................
//							       DELETE
// .............................................................................

?>
