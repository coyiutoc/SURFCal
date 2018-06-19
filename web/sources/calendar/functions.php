<?php

$COLOURS = array(0 => "grey",
				 1 => "red",
				 2 => "orange",
				 3 => "yellow",
				 4 => "green",
				 5 => "blue",
				 6 => "purple",
				 7 => "black");

$profile = 'SURFCal'; //Need better way to get from config/properties.php

function generateAlert($string, $is_positive){
	echo "
			<div class=\"" . ($is_positive ? "positive_alert" : "negative_alert") . "\">
			  	<span class=\"closebtn\" onclick=\"this.parentElement.style.display='none';\"><i class=\"fas fa-times\"></i></span> 
			  	<strong>" . ($is_positive ? "Success!" : "Error!") . "</strong> $string
			</div>
";
}
/**
 *	Adds the new item to the DB.
 *	@param array $POST_RESULT				
 */
function handleAddItem($calendarId, $POST_RESULT){

	if ($POST_RESULT['type'] === 'event'){
		$options = array("start_date" 		 => $_POST["start_date"] ? $_POST["start_date"] : NULL,
						 "end_date"   		 => $_POST["end_date"] ? $_POST["end_date"] : NULL);
	}
	else if ($POST_RESULT['type'] === 'task'){
		$options = array("due_date" 		 => $_POST["due_date"] ? $_POST["due_date"] : NULL,
						 "completion_date"   => $_POST["completion_date"] ? $_POST["completion_date"] : NULL);
	}
	else{
		$options = NULL;
	}

	// If item successfully created: 
	if (createItem($calendarId, $_SESSION['id'], 
			   	   $POST_RESULT['name'] ? $POST_RESULT['name'] : NULL, 
			   	   $POST_RESULT['note'] ? $POST_RESULT['note'] : NULL, 
			   	   $POST_RESULT['reminder'] ? $POST_RESULT['reminder'] : NULL, 
			   	   $POST_RESULT['type'] ? $POST_RESULT['type'] : NULL, 
			   	   $POST_RESULT['colour'] ? $POST_RESULT['colour'] : NULL, 
			   	   $POST_RESULT['location'] ? $POST_RESULT['location'] : NULL, 
			   	   $options)){
	
		generateAlert("Item was successfully added!", true);
	}
	else { 
		generateAlert("Item could not be added!", false); 
	}
}

/**
 *	Updates the item in the DB.
 *	@param array $POST_RESULT				
 */
function handleEditItem($calendarId, $POST_RESULT){

	if ($POST_RESULT['type'] === 'event'){
		$options = array("startDate" 		=> $_POST["start_date"] ? $_POST["start_date"] : NULL,
						 "endDate"   		=> $_POST["end_date"] ? $_POST["end_date"] : NULL);
	}
	else if ($POST_RESULT['type'] === 'task'){
		$options = array("dueDate" 		 	=> $_POST["due_date"] ? $_POST["due_date"] : NULL,
						 "completionDate"   => $_POST["completion_date"] ? $_POST["completion_date"] : NULL);
	}
	else{
		$options = array();
	}

    if (editItem($_SESSION['id'], $calendarId, $POST_RESULT['editedItemId'], $POST_RESULT['type'], 
    	$POST_RESULT['name'], $POST_RESULT['note'], $POST_RESULT['reminder'], $POST_RESULT['location'], 
    	$POST_RESULT['colour'], $options)){

    	generateAlert("Item was successfully edited!", true);
	}
	else { 
		generateAlert("Item could not be edited!", false); 
	}
}

/**
 *	Deletes item from the DB.
 *	@param integer $itemId 			
 */
function handleDeleteItem($itemId){

	if (deleteItem($itemId)){
    	generateAlert("Item was successfully deleted!", true);
	}
	else { 
		generateAlert("Item could not be deleted!", false); 
	}
}

/**
 *	Generates the HTML for each item 
 *	@param string $type 	must be: 'event', 'task', 'note' or 'reminder'
 *  @param array $items 
 * 	@return HTML				
 */
