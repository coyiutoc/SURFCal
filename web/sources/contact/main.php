<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = '';
$pageMode = '';


if (!$loggedin) {
	echo "NOT LOGGED IN";
	header("Location: ?$profile=login");
} else {
	include('styles/header.php');

	if (isset($_GET["contact"]) && $_GET["contact"] === "addContact") {
		// handle add contact
		echo "Adding contact <br>";
		print_r (var_dump($_POST));

		$name= trim($_POST["name"]);
		$birthday= $_POST["birthday"] === "" ? null : $_POST["birthday"]; 
		// if (trim($_POST["city"]) !== "") {
		// 	echo "city is not empty";
		// } else {
		// 	echo "city is empty";
		// }

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
			array_push($addresses, array(trim($_POST["street" . $addressIndex]),
											trim($_POST["street" . $addressIndex]),
											trim($_POST["state" . $addressIndex]),
											trim($_POST["country" . $addressIndex]),
											trim($_POST["postal" . $addressIndex])));
			$addressIndex++;
		}

		$emailIndex = 0;
		while (isset($_POST["email" . $emailIndex]) && trim($_POST["email" . $emailIndex]) !== "") {
			// add info to array
			array_push($emails, array(trim($_POST["email" . $emailIndex])));
			$emailIndex++;
		}

		$phoneIndex = 0;
		while (isset($_POST["phone" . $phoneIndex]) && trim($_POST["phone" . $phoneIndex]) !== "" &&
				isset($_POST["type" . $phoneIndex]) && trim($_POST["type" . $phoneIndex]) !== "") {
			// add info to array
			array_push($phones, array(trim($_POST["phone" . $phoneIndex]),
										trim($_POST["type" . $phoneIndex])));
			$phoneIndex++;
		}

		createContact($id, $name, $birthday, $addresses, $emails, $phones);
	}

	// Main content.
	echo <<< _END
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
				<label for="street">Street</label>
				<input type="text" name="street" placeholder="street" maxlength="64">
				<label for="city">City</label>
				<input type="text" name="city" placeholder="city" maxlength="64">
				<label for="state">State</label>
				<input type="text" name="state" placeholder="state" maxlength="64">
				<label for="country">Country</label>
				<input type="text" name="country" placeholder="country" maxlength="64">
				<label for="postal">Postal Code</label>
				<input type="text" name="postal" placeholder="postal code" maxlength="7">
			</div>
			<div class="field">
				<label for="email">Email</label>
				<input type="text" name="email" placeholder="email" maxlength="64">
			</div>
			<div class="field">
				<label for="phone">Phone</label>
				<input type="text" name="phone" placeholder="phone" maxlength="64">
				<label for="type">Type</label>
				<select>
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
_END;

	include('styles/footer.php');
}

?>
