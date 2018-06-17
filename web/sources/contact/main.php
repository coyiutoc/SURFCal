<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = '';
$pageMode = '';

function showContactList($id, $user) {
	$contacts = getContacts($id); // get contact list from DB

	// Contact List Display
	echo "<main>";
	echo "<h2>" . $user . "'s Contacts</h2>";
	echo "<section class='items'>"; // class='contacts'
	
	foreach($contacts as &$contact) {
		echo "<article onclick=\"\">";
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
	showContactList($id, $user);

	if (isset($_GET["contact"]) && $_GET["contact"] === "addContact") {
		// handle add contact
		$name = trim($_POST["name"]);
		$birthday = trim($_POST["birthday"]) === "" ? null : trim($_POST["birthday"]); 

		$addresses = [];
		$emails = [];
		$phones = [];
		$validInput = true;

		foreach($_POST as $key => $value) {
			$index = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
			$field = preg_replace('/\d+/u', '', $key);
			$value = trim($value) === "" ? NULL : trim($value);

			if (!isset($value)) {
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

	// display add contact section
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

			<button id="addAddress" type="button" onclick="addField('address')">Add Address</button>
			<button id="addEmail" type="button" onclick="addField('email')">Add Email</button>
			<button id="addPhone" type="button" onclick="addField('phone')">Add Phone</button>
			
			<br>
	      	<input id="addContactBtn" type="submit" name="addContact" value="Add Contact" />
	    </form>
	</aside>
_END;

	echo <<< _END
        <script type="text/javascript">
        	let addressIndex = 0, emailIndex = 0, phoneIndex = 0;


        	const removeField = function(id) {
        		document.getElementById(id).remove();
        	};

        	const addField = function(fieldType) {
        		let contactForm = document.getElementById('addContact');
	        	let addContactBtn = document.getElementById('addContactBtn');

	        	let addAddressBtn = document.getElementById('addAddress');
	        	let addEmailBtn = document.getElementById('addEmail');
	        	let addPhoneBtn = document.getElementById('addPhone');

        		let fieldDiv = document.createElement('div');
        		fieldDiv.setAttribute("class", "field");
        		let id = "";
        		let field = "";

        		switch (fieldType) {
        			case "address":
        				id = 'address' + addressIndex;
        				field = [
        					'<button class="removeField" type="button" onclick="removeField(\'' + id + '\')">-</button>',
							'<label for="street' + addressIndex + '">Street</label>',
							'<input type="text" name="street' + addressIndex + '" placeholder="street" required="required" maxlength="64">',
							'<label for="city' + addressIndex + '">City</label>',
							'<input type="text" name="city' + addressIndex + '" placeholder="city" required="required" maxlength="64">',
							'<label for="state' + addressIndex + '">State</label>',
							'<input type="text" name="state' + addressIndex + '" placeholder="state" required="required" maxlength="64">',
							'<label for="country' + addressIndex + '">Country</label>',
							'<input type="text" name="country' + addressIndex + '" placeholder="country" required="required" maxlength="64">',
							'<label for="postal' + addressIndex + '">Postal Code</label>',
							'<input type="text" name="postal' + addressIndex + '" placeholder="postal code" required="required" maxlength="7">'].join('');
	        			addressIndex++;
        				break;
        			case "email":
        				id = 'email' + emailIndex;
        				field = [
        					'<button class="removeField" type="button" onclick="removeField(\'' + id + '\')">-</button>',
		        			'<label for="email' + emailIndex + '">Email</label>',
		        			'<input type="email" name="email' + emailIndex + '" placeholder="email" required="required" maxlength="64">'].join('');
		        		emailIndex++;
        				break;
        			case "phone":
        				id = 'phone' + phoneIndex;
        				field = [
        					'<button class="removeField" type="button" onclick="removeField(\'' + id + '\')">-</button>',
							'<label for="phone' + phoneIndex + '">Phone</label>',
							'<input type="tel" name="phone' + phoneIndex + '" placeholder="phone" required="required" maxlength="64">',
							'<label for="type' + phoneIndex + '">Type</label>',
							'<select name="type' + phoneIndex + '">',
							'<option value="home">Home</option>',
							'<option value="work">Work</option>',
							'<option value="evening">Evening</option>',
							'<option value="cell">Cell</option>',
							'<option value="iphone">iPhone</option>',
							'<option value="other">Other</option>',
							'</select>'].join('');
						id = 'phone' + phoneIndex;
		        		phoneIndex++;
		        		break;
		        	default:
		        		break;
        		}

        		fieldDiv.setAttribute("id", id);
				fieldDiv.innerHTML = field;

        		contactForm.insertBefore(fieldDiv, addContactBtn);
        	};
        </script>
_END;
	include('styles/footer.php');
}

?>
