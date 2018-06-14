<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

if (basename($_SERVER['PHP_SELF']) === 'database.php') {
    require_once('../403.php');
}

$host = 'localhost';
$user = 'root';
$pass = '__';
$schema = 'surfcal';

$conn = new mysqli($host, $user, $pass, $schema);

if (!$conn) {
    // die('Connection Error: ' . mysqli_connect_error());
    require_once('../500.php');
}
echo "Connection success";
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


// .............................................................................
//							       DELETE
// .............................................................................


// =============================================================================
//							       ACCOUNT
// =============================================================================


// .............................................................................
//							        GET
// .............................................................................

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
