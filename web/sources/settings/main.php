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
	include('styles/footer.php');

	// get information associated with current user
	$accountInfo = getAccountByUser(user);
	echo("<script>console.log('PHP: $accountInfo');</script>");
	foreach($accountInfo as &$attribute) {
    		echo "<article>";
    		echo "<h4>" . attribute . "</h4>";
    		echo "</article>";
    	}
	// display form filled out with user information
	// have update button which updates user information (PUT)

	// get calendar information associated with current user
	// display form with calendar information
	// have update button which updates calendar information (PUT)

}

?>
