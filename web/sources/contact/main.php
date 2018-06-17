<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = '';
$pageMode = '';

function showContactList($id) {
	$contacts = getContacts($id); // get contact list from DB

	// Contact List Display
	echo "<main>";
	echo "<section class='contacts'>"; // class='contacts'
	
	foreach($contacts as &$contact) {
		echo "<article>";
		echo "<h4>" . $contact["name"] . "</h4>";
		echo "</article>";
	}

	echo "</section>";
	echo "</main>";
}

if (!$loggedin) {
	echo "NOT LOGGED IN";
	header("Location: ?$profile=login");
} else {
	include('styles/header.php');

	// display contact list
	showContactList($id);

	if (isset($_GET["contact"]) && $_GET["contact"] === "addContact") {
		// handle add contact
		$name = trim($_POST["name"]);
		$birthday = trim($_POST["birthday"]) === "" ? null : trim($_POST["birthday"]); 

		$addresses = [];
		$emails = [];
		$phones = [];

		$addressIndex = 0;
		while (isset($_POST["street" . $addressIndex]) && trim($_POST["street" . $addressIndex]) !== "" &&
				isset($_POST["city" . $addressIndex]) && trim($_POST["city" . $addressIndex]) !== "" &&
				isset($_POST["state" . $addressIndex]) && trim($_POST["state" . $addressIndex]) !== "" &&
				isset($_POST["country" . $addressIndex]) && trim($_POST["country" . $addressIndex]) !== "" &&
				isset($_POST["postal" . $addressIndex]) && trim($_POST["postal" . $addressIndex])!== "") {
			// add info to array
			array_push($addresses, 
						array(
							"street" => trim($_POST["street" . $addressIndex]),
							"city" => trim($_POST["city" . $addressIndex]),
							"state" => trim($_POST["state" . $addressIndex]),
							"country" => trim($_POST["country" . $addressIndex]),
							"postal" => trim($_POST["postal" . $addressIndex])));
			$addressIndex++;
		}

		$emailIndex = 0;
		while (isset($_POST["email" . $emailIndex]) && trim($_POST["email" . $emailIndex]) !== "") {
			// add info to array
			array_push($emails, 
						array(
							"email" => trim($_POST["email" . $emailIndex])
						));
			$emailIndex++;
		}

		$phoneIndex = 0;
		while (isset($_POST["phone" . $phoneIndex]) && trim($_POST["phone" . $phoneIndex]) !== "") {
			// add info to array
			array_push($phones, 
						array(
							"phone" => trim($_POST["phone" . $phoneIndex]),
							"type" => trim($_POST["type" . $phoneIndex])
						));

			$phoneIndex++;
		}

		if (createContact($id, $name, $birthday, $addresses, $emails, $phones)) {
			// refresh page (redirect back to main contact page)
			header("Location: ?$profile=contact");
		} else {
			echo "Failed to add contact, please try again later. <br>";
		}
	}

	// display add contact section
	echo <<< _END
	<aside class="addContact">
		<form id="addContact" action="?$profile=contact&contact=addContact" method="post">
			<div class="field">
				<label for="name">Name</label>
				<input type="text" name="name" placeholder="name" required="required" maxlength="64">
			</div>
			<div class="field">
				<label for="birthday">Birthday</label>
				<input type="date" name="birthday" placeholder="birthday" maxlength="64">
			</div>
			<div class="field">
				<label for="street0">Street</label>
				<input type="text" name="street0" placeholder="street" required="required" maxlength="64">
				<label for="city0">City</label>
				<input type="text" name="city0" placeholder="city" required="required" maxlength="64">
				<label for="state0">State</label>
				<input type="text" name="state0" placeholder="state" required="required" maxlength="64">
				<label for="country0">Country</label>
				<input type="text" name="country0" placeholder="country" required="required" maxlength="64">
				<label for="postal0">Postal Code</label>
				<input type="text" name="postal0" placeholder="postal code" required="required" maxlength="7">
			</div>
			<div class="field">
				<label for="email0">Email</label>
				<input type="email" name="email0" placeholder="email" required="required" maxlength="64">
			</div>
			<div class="field">
				<label for="phone0">Phone</label>
				<input type="tel" name="phone0" placeholder="phone" required="required" maxlength="64">
				<label for="type0">Type</label>
				<select name="type0">
					<option value="home">Home</option>
					<option value="work">Work</option>
					<option value="evening">Evening</option>
					<option value="cell">Cell</option>
					<option value="iphone">iPhone</option>
					<option value="other">Other</option>
				</select>
			</div>
			<div>
		      <input type="submit" name="addContact" value="Add Contact" />
		    </div>
	    </form>
	</aside>
_END;

	include('styles/footer.php');
}

?>
