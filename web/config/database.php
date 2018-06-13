<?php

if (basename($_SERVER['PHP_SELF']) === 'database.php') {
    require_once('../403.php');
}

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

?>
