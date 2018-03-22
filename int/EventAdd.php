<?php
  include_once("fest.php");
  A_Check('Staff');

//Access is for anyone with Venue to edit all, otherwise can Create and edit own only 
?>

<html>
<head>
<title>WMFF Staff | Add/Change Event</title>
<?php include("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
<script src="/js/Participants.js"></script>
</head>
<body>
<?php include("files/navigation.php"); ?>
<?php
  include("ProgLib.php");
  include("DocLib.php");
  include("DanceLib.php");
  include("MusicLib.php");
  include("EventCheck.php");
  global $MASTER,$YEAR,$USERID,$Importance;

  Set_Event_Help();

  $EventTimeFields = array('Start','End','SlotEnd');
  $EventTimeMinFields = array('Setup','Duration');

  $SideList=Select_Come();
  $ActList=Select_Act_Come(0);
  $OtherList=Select_Other_Come(0);
  $Venues = Get_Real_Venues(0);
  $Skip = 0;

  echo "<span class=floatright id=largeredsubmit onclick=($('.HelpDiv').show()) >HELP</span>";
  echo "<div class=content>";
  echo "<div class=HelpDiv hidden>";
?>
<h3>Help for Adding/Creating/Modifying an Event</h3>
For most events you only need:<p>
<ul><li>A Name (Can be pretty generic eg Dancing, Saturday Night Concert)
<li>A Type (A Broad categorization as to what lists it appears in)<li>A Venue<li>A Day<li>A Start and End Time</ul>
If you need to block off the venue before the event, give a setup time in minutes, eg 30.<p>
If you would like to give a small description, this will appear in the programme book and in lists of events.<p>
You may if you wish have a longer blurb, this will only appear on the webpage for this event.<p>
If it should be listed as a Family event, click the relevant box.  A children's Workshop would be type workshop and have Family selected.<p>
You do not normally need to set Duration, Bar/Food, Special, Alt Edit, Prices and Non Fest.  For all complex cases contact Richard (07718 511432)<p>
Then click on <b>Create</b>.<p>
See if any errors are reported at the top of the event - they currently are a bit cryptic but any event clashes involving this event will be listed 
- resolve them please.<p>
If it a simple event, with one particpant do the following (this can be done later if you have not yet decided): 
<ul><li>Select the Side, Act or Other participant from the drop down lists.
<li>Click on <b>Update</b></ul><p>
<h3>Concerts and similar events</h3>
Each act in the concert needs a sub event.<p>
On the right near the bottom it will say Add 1 sub events.  Change the 1 to the number of acts and click on <b>Add</b> (further acts can be added later if needed)<p>
In the body of the event, it will now say <b>Has Sub Events</b>, click on that link.<p>
You will see a list of sub events - the first is the entire event (Concert), you will need to change each of the others in turn for each act.<p>
Click on one of them, change the start and end times and select who is performing that spot, then click <b>Update</b>.<p>
To go back to the list of sub events click on <b>Is a Sub Event</b>
<h3>Dancing</h3>
For example, setting up dancing in the cornmarket, create a single event that runs from 10am to 5pm, then divide it up into 30 minute sub events.<p>
To divide into a number of sub events, one for each half hour, click on <b>Divide</b>.<p>
It is possible to edit sides into dancing here, but it is far far easier with the <b>Edit Dance Programme</b> from the main staff pages.<p>
A similar feature will appear eventually for music.<p>
<?php
  echo "</div>";

  echo "<h2>Add/Edit Events</h2>\n";
  echo "<form method=post action='EventAdd.php'>\n";
  if (isset($_POST{'EventId'})) { // Response to update button
    $eid = $_POST{'EventId'};
    if ($eid > 0) $Event = Get_Event($eid);
    if (isset($_POST{'ACTION'})) {
      switch ($_POST{'ACTION'}) {
      case 'Divide':
        //echo fm_smalltext('Divide into ','SlotSize',30,2) . fm_smalltext(' minute slots with ','SlotSetup',0,2) . " minute setup";
	$slotsize  = $_POST['SlotSize'];
	$slotsetup = $_POST['SlotSetup'];
        $se = $Event['SubEvent'];
	$SubEvent = $Event;
	for ($i=1;$i<5;$i++) { $SubEvent["Side$i"] = $SubEvent["Act$i"] = $SubEvent["Other$i"] = 0; };
//	$SubEvent['SName'] = "";
	if ($se == 0) {
	  $Timeleft = timereal($Event['End'])-timereal($Event['Start'])-$slotsize;
	  if ($Timeleft > 0) {
	    $Event['SubEvent'] = -1;
	    $Event['SlotEnd']=timeadd($Event['Start'],$slotsize-$slotsetup);
	    Put_Event($Event);
	    $SubEvent['SubEvent']=$eid;
	    while ($Timeleft > 0) {
	      $SubEvent['Start'] = timeadd($SubEvent['Start'],$slotsize);
	      $SubEvent['End'] = min($Event['End'],timeadd($SubEvent['Start'],$slotsize-$slotsetup));
	      $SubEvent['Duration'] = $SubEvent['End'] - $SubEvent['Start'];
	      $Timeleft -= $slotsize;
	      Insert_db('Events',$SubEvent);
            }
	  } else { 
	    $Err = "Can't divide";
	  }
	} elseif ($se < 0) { // Aready parent event
	  $Timeleft = timereal($Event['SlotEnd'])-timereal($Event['Start'])-$slotsize;
	  if ($Timeleft > 0) {
	    $oldEnd = $Event['SlotEnd'];
	    $Event['SlotEnd']=timeadd($Event['Start'],$slotsize-$slotsetup);
	    Put_Event($Event);
	    $SubEvent['SubEvent']=$eid;
	    while ($Timeleft > 0) {
	      $SubEvent['Start'] = timeadd($SubEvent['Start'],$slotsize);
	      $SubEvent['End'] = min($oldEnd,timeadd($SubEvent['Start'],$slotsize-$slotsetup));
	      $SubEvent['Duration'] = $SubEvent['End'] - $SubEvent['Start'];
	      $Timeleft -= $slotsize;
	      Insert_db('Events',$SubEvent);
            }
	  } else { 
	    $Err = "Can't divide";
	  }
	} else { // Child event
	  $Timeleft = timereal($Event['End'])-timereal($Event['Start'])-$slotsize;
	  if ($Timeleft > 0) {
	    $oldEnd = $Event['End'];
	    $Event['End']=timeadd($Event['Start'],$slotsize-$slotsetup);
	    Put_Event($Event);
	    while ($Timeleft > 0) {
	      $SubEvent['Start'] = timeadd($SubEvent['Start'],$slotsize);
	      $SubEvent['End'] = min($oldEnd,timeadd($SubEvent['Start'],$slotsize-$slotsetup));
	      $SubEvent['Duration'] = $SubEvent['End'] - $SubEvent['Start'];
	      $Timeleft -= $slotsize;
	      Insert_db('Events',$SubEvent);
            }
	  } else { 
	    $Err = "Can't divide";
	  }
	}
        break;

      case 'Add': // Add N Subevents starting and ending at current ends - if a subevent, parent is ses parent
        $AddIn = $_POST{'Slots'};
	$Se = $Event['SubEvent'];
	$SubEvent = $Event;
	$SubEvent['End'] = $SubEvent['Start'];
	$SubEvent['Duration'] = 0;
	for ($i=1;$i<5;$i++) { $SubEvent["Side$i"] = $SubEvent["Act$i"] = $SubEvent["Other$i"] = 0; };
	if ($Se > 0) { // Is already a Sub event so copy parent
	} else if ($Se ==0 ) { // SEs of this
	  $Event['SubEvent'] = -1;
	  $Event['SlotEnd'] = $Event['End'];
	  Put_Event($Event);
	  $SubEvent['SubEvent'] = $eid;
	} else { // Already Has SEs
	  $SubEvent['SubEvent'] = $eid;
	}  
	for($i=1;$i<=$AddIn;$i++) Insert_db('Events',$SubEvent);
	break;

      case 'Delete':
	$Event['Year'] -= 1000;
	Put_Event($Event);
	$Skip = 1;
        break;

      case 'Promote': // Sub Event to full event
	$Se = $Event['SubEvent'];
	if ($Se > 0) { // Is a SE
	  $Event['SubEvent'] = 0;
	  Put_Event($Event);
	} else { // Is the parent - duplicate and make new one simple, make old one start after new one and clear contents
	  $NewEvent = $Event;
	  $NewEvent['SubEvent'] = 0;
	  $NewEvent['End'] = $NewEvent['SlotEnd'];
	  $NewEvent['EventId']=0;
	  $NewEvent['SlotEnd'] = 0;
	  Insert_db('Events',$NewEvent);

	  $Event['Start'] = $NewEvent['End'];
	  for ($i=1;$i<5;$i++) $Event["Side$i"] = $Event["Act$i"] = $Event["Other$i"] = 0;
	  Put_Event($Event);
	}
	break;
      }
    } elseif ($eid > 0) { 	// existing Event
      $CurEvent=$Event;
      Parse_TimeInputs($EventTimeFields,$EventTimeMinFields);
      Update_db_post('Events',$Event);
      Check_4Changes($CurEvent,$Event);
      $OtherValid = 1;
      if ($Event['BigEvent']) {
	$err = 0;
        if (!isset($Other)) $Other = Get_Other_Things_For($eid);
	if (!$err && $Other) foreach ($Other as $i=>$ov) {  // Start with venues only
	  if ($ov['Type'] == 'Venue') {
	    $id = $ov['BigEid'];
	    if ($_POST{"VEN$id"} != $ov['Identifier']) {
	      $ven = $_POST{"VEN$id"};
	      if ($ven != 0 ) {
	      	if ($Event['Venue'] == $ven) $err = 1;
	        foreach ($Other as $ii=>$oov) if ($ov['Type'] == 'Venue' && $oov['Identifier'] == $ven) $err=1;
		$BigE = Get_BigEvent($id);
	        $BigE['Identifier'] = $ven;
	     	Put_BigEvent($BigE);
	      } else {
		db_delete('BigEvent',$id);
	      }
	      $OtherValid = 0;
	    }
	  }
	}
	if ($err==0 && $_POST{'NEWVEN'} > 0) { // Add venue
	  if ($Other) foreach ($Other as $i=>$ov) if ($ov['Type'] == 'Venue' && $ov['Identifier'] == $_POST{'NEWVEN'}) $err++;
	  if ($err == 0 && $Event['Venue'] == $_POST{'NEWVEN'}) $err++; 
	  if ($err == 0) {
	    $BigE = array('Event'=>$eid, 'Type'=>'Venue', 'Identifier'=>$_POST{'NEWVEN'});
	    New_BigEvent($BigE);
	    $OtherValid = 0;
	  }
	}
	if ($err) echo "<h2 class=ERR>The Event already has Venue " . $Venues[$_POST{'NEWVEN'}] . "</h2>\n";
	if (!$OtherValid) unset($Other);
      }  
    } else { // New
      $proc = 1;
      if (!isset($_POST['SName']) || strlen($_POST{'SName'}) < 2) { 
        echo "<h2 class=ERR>NO NAME GIVEN</h2>\n";
        $Event = $_POST;
	$proc = 0;
      }
      if ($_POST['Owner'] == 0) $_POST['Owner'] = $USERID;
      Parse_TimeInputs($EventTimeFields,$EventTimeMinFields);
      $_POST{'Year'} = $YEAR;
      $eid = Insert_db_post('Events',$Event,$proc); //
      $empty = array();
      Check_4Changes($empty,$Event);
    }
  } elseif (isset($_GET['e'])) {
    $eid = $_GET['e'];
    $Event = Get_Event($eid);
    if (Access('Staff','Venues') || $Event['Owner'] == $USERID || $Event['Owner2'] == $USERID) { // Proceed
    } else {
      Error_Page("Insufficient Privilages");
    }
  } else {
    $eid = -1;
    $Event = array();
    if (isset($_GET{'Act'})) $Event['Act1'] = $_GET{'Act'};
  }

// $Event_Types = array('Dance','Music','Workshop','Craft','Mixed','Other');
// Dance		Y		Y			Y	Y
// Music			Y	Y			Y	Y
// Other				Y		Y	Y	Y

  if ($eid > 0) EventCheck($eid);

  $AllU = Get_AllUsers(0);
  $AllA = Get_AllUsers(1);
  $AllActive = array();
  foreach ($AllU as $id=>$name) if ($AllA[$id] >= 2 && $AllA[$id] <= 6) $AllActive[$id]=$name;

//var_dump($Event);
  if (isset($Err)) echo "<h2 class=ERR>$Err</h2>\n";
  echo "<span class=NotSide>Fields marked should be only set by Richard</span>";
  if (!$Skip) {
    $adv = ($Event['SubEvent']>0?"class=Adv":""); 
    echo "<table width=90% border>\n";
      if (isset($eid) && $eid > 0) {
        echo "<tr><td>Event Id:" . $eid . fm_hidden('EventId',$eid);
      } else {
        echo fm_hidden('EventId',-1);
        $Event['Day'] = 1;
      }
//      echo fm_text('SE',$Event,'SubEvent');
      echo "<td class=NotSide>" . fm_checkbox('Exclude From Spot Counts',$Event,'ExcludeCount');
      echo "<td class=NotSide>Public:" . fm_select($Public_Event_Types,$Event,'Public');
      echo "<td class=NotSide>Participant Visibility:" . fm_select($VisParts,$Event,'InvisiblePart');
      echo "<td class=NotSide>Originator:" . fm_select($AllActive,$Event,'Owner',1);

      echo "<tr><td class=NotSide>" . fm_checkbox('Multiday Event',$Event,'LongEvent','onchange=$(".mday").show()');
      $hidemday =  ($Event['LongEvent'])?'':'hidden ';
      echo "<td class=NotSide>" . fm_checkbox('Big Event',$Event,'BigEvent');
      echo "<td class=NotSide>" . fm_checkbox('Ignore Clashes',$Event,'IgnoreClash');
      echo "<td class=NotSide>" . fm_checkbox('Also Dance',$Event,'ListDance');
      echo "<td class=NotSide>" . fm_checkbox('Also Music',$Event,'ListMusic');

      echo "<tr><td>" . fm_checkbox('Special Event',$Event,'Special');
      echo "<td>" . fm_checkbox('Family Event',$Event,'Family');
      echo "<td>" . fm_checkbox('Non Fest',$Event,'NonFest');
      echo "<td>Alt Edit:" . fm_select($AllActive,$Event,'Owner2',1);
      echo "<td class=NotSide>Importance: " . fm_select($Importance,$Event,'Importance');

      echo "<tr>" . fm_text('Name', $Event,'SName');
        echo "<td>Event Type:" . fm_select($Event_Types,$Event,'Type');
        $se = $Event['SubEvent'];
        if ($se == 0) { echo "<td>No Sub Events"; }
        elseif ($se < 0) { echo "<td><a href=EventList.php?se=$eid>Has Sub Events</a>"; }
        else { echo "<td><a href=EventList.php?se=$se>Is a Sub Event</a>"; };
        echo "<td class=NotSide>" . fm_checkbox('No Part',$Event,'NoPart');

      if ($se <= 0) {
	echo "<tr>";
	echo "<td>" . fm_simpletext('Price &pound;',$Event,'Price1');
	if ($MASTER['PriceChange1']) echo "<td>" . fm_simpletext('Price after ' . date('j M Y',$MASTER['PriceChange1']) . '(if diff) &pound;',$Event,'Price2');
	if ($MASTER['PriceChange2']) echo "<td>" . fm_simpletext('Price after ' . date('j M Y',$MASTER['PriceChange2']) . '(if diff) &pound;',$Event,'Price3');
	echo "<td>" . fm_simpletext('Door Price (if different) &pound;',$Event,'DoorPrice');
	echo "<td class=NotSide>" . fm_simpletext('Ticket Code',$Event,'TicketCode');
      }

      echo "<tr>" . fm_radio('<span class=mday $hidemday>Start </span>Day',$DayList,$Event,'Day');
//      echo fm_text('Year',$Event,'Year');
      echo "<td colspan=3>Times: " . fm_smalltext('Start','Start',$Event['Start']);
        echo fm_smalltext('End','End',$Event['End']);
        echo fm_smalltext('Setup Time','Setup (mins)',$Event['Setup']) ;
        echo fm_smalltext('Duration','Duration',$Event['Duration']) . "&nbsp;minutes ";
        if ($se < 0) echo fm_smalltext(', Slot End','SlotEnd',$Event['SlotEnd']);
      if ($se <= 0) echo "<tr class=mday $hidemday>" . fm_radio('End Day',$DayList,$Event,'EndDay') . 
		"<td colspan=3>Set up a sub event for each day after first, times are for first day";
      echo "<tr><td>Venue:<td>" . fm_select($Venues,$Event,'Venue',1);
        echo fm_textarea('Notes', $Event,'Notes',4,2);
      $et = 'Mixed';
      if (isset($Event['Type'])) $et = $Event_Types[$Event['Type']];
      echo "<tr $adv>" . fm_textarea('Description <span id=DescSize></span>',$Event,'Description',5,2,'',
			'maxlength=150 oninput=SetDSize("DescSize",150,"ShortBlurb") id=ShortBlurb'); 
      echo "<tr $adv>" . fm_textarea('Blurb',$Event,'Blurb',5,2,'','maxlength=2000');
      echo "<tr><td>If the Venue doesn't normally have a Bar or Food<td>" . fm_checkbox('Bar',$Event,'Bar') . 
		"<td>" . fm_checkbox('Food',$Event,'Food') . fm_text('Food/Bar text',$Event,'BarFoodText') . "\n";
      if (!$Event['BigEvent']) {
//        if ($et == 'Dance' || $et == 'Workshop' || $et == 'Mixed' || $et == 'Other') {
          echo "<tr><td rowspan=2>Sides:" . Help('Sides');
          for ($i=1; $i<5; $i++) {
	    if ($i==3) echo "<tr>"; 
	    echo "<td colspan=2>" . fm_select($SideList,$Event,'Side' . $i);
	  }
//        }
//        if ($et == 'Music' || $et == 'Workshop' || $et == 'Mixed' || $et == 'Other') {
          echo "<tr><td rowspan=2>Music:" . Help('Acts');
          for ($i=1; $i<5; $i++) {
	    if ($i==3) echo "<tr>"; 
            echo "<td colspan=2>" .fm_select($ActList,$Event,'Act' . $i,1);
          }
//        }
//        if ($et == 'Craft' || $et == 'Workshop' || $et == 'Mixed' || $et == 'Other') {
          echo "<tr><td rowspan=2>Other:" . Help('Others');
          for ($i=1; $i<5; $i++) {
	    if ($i==3) echo "<tr>"; 
	    echo "<td colspan=2>" .fm_select($OtherList,$Event,'Other' . $i,1);
	  }
//        }
      } else {
	$ovc=0;
        echo "<tr><td>Other Venues:";
        if (!isset($Other)) $Other = Get_Other_Things_For($eid);
 	if ($Other) {
	  foreach ($Other as $i=>$ov) {
	    if ($ov['Type'] == 'Venue') {
	      $id = $ov['Identifier'];
  	      echo "<td>" . fm_select2($Venues,$id,"VEN" . $ov['BigEid'] ,1);
	      if ((($ovc++)&3) == 3) echo "\n<tr><td>";
	    }
	  }
	}
  	echo "<td>" . fm_select2($Venues,0,"NEWVEN",1);
      }
        
    echo "</table>\n";
    if ($Event['BigEvent']) {
      echo "Use the <a href=BigEventProg.php?e=$eid>Big Event Programming Tool</a> to add sides, musicians and others to this event. ";
      echo "Use the <a href=DisplayBE.php?e=$eid>Big Event Display</a> to get a simple display of the event.";
    }
  
    if ($eid > 0) {
      echo "<Center><input type=Submit name='Update' value='Update'>\n";
      if (Access('Committee','Venues')) {
        echo ", <form method=post action='EventAdd.php'>\n";
        echo fm_hidden('EventId',$eid);
        echo fm_smalltext('Divide into ','SlotSize',30,2) . fm_smalltext(' minute slots with ','SlotSetup',0,2) . " minute setup";
        echo "<input type=Submit name=ACTION value=Divide>, \n";
        echo "<input type=Submit name=ACTION value=Delete onClick=\"javascript:return confirm('are you sure you want to delete this?');\">, \n";
	echo "<input type=Submit name=ACTION value=Add>" . fm_smalltext('','Slots',1,2) . " sub events";
	if (Access('SysAdmin') && $se != 0 ) echo ", <input type=Submit name=ACTION value=Promote>";
        echo "</form>\n";
      }
      echo "</center>\n";
    } else { 
      echo "<Center><input type=Submit name=Create value='Create'></center>\n";
    }
    if ($Event['SubEvent'] > 0) echo "<button onclick=ShowAdv(event) id=ShowMore type=button class=floatright>More features</button>";
    echo "</form>\n";
  }
  echo "<h2><a href=EventList.php>List Events</a>";
  if ($eid) echo ", <a href=EventAdd.php>Add another event</a>";
  if ($eid) echo ", <a href=EventShow.php?e=$eid>Show Event</a>";
  echo "</h2>\n";

  dotail();
?>
