<?php

function showGroupList() {
	global $profile;
	global $id;
	global $user;

	$calendars = getAllCalendars($id); // get contact list from DB

	// Contact List Display
	echo "<main>";
	echo "<h2>" . $user . "'s Calendars</h2>";
	echo "<section class='items'>"; // class='calendar'
	
	foreach($calendars as &$calendar) {
		echo "<a href='?$profile=calendar&calendarId=" . $calendar["calendarId"] . "'>";
		echo "<article>";
		echo "<h4>" . $calendar["name"] . "</h4>";
		echo "</article>";
		echo "</a>";
	}

	echo "</section>";
	echo "</main>";
}

?>