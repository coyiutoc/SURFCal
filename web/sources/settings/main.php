<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = 'Settings';
$pageMode = '';

if(!$loggedin){
	header("Location: ?$profile=login");
}
else
{
	include('styles/header.php');

	if (isset($_GET["settings"]) && $_GET["settings"] === "updateUserInfo") {
    		// handle update user info
    		$name = trim($_POST["name"]);
    		$birthday = trim($_POST["birthday"]) === "" ? null : trim($_POST["birthday"]);
    		$email = trim($_POST["email"]);
    		$username = trim($_POST["username"]);

    		if (updateAccount($id, $name, $birthday, $email, $username)) {
    			// refresh page (redirect back to main contact page)
    			header("Location: ?$profile=settings");
    		} else {
    			echo "Failed to update user info, please try again later. <br>";
    		}
    	}

	// get information associated with current user
	$accountInfo = getAccountByUser($user);

	// display form filled out with user information
	echo <<< _END
    <aside class="updateUserInfo">
   		<form id="updateUserInfo" action="?$profile=settings&settings=updateUserInfo" method="post">
   			<div class="field">
   				<label for="username">Username</label>
    			<input type="text" name="username" value="$accountInfo[username]" required="required" maxlength="64">
    		</div>
    		<div class="field">
    			<label for="birthday">Birthday</label>
    			<input type="date" name="birthday" value="$accountInfo[birthday]" maxlength="64">
   			</div>
    		<div class="field">
                <label for="name">Name</label>
                <input type="text" name="name" value="$accountInfo[name]" required="required" maxlength="64">
            </div>
    		<div class="field">
    			<label for="email0">Email</label>
    			<input type="email" name="email0" value="$accountInfo[email]" required="required" maxlength="64">
    		</div>
    		<div>
    		   <input type="submit" name="updateUserInfo" value="Update User Info" />
    		</div>
    	</form>
    </aside>
_END;

	// get calendar information associated with current user
	// display form with calendar information
	// have update button which updates calendar information (PUT)

	include('styles/footer.php');

}

?>