function itemsToHTML($type, $items, $calendarId){
	if ($type !== 'event' && $type !== 'task' && $type !== 'note'
		&& $type !== 'reminder'){
			echo "!!!!! Incorrect type input. !!!!!!";
	}
	else {

		foreach($items as &$item) {

			global $COLOURS;
			global $profile;

			$itemId = $item["itemId"];
	        $name = $item["name"];
	        $description = $item["note"];
	        $colour = isset($item["colour"]) ? $COLOURS[$item["colour"]] : 0;

	        if ($type === 'event'){
	        	$timeText = "[ Starts ] ";
		        $beginningDate = substr($item["startDate"], 0, -8);
		        $beginningHour = date('H:i',strtotime($item["startDate"]));
		        $endingDate = $item["endDate"];
		        $endingHour = date('H:i',strtotime($endingDate));
		    }
	        else if ($type === 'task'){
	        	$timeText = "[ Due ] ";
	        	$beginningDate = substr($item["dueDate"], 0, -8);
		        $beginningHour = date('H:i',strtotime($item["dueDate"]));
		        $endingDate = $item["completionDate"];
		        $endingHour = date('H:i',strtotime($endingDate));
	        }
	        else if ($type === 'reminder' || $type === 'note'){
				$timeText = "[ Created ] ";
	        	$beginningDate = $item["createDate"];
		        $beginningHour = NULL;
		        $endingDate = NULL;
		        $endingHour = NULL;
	        }

	        echo "<h6>{$timeText} {$beginningDate}</h6>";
	        echo "<article class=$colour>";
	            echo "<div class ='time'>";
	                echo "<time class='start' datetime=$beginningDate'>{$beginningHour}</time>";
	                echo "<time class='end' datetime=$endingDate>{$endingHour}</time>";
	            echo "</div>";
	            echo "<div class='indicator'><i class='far fa-calendar'></i></div>";
	            echo "<div class='itemInfo'>";
	                echo "<h5>{$name}</h5>";
	                echo "<p>{$description}</p>";
	            echo "</div>";

	            $itemEncoded = json_encode($item);
	            // Icon for editing:
                echo "<a class='edit'><i id='editButton' class='far fa-edit' align='right' onclick='javascript:openModal($itemEncoded);'></i></a>";
	            // Icon for removing:
                echo "<a class='remove' href='?$profile=calendar&calendar=$calendarId&mode=removeItem&id=$itemId'><i class='far fa-minus-square' align='right' style='padding-left: 5px''></i></a>";
             
	        echo "</article>";

	    } // end for each loop
	} // end if ($type !== 'event' && $type !== 'task')
}

/**
 *	Generates the HTML for the edit modal.	
 */
