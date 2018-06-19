<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = 'Group';
$pageMode = ($loggedin ? '' : 'macro');

include('styles/header.php');

if ($loggedin) {
    if(isset($_GET['calendar']) && isset($_GET['mode']) && $_GET['mode'] === 'editCalendar') {
        // get calendarId
        $calendarId = $_GET['calendar'];
        $calendarInfo = getCalendar($calendarId);
        echo <<< _END
            <aside class="updateCalendar">
                <h2>Update Calendar</h2>
       		    <form id="updateCalendarInfo" action="?$profile=calendar&mode=updateCalendar" method="post">
        		    <div class="field">
                        <label for="name">Name</label>
                        <input type="text" name="name" value="$calendarInfo[name]" required="required" maxlength="64">
                    </div>
        		    <div class="field">
        			    <label for="description">Description</label>
        			    <input type="text" name="description" value="$calendarInfo[description]">
       			    </div>
        		    <div>
        		        <input type="submit" name="updateCalendarInfo" value="Update Calendar Info" />
        		    </div>
        	    </form>
            </aside>
_END;
    } else if (isset($_GET['calendar']) && isset($_GET['mode']) && $_GET['mode'] === 'updateCalendar') {
            $updatedCalendar = array();
            $calendarId = $_GET['calendar'];
            $name = trim($_POST["name"]);
            $description = trim($_POST["description"]);
            $updatedCalendar["name"] = $name;
            $updatedCalendar["description"] = $description;
            updateCalendar($Id, $calendarId, $updatedCalendar);
            header("location: ?$profile=calendar&calendar=$calendarId");
    } else {
        if(isset($_GET['calendar'])){
           		$isHomeCalendar = false;
           		include('sources/calendar/calendar_generator.php');
           	}
           	else{
           		global $profile;
        		global $id;
        		global $user;

        		$calendars = getAllCalendars($id); // get contact list from DB

        		// Contact List Display
        		echo "<main>";
        		echo "<h2>" . $user . "'s Calendars</h2>";
        		echo "<section class='items'>"; // class='calendar'

        		foreach($calendars as &$calendar) {
        			echo "<a href='?$profile=calendar&calendar=" . $calendar["calendarId"] . "'>";
        			echo "<article>";
        			echo "<h4>" . $calendar["name"] . "</h4>";
        			echo "</article>";
        			echo "</a>";
        		}

        		echo "</section>";
        		echo "</main>";
           	}
    }

} else {
    echo <<<_END
                    <main>
                        <section class="landing">
                            <h2>Welcome to SURFCal!</h2>
                            <p class="subtitle"></p>
                            <script>document.getElementsByClassName("subtitle")[0].innerHTML += twemoji.parse("🐿 🦄 🐰 🐠");</script>
                            <nav>
                                <a href="?$profile=login" class="login">Login</a>
                                <a href="?$profile=register">Sign Up</a>
                            </nav>
                        </section>
                    </main>
_END;
}

include('styles/footer.php');

?>
