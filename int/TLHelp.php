<?php

  include_once("fest.php");
  A_Check('Upload');

  dostaffhead("Timeline Help");
?>
<h2>Basics</h2>
The timeline system is to provide a simple to use way of keeping track of actions and the time line, mostly by self reporting.<p>

Anyone can raise tasks.  Only the person a task is assigned to, the originator, and those in control of the timeline can change a task.<p>

When you go to the timeline (either off the main staff tools page or the Timeline link at the top) it will show you all open tasks assigned to you (and any that are homeless or rated
critical).<p>

The box at the top allows you to create a new task, show tasks for all people, see those due in the next month, or all tasks, finally there is an option to see more information
(not needed for most people).<p>

<h2>Marking off your progress</h2>
In most cases, just move the slidebar across to record your progress on each task.<p>

<h2>Adding new tasks</h2>
<ul>
<li>Click on the "Add a Task"
<li>Give it a short but relevant title
<li>Set the start date (if not today)
<li>Set the due date (understands this in many formats such as 23/9/19, 23rd Sep, Sep, Sep 23rd, Sep 23)
<li>Who is it to be assigned to (default YOU)
<li>How important is it - most will be blank (normal)
<li><b>IF</b> you think the tasks is a recuring one each year, tick the recuring box and it will magically appear in the timeline next year
<li>If you want to expand on the title and give a more detailed explanation, then add some notes
<li>Mark any progress, if relevant
<li>If you want to add some notes about the progress then fill in the progress text
<li>History is NORMALLY automatically filled in with records of who marked off what progress on what date.  You should not NORMALLY need to change this.
</ul>

Then click on <b>CREATE</b><p>

<h2>Looking back, plan ahead</h2>
The timeline system defaults to the currently planned year.  You can look back to previous year(s) and forward to future year(s) by selecting the relevant year at the top right.<p>

<h2>Changing Items</h2>
Click on the name of a task to see more about it and to edit it.<p>

Only the assignee, the originator, and those managing the timeline can edit it.<p>

Edit any fields you wish and click <b>Update</b>.<p>

<h2>Planned Features</h2>
Emails - You will get emails when new items are added for you and to remind you of outstanding items.  (This will be relatively soon)<p>

Dependances - The ability to have some activities be dependant on others will be added at some point.<p>

Statistics - what and in what form is not yet decided<p>

<h2>Questions/Problems/Suggestions</h2>
Talk to Richard<p>

<?php
  dotail();
?>

