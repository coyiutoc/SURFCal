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

	// get information associated with current user
	$accountInfo = getAccountByUser($user);

	// display form filled out with user information
	echo <<< _END
    	<aside class="userInfo">
    		<form id="userInfo" action="?$profile=contact&contact=addContact" method="post">
    			<div class="field">
    				<label for="username">Username</label>
    				<input type="text" name="username" placeholder="Username" required="required" maxlength="64">
    			</div>
    			<div class="field">
    				<label for="birthday">Birthday</label>
    				<input type="date" name="birthday" placeholder="birthday" maxlength="64">
    			</div>
    			<div class="field">
                    <label for="name">Name</label>
                    <input type="text" name="name" placeholder="name" required="required" maxlength="64">
                </div>
    			<div class="field">
    				<label for="email0">Email</label>
    				<input type="email" name="email0" placeholder="email" required="required" maxlength="64">
    			</div>
    			<div>
    		      <input type="submit" name="updateContact" value="Update Contact" />
    		    </div>
    	    </form>
    	</aside>
    _END;
	include('styles/footer.php');
	// have update button which updates user information (PUT)

	// get calendar information associated with current user
	// display form with calendar information
	// have update button which updates calendar information (PUT)

}

?>