function loadModalHTML($calendarId){

	global $profile;

    echo <<<_END

    	<script type="text/javascript">

    		// We need to the text input fields back into date-time upon focus:
            var date_times = document.getElementsByClassName('date-time');
            for (d in date_times){
                d.onfocus = function (event) {
                this.type = 'datetime-local';
                this.focus();
                }
            }

        </script>

        <div id="editModal" class="modal">
          <div class="modalContent">
            <span class="close">&times;</span>

            <h4 style="padding-bottom: 10px;"><div id="editItemTitle"></div></h4>
                <div id="modalEventBlock" style="display:none">
                    <form id="event" action="?$profile=calendar&calendar=$calendarId&mode=editItem" method="post">
                        <input type="hidden" name="type" value="event"/>
                        <input id="eventItemId" type="hidden" name="editedItemId"/>
                        <div class="field"><label for="name">Name</label><input id="eventNameInput" type="text" name="name" placeholder="Name" maxlength="64"></div>
                        <div class="field"><label for="start">Start</label><input class="date-time" id ="eventStartInput" type="text" name="start_date"></div>
                        <div class="field"><label for="end">End</label><input class="date-time" id="eventEndInput" type="text" name="end_date"></div>
                        <div class="field"><label for="reminder">Reminder</label><input class="date-time" id="eventReminderInput" type="text" name="reminder"></div>
                        <div class="field"><label for="note">Note</label><input id="eventNoteInput" type="text" name="note" maxlength="1024" class="note"></div>
                        <div class="field"><label for="location">Location</label><input id="eventLocationInput" type="text" name="location" maxlength="256"></div>
                        <div class="field">
                            <label for="colour">Colour</label>
                            <select name="colour" id="eventColourInput">
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
                        <div class="field"><input type="submit" value="Edit" class="button"></div>
                    </form>
                </div>
                <div id="modalTaskBlock" style="display:none">
                    <form id="task" action="?$profile=calendar&calendar=$calendarId&mode=editItem" method="post">
                        <input type="hidden" name="type" value="task"/>
                        <input id="taskItemId" type="hidden" name="editedItemId"/>
                        <div class="field"><label for="name">Name</label><input id="taskNameInput" type="text" name="name" maxlength="64"></div>
                        <div class="field"><label for="due">Due</label><input class="date-time" id="taskDueInput" type="text" name="due_date"></div>
                        <div class="field"><label for="completion">Completion</label><input class="date-time" id="taskCompletionInput" type="text" name="completion_date"></div>
                        <div class="field"><label for="reminder">Reminder</label><input class="date-time" id="taskReminderInput" type="text" name="reminder" "></div>
                        <div class="field"><label for="note">Note</label><input id="taskNoteInput" type="text" name="note" maxlength="1024" class="note"></div>
                        <div class="field"><label for="location">Location</label><input id="taskLocationInput" type="text" name="location" maxlength="256"></div>
                        <div class="field">
                            <label for="colour">Colour</label>
                            <select name="colour" id="taskColourInput">
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
                        <div class="field"><input type="submit" value="Edit" class="button"></div>
                    </form>
                </div>
                <div id="modalReminderBlock" style="display:none">
                    <form id="reminder" action="?$profile=calendar&calendar=$calendarId&mode=editItem" method="post">
                        <input type="hidden" name="type" value="reminder"/>
                        <input id="reminderItemId" type="hidden" name="editedItemId"/>
                        <div class="field"><label for="name">Name</label><input id="reminderNameInput" type="text" name="name" maxlength="64"></div>
                        <div class="field"><label for="reminder">Reminder</label><input class="date-time" id="reminderReminderInput" type="text" name="reminder"></div>
                        <div class="field"><label for="note">Note</label><input id="reminderNoteInput" type="text" name="note" maxlength="1024" class="note"></div>
                        <div class="field"><label for="location">Location</label><input id="reminderLocationInput" type="text" name="location" maxlength="256"></div>
                        <div class="field">
                            <label for="colour">Colour</label>
                            <select name="colour" id="reminderColourInput">
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
                        <div class="field"><input type="submit" value="Edit" class="button"></div>
                    </form>
                </div>
                <div id="modalNoteBlock" style="display:none">
                    <form id="note" action="?$profile=calendar&calendar=$calendarId&mode=editItem" method="post">
                        <input type="hidden" name="type" value="note"/>
                        <input id="noteItemId" type="hidden" name="editedItemId"/>
                        <div class="field"><label for="name">Name</label><input id="noteNameInput" type="text" name="name" maxlength="64"></div>
                        <div class="field"><label for="reminder">Reminder</label><input class="date-time" id="noteReminderInput" type="text" name="reminder"></div>
                        <div class="field"><label for="note">Note</label><input id="noteNoteInput" type="text" name="note" maxlength="1024" class="note"></div>
                        <div class="field"><label for="location">Location</label><input id="noteLocationInput" type="text" name="location" maxlength="256"></div>
                        <div class="field">
                            <label for="colour">Colour</label>
                            <select name="colour" id="noteColourInput">
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
                        <div class="field"><input type="submit" value="Edit" class="button"></div>
                    </form>
                </div>

          </div>
        </div>
_END;
}

/**
 *	Generates JS needed for the calendar.			
 */
