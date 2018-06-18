<?php

function showContactList() {
	global $profile;
	global $id;
	global $user;

	$contacts = getContacts($id); // get contact list from DB

	// Contact List Display
	echo "<main>";
	echo "<h2>" . $user . "'s Contacts</h2>";
	echo "<section class='items'>"; // class='contacts'
	
	foreach($contacts as &$contact) {
		echo "<a href='?$profile=contact&contact=contactDetail&contactId=" . $contact["contactId"] . "'>";
		echo "<article>";
		echo "<h4>" . $contact["name"] . "</h4>";
		echo "</article>";
		echo "</a>";
	}

	echo "</section>";
	echo "</main>";
}

function displayContactDetails($details) {
	global $profile;

	// Parse details
	$name = "";
	$birthday = "";
	$addresses = [];
	$emails = [];
	$phones = [];

	foreach($details as &$detail) {
		if (isset($detail["name"])) $name = $detail["name"];
		if (isset($detail["birthday"])) $birthday = $detail["birthday"];

		if (isset($detail["streetField"])) {
			array_push($addresses, $detail);
		} else if (isset($detail["email"])) {
			array_push($emails, $detail);
		} else if (isset($detail["phone"])) {
			array_push($phones, $detail);
		}
	}

	echo '<div class="contactDetail">';
	echo '<a id="backToContacts" href="?'. $profile . '=contact" style="display: block;">< back to contacts</a>';

	// Display details: 

	// Name
	echo '<h2 id="contactName">' . $name . '</h2>';
	// Birthday
	if ($birthday !== "") {
		echo '<span class="infoField">';
		echo '<label>Birthday</label>  ' . $birthday;
		echo '</span>';
	}

	// Address(es)
	if (count($addresses) > 0) {
		echo '<h4>Address</h4>';
		foreach($addresses as &$address) {
			echo '<div class="contactInfoSection">';
			generateInfoField($address);
			echo '</div>';
		}
	}

	// Email(s)
	if (count($emails) > 0) {
		echo '<h4>Email</h4>';
		foreach($emails as &$email) {
			echo '<div class="contactInfoSection">';
			generateInfoField($email);
			echo '</div>';
		}
	}

	// Phone(s)
	if (count($phones) > 0) {
		echo '<h4>Phone</h4>';
		foreach($phones as &$phone) {
			echo '<div class="contactInfoSection">';
			generateInfoField($phone);
			echo '</div>';
		}
	}

	echo '</div>';
}

function generateInfoField($info) {
	foreach($info as $key => $value) {
		echo '<span class="infoField">';
		echo '<label>' . infoKeyToLabel($key) . '</label>  ' . $value;
		// echo '<p>' . $value . '</p>';
		echo '</span>';
	}
}

function infoKeyToLabel($key) {
	$label = "";

	switch ($key) {
		case "streetField":
			$label = "Street";
			break;
		case "city":
			$label = "City";
			break;
		case "state_":
			$label = "State";
			break;
		case "country":
			$label = "Country";
			break;
		case "postal":
			$label = "Postal";
			break;
		case "email":
			$label = "Email";
			break;
		case "phoneNum":
			$label = "Phone";
			break;
		case "type":
			$label = "Type";
			break;
		default:
			break;
	}

	return $label;
}

function handleAddContact($POST_RESULT) {
	global $profile;
	global $id;

	$name = trim($POST_RESULT["name"]);
	$birthday = trim($POST_RESULT["birthday"]) === "" ? null : trim($POST_RESULT["birthday"]); 

	$addresses = [];
	$emails = [];
	$phones = [];
	$validInput = true;

	foreach($POST_RESULT as $key => $value) {
		$index = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
		$field = preg_replace('/\d+/u', '', $key);
		$value = trim($value) === "" ? NULL : trim($value);

		if (!isset($value) && $key !== "birthday") {
			$validInput = false;
			break;
		}

		if ($field === "street" || $field === "city" || $field === "state" || $field === "country" || $field === "postal") {
			if (!isset($addresses[$index])) {
				$addresses[$index] = [];
			}
			$addresses[$index][$field] = $value; 
		}
		if ($field === "email") {
			if (!isset($emails[$index])) {
				$emails[$index] = [];
			}
			$emails[$index][$field] = $value; 
		}
		if ($field === "phone" || $field === "type") {
			if (!isset($phones[$index])) {
				$phones[$index] = [];
			}
			$phones[$index][$field] = $value; 
		}
	}

	if (!$validInput) {
		$message = "Some inputs are invalid, please try again";
		echo "<script type='text/javascript'>alert('$message');</script>";
	} else {
		if (createContact($id, $name, $birthday, $addresses, $emails, $phones)) {
			// refresh page (redirect back to main contact page)
			header("Location: ?$profile=contact");
		} else {
			$message = "Some fields were not added, please try again later.";
			echo "<script type='text/javascript'>alert('$message');</script>";
		}
	}
}

function handleRemoveContact($contactId) {
	global $profile;

	echo "<script type='text/javascript'>alert('$contactId');</script>";
	if (deleteContact($contactId)) {
		// refresh page (redirect back to main contact page)
		header("Location: ?$profile=contact");
	} else {
		$message = "Error occurred, please try again later.";
		echo "<script type='text/javascript'>alert('$message');</script>";
		// header("Location: ?$profile=contact");
	}
}

function displayAddContactSection() {
	global $profile;

	echo <<< _END
		<aside class="addItem">
			<h4>Add Contact</h4>
			<form id="addContact" action="?$profile=contact&contact=addContact" method="post">
				<div class="field">
					<label for="name">Name</label>
					<input type="text" name="name" placeholder="name" required="required" maxlength="64">
				</div>
				<div class="field">
					<label for="birthday">Birthday</label>
					<input type="date" name="birthday" placeholder="birthday" maxlength="64">
				</div>

				<button id="addAddress" type="button" onclick="addFieldSection('address')">Add Address</button>
				<button id="addEmail" type="button" onclick="addFieldSection('email')">Add Email</button>
				<button id="addPhone" type="button" onclick="addFieldSection('phone')">Add Phone</button>
				
				<br>
		      	<input id="addContactBtn" type="submit" name="addContact" value="Add Contact" />
		    </form>
		</aside>
_END;
}

function displayDeleteContactOption($contactId) {
	global $profile;
	echo '<a id="deleteContact" href="?' . $profile . '=contact&contact=deleteContact&contactId=' . $contactId . '">Delete Contact</a>';
}

?>