<?php

// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// MAKE SURE TO EDIT:
// addItem endpoint is changing to:
// ?$profile=calendar&calendar=ID&addItem=true.
// 
// Make sure it is changed in the form divs too.
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = 'Calendar';
$pageMode = '';

include_once("functions.php");

if(!$loggedin){
	header("Location: ?$profile=login");
}
else
{	
	include('styles/header.php');

    // ------------ ENDPOINT HANDLING -------------------------------------------------- // 

    // addItem:
	if(isset($_GET['calendar']) && $_GET['calendar'] === 'addItem'){

		// ----------- debugging ------------------------------------
		// echo("<br> POST: <br>");
		// print_r ($_POST);
		// ----------- debugging ------------------------------------

        // Must reassign to avoid global variable reassignment issues:
        $POST_RESULT = $_POST;

        // Add item to DB: 
        handleAddItem($POST_RESULT);

	}	

    // editItem:
    if(isset($_GET['calendar']) && $_GET['calendar'] === 'editItem'){

        // ----------- debugging ------------------------------------
        // echo("<br> POST: <br>");
        // print_r ($_POST);
        // ----------- debugging ------------------------------------

        // Must reassign to avoid global variable reassignment issues:
        $POST_RESULT = $_POST;

        handleEditItem($POST_RESULT);
    }

    // removeItem:
    if(isset($_GET['calendar']) && $_GET['calendar'] === 'removeItem'
        && isset($_GET['id'])){

        handleDeleteItem($_GET['id']);
    }

    // ------------ HTML FOR CALENDAR HEADER ------------------------------------------- // 

    loadModalHTML();
    loadJS(); 

    // Retrieve Calendar data for header class = "calendar"
    $calendar_data = getCalendar($_SESSION['calId']);
    if ($calendar_data){
        $calendar_name = $calendar_data["name"];
    }
    else{
        $calendar_name = "Calendar";
        echo("!!!!!! Could not retrieve Calendar info. !!!!!!");
    }

    echo "<div class='main global'>";
        echo "<div class='container'>";
            echo "<main>";
                echo <<<_END
                    <header class="calendar">
                        <h2><i class="far fa-calendar-alt"></i> {$calendar_name} </h2>
                        <span class="actions">
                            <a class="edit" href="#"><i class="fas fa-edit"></i></a>
                            <a class="addMember" href="#"><i class="fas fa-users"></i></a>
                        </span>
                    </header>
_END;

    // ------------ HTML FOR CALENDAR BODY -------------------------------------------- // 

                    echo "<section class='items' id='items'>";

                    // Populate Events:
                    echo "<h3>Events</h3>";
                    $event_items = getItemsByType('event', $_SESSION['calId']);
                    if ($event_items){
                        itemsToHTML('event', $event_items);
                    }
                    else{
                        echo "<h6>No events have been added yet.</h6>";
                    }
                    echo "<div class='item_type_border'></div>";

                    // Populate Tasks:
                    echo "<h3>Tasks</h3>";
                    $task_items = getItemsByType('task', $_SESSION['calId']);
                    if ($task_items){
                        itemsToHTML('task', $task_items);
                    }
                    else{
                        echo "<h6>No tasks have been added yet.</h6>";
                    }
                    echo "<div class='item_type_border'></div>";


                    // Populate Reminders:
                    echo "<h3>Reminders</h3>";
                    $reminder_items = getItemsByType('reminder', $_SESSION['calId']);
                    if ($reminder_items){
                        itemsToHTML('reminder', $reminder_items);
                    }
                    else{
                        echo "<h6>No reminders have been added yet.</h6>";
                    }
                    echo "<div class='item_type_border'></div>";

                    // Populate Notes:
                    echo "<h3>Notes</h3>";
                    $note_items = getItemsByType('note', $_SESSION['calId']);
                    if ($note_items){
                        itemsToHTML('note', $note_items);
                    }
                    else{
                        echo "<h6>No notes have been added yet.</h6>";
                    }

                    echo "</section>";
            echo "</main>";
    
    // ------------ HTML FOR FORM ----------------------------------------------------- // 
            
            echo <<<_END
            <aside class="addItem">
                <h4>Add Item</h4>
                <form id="type" class="type" action="#">
                    <div class="radioField"><input type="radio" name="itemType" value="event" id="eventButton" checked="checked" onclick="javascript:typeCheck();""> Event</div>
                    <div class="radioField"><input type="radio" name="itemType" value="task" id="taskButton" onclick="javascript:typeCheck();""> Task</div>
                    <div class="radioField"><input type="radio" name="itemType" value="reminder" id="reminderButton" onclick="javascript:typeCheck();""> Reminder</div>
                    <div class="radioField"><input type="radio" name="itemType" value="note" id="noteButton" onclick="javascript:typeCheck();"> Note</div>
                </form>
                <div id="eventBlock" style="display:block">
                    <form id="event" action="?$profile=calendar&calendar=addItem" method="post">
                        <input type="hidden" name="type" value="event"/>
                        <div class="field"><label for="name">Name</label><input type="text" name="name" placeholder="Name" required="required" maxlength="64"></div>
                        <div class="field"><label for="start">Start</label><input type="datetime-local" name="start_date" placeholder="Start Date" required="required" value="start_date"></div>
                        <div class="field"><label for="end">End</label><input type="datetime-local" name="end_date" placeholder="End Date" value = "end_date"></div>
                        <div class="field"><label for="reminder">Reminder</label><input type="datetime-local" name="reminder" placeholder="Reminder"></div>
                        <div class="field"><label for="note">Note</label><input type="text" name="note" placeholder="Note" maxlength="1024" class="note"></div>
                        <div class="field"><label for="location">Location</label><input type="text" name="location" placeholder="Location" maxlength="256"></div>
                        <div class="field">
                            <label for="colour">Colour</label>
                            <select name="colour">
                                <option value="0" class="grey">Grey</option>
                                <option value="1" class="red">Red</option>
                                <option value="2" class="orange">Orange</option>
                                <option value="3" class="yellow">Yellow</option>
                                <option value="4" class="green">Green</option>
                                <option value="5" class="blue">Blue</option>
                                <option value="6" class="purple">Purple</option>
                                <option value="7" class="black">Black</option>
                            </select>
                        </div>
                        <div class="field"><input type="submit" value="Add" class="button"></div>
                    </form>
                </div>
                <div id="taskBlock" style="display:none">
                    <form id="task" action="?$profile=calendar&calendar=addItem" method="post">
                        <input type="hidden" name="type" value="task"/>
                        <div class="field"><label for="name">Name</label><input type="text" name="name" placeholder="Name" required="required" maxlength="64"></div>
                        <div class="field"><label for="due">Due</label><input type="datetime-local" name="due_date" placeholder="Due Date" required="required" value="due_date"></div>
                        <div class="field"><label for="completion">Completion</label><input type="datetime-local" name="completion_date" placeholder="Completion Date" value="completion_date"></div>
                        <div class="field"><label for="reminder">Reminder</label><input type="datetime-local" name="reminder" placeholder="Reminder"></div>
                        <div class="field"><label for="note">Note</label><input type="text" name="note" placeholder="Note" maxlength="1024" class="note"></div>
                        <div class="field"><label for="location">Location</label><input type="text" name="location" placeholder="Location" maxlength="256"></div>
                        <div class="field">
                            <label for="colour">Colour</label>
                            <select name="colour">
                                <option value="0" class="grey">Grey</option>
                                <option value="1" class="red">Red</option>
                                <option value="2" class="orange">Orange</option>
                                <option value="3" class="yellow">Yellow</option>
                                <option value="4" class="green">Green</option>
                                <option value="5" class="blue">Blue</option>
                                <option value="6" class="purple">Purple</option>
                                <option value="7" class="black">Black</option>
                            </select>
                        </div>
                        <div class="field"><input type="submit" value="Add" class="button"></div>
                    </form>
                </div>
                <div id="reminderBlock" style="display:none">
                    <form id="reminder" action="?$profile=calendar&calendar=addItem" method="post">
                        <input type="hidden" name="type" value="reminder"/>
                        <div class="field"><label for="name">Name</label><input type="text" name="name" placeholder="Name" required="required" maxlength="64"></div>
                        <div class="field"><label for="reminder">Reminder</label><input type="datetime-local" name="reminder" placeholder="Reminder" required="required"></div>
                        <div class="field"><label for="note">Note</label><input type="text" name="note" placeholder="Note" maxlength="1024" class="note"></div>
                        <div class="field"><label for="location">Location</label><input type="text" name="location" placeholder="Location" maxlength="256"></div>
                        <div class="field">
                            <label for="colour">Colour</label>
                            <select name="colour">
                                <option value="0" class="grey">Grey</option>
                                <option value="1" class="red">Red</option>
                                <option value="2" class="orange">Orange</option>
                                <option value="3" class="yellow">Yellow</option>
                                <option value="4" class="green">Green</option>
                                <option value="5" class="blue">Blue</option>
                                <option value="6" class="purple">Purple</option>
                                <option value="7" class="black">Black</option>
                            </select>
                        </div>
                        <div class="field"><input type="submit" value="Add" class="button"></div>
                    </form>
                </div>
                <div id="noteBlock" style="display:none">
                    <form id="note" action="?$profile=calendar&calendar=addItem" method="post">
                        <input type="hidden" name="type" value="note"/>
                        <div class="field"><label for="name">Name</label><input type="text" name="name" placeholder="Name" required="required" maxlength="64"></div>
                        <div class="field"><label for="reminder">Reminder</label><input type="datetime-local" name="reminder" placeholder="Reminder"></div>
                        <div class="field"><label for="note">Note</label><input type="text" name="note" placeholder="Note" maxlength="1024" class="note"></div>
                        <div class="field"><label for="location">Location</label><input type="text" name="location" placeholder="Location" maxlength="256"></div>
                        <div class="field">
                            <label for="colour">Colour</label>
                            <select name="colour">
                                <option value="0" class="grey">Grey</option>
                                <option value="1" class="red">Red</option>
                                <option value="2" class="orange">Orange</option>
                                <option value="3" class="yellow">Yellow</option>
                                <option value="4" class="green">Green</option>
                                <option value="5" class="blue">Blue</option>
                                <option value="6" class="purple">Purple</option>
                                <option value="7" class="black">Black</option>
                            </select>
                        </div>
                        <div class="field"><input type="submit" value="Add" class="button"></div>
                    </form>
                </div>
            </aside>
_END;

    // ------------ END HTML FOR FORM -------------------------------------------------- // 

    echo "</div><!-- .container ends -->";
echo "</div>";

	include('styles/footer.php');
}

?>
