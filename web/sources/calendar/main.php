<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = '';
$pageMode = '';

// calendar ID will be in the URL 
//$cal_id

// echo("<br> CREATE ITEM TEST<br>");
// createItem(1, 1, 'event', 'note', "2018-05-12", 'event', 0, 'location', NULL);

//echo("<br>");

if(!$loggedin){
	header("Location: ?$profile=login");
}
else
{	
	include('styles/header.php');

    // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    // ENDPOINT CHANGE TO ?$profile=calendar&calendar=ID&addItem=true.
    // Make sure it is changed in the form divs too.
    // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    // Check endpoint is addItem:
	if(isset($_GET['calendar']) && $_GET['calendar'] === 'addItem'){

		// ----------- debugging ------------------------------------
		echo("<br> GET: <br>");
		print_r($_GET);

		// Looking at what is POSTED in form.
		echo("<br> POST: <br>");
		print_r ($_POST);
		// ----------- debugging ------------------------------------

		if ($_POST['type'] === 'event'){
			$options = array("start_date" 		 => $_POST["start_date"],
							 "end_date"   		 => $_POST["end_date"]);
		}
		else if ($_POST['type'] === 'task'){
			$options = array("due_date" 		 => $_POST["due_date"],
							 "completion_date"   => $_POST["completion_date"]);
		}
		else{
			$options = NULL;
		}

		// If item successfully created: 
		if (createItem($_SESSION['calId'], $_SESSION['id'], 
				   	   $_POST['name'], $_POST['note'], $_POST['reminder'], $_POST['type'], 
				   	   $_POST['colour'], $_POST['location'], $options))
		{
			// Alert:
			echo <<<_END
				<div class="alert">
				  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
				  <strong>Success!</strong> Item was successfully added!
				</div>
_END;
		}
	} // end if(isset($_GET['calendar'])...		

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

			<div class="main global">
                <div class="container">
                    <main>
                        <header class="calendar">
                            <h2><i class="far fa-calendar-alt"></i> Calendar Name</h2>
                            <span class="actions">
                                <a class="edit" href="#"><i class="fas fa-edit"></i></a>
                                <a class="addMember" href="#"><i class="fas fa-users"></i></a>
                            </span>
                        </header>
                        <section class="items">
                            <h3>Events</h3>
                            <h4>Saturday, June 2, 2018</h4>
                            <a href="#">
                                <article>
                                    <div class="time">
                                        <time class="start" datetime="2018-06-02 08:00">8:00 am</time>
                                        <time class="end" datetime="2018-06-02 09:00">9:00 am</time>
                                    </div>
                                    <div class="indicator"><i class="far fa-calendar"></i></div>
                                    <div class="itemInfo">
                                        <h5>Event Item</h5>
                                        <p>Event item description.</p>
                                    </div>
                                </article>
                            </a>
                            <article>
                                <div class="time">
                                    <time class="due" datetime="2018-06-02 18:00">6:00 pm</time>
                                </div>
                                <div class="indicator"><i class="far fa-square"></i></div>
                                <div class="itemInfo">
                                    <h5>Task Item</h5>
                                    <p>Task item description.</p>
                                </div>
                            </article>
                            <article>
                                <div class="time">
                                    <time class="due" datetime="2018-06-02 18:00">6:00 pm</time>
                                </div>
                                <div class="indicator"><i class="far fa-check-square"></i></div>
                                <div class="itemInfo">
                                    <h5>Completed Task Item</h5>
                                    <p>Completed task item description.</p>
                                </div>
                            </article>
                            <article>
                                <div class="time">
                                    <time class="reminder" datetime="2018-06-02 19:00">7:00 pm</time>
                                </div>
                                <div class="indicator"><i class="far fa-bell"></i></div>
                                <div class="itemInfo">
                                    <h5>Reminder Item</h5>
                                    <p>Reminder item description.</p>
                                </div>
                            </article>
                            <article>
                                <div class="time">
                                    <time class="ref" datetime="2018-06-02 20:00">8:00 pm</time>
                                </div>
                                <div class="indicator"><i class="far fa-sticky-note"></i></div>
                                <div class="itemInfo">
                                    <h5>Note Item</h5>
                                    <p>Note item description.</p>
                                </div>
                            </article>
                            <article class="red">
                                <div class="time">
                                    <time class="start" datetime="2018-06-02 08:00">8:00 am</time>
                                    <time class="end" datetime="2018-06-02 09:00">9:00 am</time>
                                </div>
                                <div class="indicator"><i class="far fa-calendar"></i></div>
                                <div class="itemInfo">
                                    <h5>Event Item</h5>
                                    <p>Event item description.</p>
                                </div>
                            </article>
                            <article class="orange">
                                <div class="time">
                                    <time class="start" datetime="2018-06-02 08:00">8:00 am</time>
                                    <time class="end" datetime="2018-06-02 09:00">9:00 am</time>
                                </div>
                                <div class="indicator"><i class="far fa-calendar"></i></div>
                                <div class="itemInfo">
                                    <h5>Event Item</h5>
                                    <p>Event item description.</p>
                                </div>
                            </article>
                            <article class="yellow">
                                <div class="time">
                                    <time class="start" datetime="2018-06-02 08:00">8:00 am</time>
                                    <time class="end" datetime="2018-06-02 09:00">9:00 am</time>
                                </div>
                                <div class="indicator"><i class="far fa-calendar"></i></div>
                                <div class="itemInfo">
                                    <h5>Event Item</h5>
                                    <p>Event item description.</p>
                                </div>
                            </article>
                            <article class="green">
                                <div class="time">
                                    <time class="start" datetime="2018-06-02 08:00">8:00 am</time>
                                    <time class="end" datetime="2018-06-02 09:00">9:00 am</time>
                                </div>
                                <div class="indicator"><i class="far fa-calendar"></i></div>
                                <div class="itemInfo">
                                    <h5>Event Item</h5>
                                    <p>Event item description.</p>
                                </div>
                            </article>
                            <article class="blue">
                                <div class="time">
                                    <time class="start" datetime="2018-06-02 08:00">8:00 am</time>
                                    <time class="end" datetime="2018-06-02 09:00">9:00 am</time>
                                </div>
                                <div class="indicator"><i class="far fa-calendar"></i></div>
                                <div class="itemInfo">
                                    <h5>Event Item</h5>
                                    <p>Event item description.</p>
                                </div>
                            </article>
                            <article class="purple">
                                <div class="time">
                                    <time class="start" datetime="2018-06-02 08:00">8:00 am</time>
                                    <time class="end" datetime="2018-06-02 09:00">9:00 am</time>
                                </div>
                                <div class="indicator"><i class="far fa-calendar"></i></div>
                                <div class="itemInfo">
                                    <h5>Event Item</h5>
                                    <p>Event item description.</p>
                                </div>
                            </article>
                            <article class="grey">
                                <div class="time">
                                    <time class="start" datetime="2018-06-02 08:00">8:00 am</time>
                                    <time class="end" datetime="2018-06-02 09:00">9:00 am</time>
                                </div>
                                <div class="indicator"><i class="far fa-calendar"></i></div>
                                <div class="itemInfo">
                                    <h5>Event Item</h5>
                                    <p>Event item description.</p>
                                </div>
                            </article>
                            <article class="purple">
                                <div class="time">
                                    <time class="start" datetime="2018-06-02 08:00">8:00 am</time>
                                    <time class="end" datetime="2018-06-02 09:00">9:00 am</time>
                                </div>
                                <div class="indicator"><i class="far fa-calendar"></i></div>
                                <div class="itemInfo">
                                    <h5>Event Item</h5>
                                    <p>Event item description with extra long text about random stuff.</p>
                                </div>
                            </article>
                        </section>
                    </main>

                    <!---------------------------------- addItem aside ---------------------------------->
                    <!----------------------------------------------------------------------------------->

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
			                    <div class="field"><label for="start">Start</label><input type="datetime-local" name="start_date" placeholder="Start Date" required="required" value = "start_date"></div>
			                    <div class="field"><label for="end">End</label><input type="datetime-local" name="end_date" placeholder="End Date" required="required" value = "end_date"></div>
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
			                    <div class="field"><label for="completion">Completion</label><input type="datetime-local" name="completion_date" placeholder="Completion Date" required="required" value="completion_date"></div>
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

                </div><!-- .container ends -->
            </div>
_END;

	include('styles/footer.php');
}

?>
