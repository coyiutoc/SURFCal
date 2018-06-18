<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = '';
$pageMode = '';

include_once('functions.php');

if (!$loggedin) {
	echo "NOT LOGGED IN";
	header("Location: ?$profile=login");
} else {
	include('styles/header.php');

	if (isset($_GET["contact"]) && $_GET["contact"] === "addContact") {
		// Must reassign to avoid global variable reassignment issues:
		$POST_RESULT = $_POST;

		// Add contact to DB: 
		handleAddContact($POST_RESULT);
	}

	if (isset($_GET["contact"]) && $_GET["contact"] === "contactDetail" && isset($_GET["contactId"])) {
		$details = getContactDetails($_GET["contactId"]); // get contact list from DB

		displayContactDetails($details);
	} else {
		// display contact list
		showContactList();

		// display add contact section
		displayAddContactSection();
	}

	echo <<< _END
        <script type="text/javascript">
        	let addressIndex = 0, emailIndex = 0, phoneIndex = 0;

        	const removeField = function(id) {
        		document.getElementById(id).remove();
        	};

        	const generateField = function(name, label, type, maxLen) {
        		return '<div class="field">' +
					'<label for="' + name + '">' + label + '</label>' +
					'<input type="' + type + '" name="' + name + '" placeholder="' + label + '" required="required" maxlength="' + maxLen + '">' +
					'</div>';
        	}

        	const addFieldSection = function(fieldType) {
        		let contactForm = document.getElementById('addContact');
	        	let addContactBtn = document.getElementById('addContactBtn');

	        	let addAddressBtn = document.getElementById('addAddress');
	        	let addEmailBtn = document.getElementById('addEmail');
	        	let addPhoneBtn = document.getElementById('addPhone');

        		let fieldDiv = document.createElement('div');
        		let id = "";
        		let field = "";

        		switch (fieldType) {
        			case "address":
        				id = 'address' + addressIndex;
        				field = [
							generateField("street" + addressIndex, "Street", "text", 64),
							generateField("city" + addressIndex, "City", "text", 64),
							generateField("state" + addressIndex, "State", "text", 64),
							generateField("country" + addressIndex, "Country", "text", 64),
							generateField("postal" + addressIndex, "Postal", "text", 64),
							'<button class="removeField" type="button" onclick="removeField(\'' + id + '\')">Remove</button>'
						].join('');

	        			addressIndex++;
        				break;
        			case "email":
        				id = 'email' + emailIndex;
        				field = [
        					generateField("email" + emailIndex, "Email", "text", 64),
        					'<button class="removeField" type="button" onclick="removeField(\'' + id + '\')">Remove</button>'
        				].join('');

		        		emailIndex++;
        				break;
        			case "phone":
        				id = 'phone' + phoneIndex;
        				field = [
        					generateField("phone" + phoneIndex, "Phone", "tel", 64),
        					'<div class="field">',
							'<label for="type' + phoneIndex + '">Type</label>',
							'<select name="type' + phoneIndex + '">',
							'<option value="home">Home</option>',
							'<option value="work">Work</option>',
							'<option value="evening">Evening</option>',
							'<option value="cell">Cell</option>',
							'<option value="iphone">iPhone</option>',
							'<option value="other">Other</option>',
							'</select>',
							'</div>',
							'<button class="removeField" type="button" onclick="removeField(\'' + id + '\')">Remove</button>'
						].join('');

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