function loadJS(){
	echo <<<_END
		<script type="text/javascript">

			// Modal related --------------------------------------------------------

			// Get the modal
			var modal = document.getElementById('editModal');
			console.log(modal);

			// Get the button that opens the modal
			var btn = document.getElementById("editButton");

			// Get the <span> element that closes the modal
			var span = document.getElementsByClassName("close")[0];

			// Will contain JSON of info of the item 
			// w/ the open edit modal 
			var item;

			function setPlaceHolders(){

				// Common to all types:
				var type_name;
				var name_element;
				var note_element;
				var reminder_element;
				var location_element;
				var colour_element;

				switch(item["type"]) {
				    case 'event':
				    	id_element = document.getElementById("eventItemId");
				        name_element = document.getElementById("eventNameInput");
						note_element = document.getElementById("eventNoteInput");
						reminder_element = document.getElementById("eventReminderInput");
						location_element = document.getElementById("eventLocationInput");
						colour_element = document.getElementById("eventColourInput");

						if (item["startDate"]){
							document.getElementById("eventStartInput").placeholder = (item["startDate"] ? item["startDate"] : null);
						}
						if (item["endDate"]){
							document.getElementById("eventEndInput").placeholder = (item["endDate"] ? item["endDate"] : null);
						}
						else {document.getElementById("eventEndInput").placeholder = null;}

				        break;

				    case 'task':
				    	id_element = document.getElementById("taskItemId");
				        name_element = document.getElementById("taskNameInput");
						note_element = document.getElementById("taskNoteInput");
						reminder_element = document.getElementById("taskReminderInput");
						location_element = document.getElementById("taskLocationInput");
						colour_element = document.getElementById("taskColourInput");

						if (item["dueDate"]){
							document.getElementById("taskDueInput").placeholder = (item["dueDate"] ? item["dueDate"] : null);
						}
						if (item["completionDate"]){
							document.getElementById("taskCompletionInput").placeholder = (item["completionDate"] ? item["completionDate"] : null);
						}
						else {document.getElementById("taskCompletionInput").placeholder = null;}

				        break;

				    case 'reminder':
				    	id_element = document.getElementById("reminderItemId");
				    	name_element = document.getElementById("reminderNameInput");
						note_element = document.getElementById("reminderNoteInput");
						reminder_element = document.getElementById("reminderReminderInput");
						location_element = document.getElementById("reminderLocationInput");
						colour_element = document.getElementById("reminderColourInput");

				    	break;

				    case 'note':
				    	id_element = document.getElementById("noteItemId");
				    	name_element = document.getElementById("noteNameInput");
						note_element = document.getElementById("noteNoteInput");
						reminder_element = document.getElementById("noteReminderInput");
						location_element = document.getElementById("noteLocationInput");
						colour_element = document.getElementById("noteColourInput");

				    	break;
				}

				type_name = item["type"];
				document.getElementById("editItemTitle").innerHTML = "Edit " + type_name.charAt(0).toUpperCase() + type_name.slice(1);

				id_element.value = item["itemId"];
				name_element.placeholder = item["name"];
				note_element.placeholder = item["note"] ? item["note"] : null;
				reminder_element.placeholder = item["reminder"] ? item["reminder"] : null;
				location_element.placeholder = item["location"] ? item["location"] : null;

				for(var i, j = 0; i = colour_element.options[j]; j++) {
				    if(i.value == item["colour"]) {
				        colour_element.selectedIndex = j;
				        break;
				    }
				}

			}

			function openModal(itemInfo){
				item = itemInfo;
				setPlaceHolders();
				console.log(item);
				
				if (item["type"] == 'event'){
					document.getElementById('modalEventBlock').style.display = 'block';
			        document.getElementById('modalTaskBlock').style.display = 'none';
			        document.getElementById('modalReminderBlock').style.display = 'none';
			        document.getElementById('modalNoteBlock').style.display = 'none';
				}
				else if (item["type"] == 'task'){
					document.getElementById('modalEventBlock').style.display = 'none';
			        document.getElementById('modalTaskBlock').style.display = 'block';
			        document.getElementById('modalReminderBlock').style.display = 'none';
			        document.getElementById('modalNoteBlock').style.display = 'none';
				}
				else if (item["type"] == 'reminder'){
					document.getElementById('modalEventBlock').style.display = 'none';
			        document.getElementById('modalTaskBlock').style.display = 'none';
			        document.getElementById('modalReminderBlock').style.display = 'block';
			        document.getElementById('modalNoteBlock').style.display = 'none';
				}
				else if (item["type"] == 'note'){
					document.getElementById('modalEventBlock').style.display = 'none';
			        document.getElementById('modalTaskBlock').style.display = 'none';
			        document.getElementById('modalReminderBlock').style.display = 'none';
			        document.getElementById('modalNoteBlock').style.display = 'block';
				}
			
				modal.style.display = "block";
			}

			function getItemId(){
				return item["itemId"];
			}

			// When the user clicks on <span> (x), close the modal
			span.onclick = function() {
			    modal.style.display = "none";
			}

			// When the user clicks anywhere outside of the modal, close it
			window.onclick = function(event) {
			    if (event.target == modal) {
			        modal.style.display = "none";
			    }
			}

			// End modal related ----------------------------------------------------

			// Checks which radio button is clicked and displays the
			// right form:
			function typeCheck() {
			    if (document.getElementById('eventButton').checked) {
			        document.getElementById('eventBlock').style.display = 'block';
			        document.getElementById('taskBlock').style.display = 'none';
			        document.getElementById('reminderBlock').style.display = 'none';
			        document.getElementById('noteBlock').style.display = 'none';
			    }
			    else if (document.getElementById('taskButton').checked){
			        document.getElementById('eventBlock').style.display = 'none';
			        document.getElementById('taskBlock').style.display = 'block';
			        document.getElementById('reminderBlock').style.display = 'none';
			        document.getElementById('noteBlock').style.display = 'none';
			    }
			    else if (document.getElementById('reminderButton').checked){
			        document.getElementById('eventBlock').style.display = 'none';
			        document.getElementById('taskBlock').style.display = 'none';
			        document.getElementById('reminderBlock').style.display = 'block';
			        document.getElementById('noteBlock').style.display = 'none';
			    }
			    else{
			        document.getElementById('eventBlock').style.display = 'none';
			        document.getElementById('taskBlock').style.display = 'none';
			        document.getElementById('reminderBlock').style.display = 'none';
			        document.getElementById('noteBlock').style.display = 'block';
			    }
			}
		</script>
_END;
}

?>