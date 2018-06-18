<?php

$COLOURS = array(0 => "grey",
				 1 => "red",
				 2 => "orange",
				 3 => "yellow",
				 4 => "green",
				 5 => "blue",
				 6 => "purple",
				 7 => "black");

// Adds an item to DB using form inputs.
function addItem($POST_RESULT){
	if ($POST_RESULT['type'] === 'event'){
		$options = array("start_date" 		 => $_POST["start_date"],
						 "end_date"   		 => $_POST["end_date"]);
	}
	else if ($POST_RESULT['type'] === 'task'){
		$options = array("due_date" 		 => $_POST["due_date"],
						 "completion_date"   => $_POST["completion_date"]);
	}
	else{
		$options = NULL;
	}

	// If item successfully created: 
	if (createItem($_SESSION['calId'], $_SESSION['id'], 
			   	   $POST_RESULT['name'], $POST_RESULT['note'], $POST_RESULT['reminder'], $POST_RESULT['type'], 
			   	   $POST_RESULT['colour'], $POST_RESULT['location'], $options))
	{
		
		// Alert:
		echo <<<_END
			<div class="positive_alert">
			  	<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
			  	<strong>Success!</strong> Item was successfully added!
			</div>
_END;
	}
	else{

		// Alert:
		echo <<<_END
			<div class="negative_alert">
			  	<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
			  	<strong>Item could not be added!</strong>
			</div>
_END;
	}
}

// Type must be either 'event' or 'task'.
function itemsToHTML($type, $items){
	if ($type !== 'event' && $type !== 'task'){
			echo "!!!!! Incorrect type input. !!!!!!";
	}
	else {

		foreach($items as &$item) {

			global $COLOURS;
	        $name = $item["name"];
	        $description = $item["note"];
	        $colour = $COLOURS[$item["colour"]];

	        if ($type === 'event'){
		        $beginningDate = $item["startDate"];
		        $beginningHour = date('H:i',strtotime($beginningDate));
		        $endingDate = $item["endDate"];
		        $endingHour = date('H:i',strtotime($endingDate));
		    }
	        else if ($type === 'task'){
	        	$beginningDate = $item["dueDate"];
		        $beginningHour = date('H:i',strtotime($beginningDate));
		        $endingDate = $item["completionDate"];
		        $endingHour = date('H:i',strtotime($endingDate));
	        }

	        echo "<h4>{$beginningDate}</h4>";
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
	        echo "</article>";

	    } // end for each loop
	} // end if ($type !== 'event' && $type !== 'task')
}

// Loads the script required for toggling between the 
// different item forms. 
function loadFormDisplayJS(){
	echo <<<_END
		<script type="text/javascript">
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