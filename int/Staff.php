<?php
  include_once("fest.php");
  /* Remove any Participant overlay */
  if (isset($_COOKIE{'WMFFD'})) {
    unset($_COOKIE{'WMFFD'});
    setcookie('WMFFD','',1,'/');
  }
  A_Check('Upload');
  $host= "https://" . $_SERVER['HTTP_HOST'];
?>

<html>
<head>
<title>WMFF Staff | Staff Tools</title>
<?php include("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
<script src="/js/jquery-3.2.1.min.js"></script>
<script src="/js/jquery.typeahead.min.js"></script>
<link href="/css/jquery.typeahead.min.css" type="text/css" rel="stylesheet" />
<script src="/js/Staff.js"></script>
</head>
<body>
<?php
  global $YEAR,$THISYEAR;
  include_once("files/navigation.php");
  include_once("ProgLib.php");

  $Years = Get_Years();

  echo '<div class="content">';
  if (isset($ErrorMessage)) echo "<h2 class=ERR>$ErrorMessage</h2>";

  echo "<div class=floatright><h2>";
  if (isset($Years[$YEAR-1])) echo "<a href=Staff.php?Y=" . ($YEAR-1) .">" . ($YEAR-1) . "</a> &nbsp; ";
  if (isset($Years[$YEAR+1])) echo "<a href=Staff.php?Y=" . ($YEAR+1) .">" . ($YEAR+1) . "</a>\n";
  echo "</h2></div>";
  echo "<h2>Staff Pages - $YEAR</h2>\n";
  echo "<table border width=90% class=Staff>\n";
  echo "<tr><td class=Stafftd><h2>Document Storage</h2>\n";
    echo "<ul>\n";
    if (Access('Staff','Docs')) {
      echo "<li><a href=Dir.php>View Document Storage</a>\n";
      echo "<li><a href=Search.php>Search Document Storage</a>\n";
    }
    echo "<p>";
    echo "<li><a href=StaffHelp.php>General Help</a>\n";

    if (0 && Access('SysAdmin')) {
      echo "<li><a href=DirRebuild.php?FI>Rebuild Directorys - Files are master</a>";
      echo "<li><a href=DirRebuild.php?DB>Rebuild Directorys - Database is master</a>";
    }
    echo "</ul>\n";

// *********************** TIMELINE ****************************************************
  echo "<td class=Stafftd><h2>Timeline</h2>\n";
    echo "<ul>\n";
    echo "<li><a href=TimeLine.php>Time Line Management</a>\n<p>";
    echo "<li>Timeline Help\n";
    echo "</ul><p>\n";

// *********************** MUSIC ****************************************************
  echo "<tr>";
    echo "<td class=Stafftd><h2>Music</h2>\n";
    echo "<ul>\n";
    echo "<li><a href=MusicFAQ.php>Music FAQ</a>\n";
    if (Access('Staff')) {
      echo "<li><a href=ListMusic.php?SEL=ALL&Y=$YEAR>List All Music Acts in Database</a>\n";
      echo "<li><a href=ListMusic.php?SEL=Booking&Y=$YEAR>List Music Acts Booking</a>\n";
      echo "<li>Music Acts Summary"; //<a href=MusicSummary.php?Y=$YEAR>Music Acts Summary</a>\n";
    }
    if (Access('Staff','Music')) {
      echo "<li>Invite Music Acts\n";
//      echo "<li><a href=InviteMusic.php>Invite Music Acts</a>\n";
      echo "<li><a href=AddMusic.php>Add Music Act to Database</a>\n"; 
      echo "<li>Find Act"; // <a href=AddDance.php>Add Dance Side</a>"; 
/*
//      if ($YEAR == $THISYEAR) echo "<li><a href=MusicProg.php?>Edit Music Programming</a>";
*/
      echo "<li>Edit Music Programming";
      if (Access('SysAdmin')) {
        echo "<li><a href=ShowMusicProg.php?Y=$YEAR>View Music Programming\n</a>"; 
      } else {
        echo "<li>View Music Programming\n"; 
      }
    } else {
//      echo "<li><a href=ShowMusicProg.php?Y=$YEAR>View Music Programme</a>";
    }
    if (Access('SysAdmin')) {
      echo "<p><table><tr><td>";
      echo "<li class=smalltext><a href=MusicTypes.php>Set Music Types</a>";
      echo "<li class=smalltext><a href=ResetImageSizes.php>Scan and save Image sizes</a>";
      echo "</table><p>\n";
    }
    echo "<li><a href=LiveNLoudView.php?Y=$YEAR>Show Live N Loud applications</a>";
    echo "</ul>\n";

// *********************** DANCE ****************************************************
  echo "<td class=Stafftd><h2>Dance</h2>\n";
    echo "<ul>\n";
    if (Access('Staff','Dance')) {
      echo "<li><a href=InviteDance.php>Invite Dance Sides</a>\n";
    }
    if (Access('Committee')) {
      echo "<li><a href=ListDance.php?SEL=ALL&Y=$YEAR>List All Dance Sides in Database</a>\n";
      echo "<li><a href=ListDance.php?SEL=Coming&Y=$YEAR>List Dance Sides Coming</a>\n";
      echo "<li><a href=DanceSummary.php?Y=$YEAR>Dance Sides Summary</a>\n";
    }
    if (Access('Staff','Dance')) {
      echo "<li><a href=AddDance.php>Add Dance Side to Database</a>"; 
    }

//      echo "<li><input class=typeahead type=text placeholder='Find a Side'>\n";
    if (0 && Access('SysAdmin')) {
      echo "<li>";
//	echo "<form id=form-sidefind name=form-sidefind>\n";
      echo "<span class=typeahead__container><span class=typeahead__field>\n";
      echo "<span class=typeahead__query><input class=findaside name=sidefind type=search placeholder='Find Side' autocomplete=off></span>";
      echo "<span class=typeahead__button><button type=submit><i class=typeahead__search-icon></i></button></span>\n";
      echo "</span></span>";
//	echo "</form>\n"; 
    } else {
      echo "<li>Find a Side\n";
//         echo "<li><input class=typeahead type=text placeholder='Find a Side'>\n";
    }

    echo "<li><a href=DanceFAQ.php>Dance FAQ</a>\n";
    if (Access('Staff','Dance')) {
      if ($YEAR == $THISYEAR) {
	/* echo "<li><a href=DanceProg.php?Y=$YEAR>Edit Dance Programme</a>"; */
	echo "<li><a href=NewDanceProg.php?Y=$YEAR>Edit Dance Programme</a>";
      } else {
        echo "<li><a href=DanceProg.php?Y=$YEAR&SAND>Edit 2017 Dance Programme in Sandbox</a>";
      }
    } else {
      echo "<li><a href=ShowDanceProg.php?Y=$YEAR>View Dance Programme</a>";
    }
    if (Access('SysAdmin')) {
      echo "<li><a href=ShowDanceProg.php?Y=$YEAR>View Dance Programme</a>";
      echo "<p><table><tr><td>";
      echo "<li class=smalltext><a href=ShowDanceProg.php?Cond=1&Y=$YEAR>Condensed Dance Programme</a>";
      echo "<li class=smalltext><a href=DanceCheck.php?Y=$YEAR>Dance Checking</a>";
      echo "<li class=smalltext><a href=DanceTypes.php>Set Dance Types</a>";
      echo "<li class=smalltext><a href=LineUpDance.php?MIN&Y=$YEAR>Picture free List of Dance Sides Coming</a>\n";
//      echo "<li class=smalltext><a href=ModifyDance2.php>Modify Dance Structure #2</a>\n";
      echo "<li class=smalltext><a href=WhereDance.php?Y=$YEAR>Where did Dance Sides Come from</a>\n";
      echo "<td>";
      echo "<li class=smalltext><a href=PrintLabels.php&Y=$YEAR>Print Address Labels</a>";
      echo "<li class=smalltext><a href=CarPark.php&Y=$YEAR>Car Park Tickets</a>";
      if ($YEAR == $THISYEAR) echo "<li class=smalltext><a href=WristbandsSent.php>Mark Wristbands Sent</a>";
      echo "<li class=smalltext><a href=ShowDanceProg.php?Cond=1&Pub=1&Y=$YEAR>Public Dance Programme</a>";
      echo "<li class=smalltext><a href=ShowDanceProg.php?Cond=0&Pub=1&Head=0&Day=Sat&Y=$YEAR>Dance Programme - Sat - no headers</a>";
      echo "<li class=smalltext><a href=ShowDanceProg.php?Cond=0&Pub=1&Head=0&Day=Sun&Y=$YEAR>Dance Programme - Sun - no headers</a>";
//      echo "<li class=smalltext><a href=ImportDance2.php>Import Appalachian List</a>"; // Should never be needed again
      echo "</table>\n";
    }
    echo "</ul>\n";

// *********************** STALLS & SPONSORS  ****************************************************
  echo "<tr>";
  echo "<td class=Stafftd><h2>Stalls and Sponsors</h2>\n";
    echo "<ul>\n";
      echo "<li><a href=ListCTrade.php?Y=$YEAR>List Active Traders This Year</a>\n";
      echo "<li><a href=ListTrade.php?Y=$YEAR>List All Traders</a>\n";
      echo "<li><a href=TradeFAQ.php>Trade FAQ</a>\n";
    if (Access('Staff','Stalls')) {
      echo "<li><a href=Trade.php>Add Trader</a>\n";
      echo "<li><a href=ListCTrade.php?Y=$YEAR&SUM>Traders Summary</a>\n";
      echo "<li><a href=TradeLocs.php>Trade Locations</a>\n";
      if (Access('SysAdmin')) echo "<li><a href=TradeTypes.php>Trade Types and base Prices</a>\n";
      echo "<li><a href=EmailTraders.php>Email Groups of Traders</a>\n";
//      if (Access('SysAdmin')) echo "<li><a href=TradeImport1.php>Convert old Trade Data</a>\n";
//      if (Access('SysAdmin')) echo "<li><a href=TradeImport2.php>Merge Mandy's Trade Data</a>\n";
      if (Access('SysAdmin')) echo "<li><a href=TradeImport3.php>Fix Access Keys</a>\n";
//      echo "<li><a href=/admin/trade/index.php>Old Trade Stand Section</a>\n";
      echo "<li><a href=Trade2CSV.php>Traders as CSV</a>\n";
    }
    if (Access('Staff','Sponsors')) echo "<li><a href=Sponsors.php>Sponsors</a>\n";
    if (Access('Committee','Bugs')) {
      echo "<p>";
      echo "<li class=smalltext><a href=TEmailProformas.php>EMail Proformas</a>";
    }
    echo "</ul>\n";

// *********************** VENUES & EVENTS *******************************************************
  echo "<td class=Stafftd><h2>Venues and Events</h2>\n";
    echo "<ul>\n";
    echo "<li><a href=VenueList.php>List Venues</a>\n";
    if (Access('Staff','Venues')) echo "<li><a href=EventList.php?Y=$YEAR>List Events</a>\n";
    if (Access('Staff','Venues')) echo "<li><a href=EventTypes.php>Event Types</a>\n";
    echo "<li><form method=Post action=VenueUse.php class=staffform>";
      echo "<input type=submit name=a value='Show Events at' id=staffformid>" . 
		fm_hidden('Y',$YEAR) .
		fm_select(Get_Venues(),0,'v',0," onchange=this.form.submit()") . "</form>\n";
    echo "<li>Show Events at a Time\n";
    //<li><a href=EventTime.php>List Events at a Time</a>
    if (Access('Staff','Venues') && $YEAR==$THISYEAR) echo "<li><a href=EventAdd.php>Create Event(s)</a>";
      echo "<li><a href=TicketEvents.php?Y=$YEAR>List Ticketed Events</a>\n";
    if (Access('Staff','Venues')) echo "<li><a href=MapPoints.php>Additional Map Points</a>\n";
    if (Access('SysAdmin')) echo "<li><a href=MapPTypes.php>Map Point Types</a>\n";
    echo "<li><a href=$host/Map.php>Map</a>\n";
    echo "</ul>\n";

// *********************** OTHER *****************************************************************
  echo "<tr>";
  echo "<td class=Stafftd><h2>Other (Arts, Crafts, Children, Comedy)</h2>\n";
    echo "<ul>\n";
    echo "<li><a href=StewardView.php>Stewarding Applications</a>\n";
    echo "<li><a href=NewsManage.php>News Management</a>";
//    echo "<li><a href=Facebook.php>Embed Facebook as News</a>";
//    if (Access('SysAdmin')) echo "<li><a href=NewsConvert1.php>Convert News</a>";
    if (Access('Staff','Photos')) {
      echo "<li><a href=PhotoUpload.php>Photo Upload</a>";
      echo "<li><a href=PhotoManage.php>Photo Manage</a>";
    }
    echo "<p>";
    
    if (Access('Staff')) {
      echo "<li><a href=ListMusic.php?SEL=ALL&Y=$YEAR&t=O>List All Other Participants in Database</a>\n";
      echo "<li><a href=ListMusic.php?SEL=Booking&Y=$YEAR&t=O>List Other Participants Booking</a>\n";
    }
    if (Access('Staff','Other')) {
      echo "<li><a href=AddMusic.php?t=O>Add Other Particpant to Database</a>\n"; 
      echo "<li>Find Other";
    }
    if (Access('Committee')) echo "<li><a href=Campsite.php?Y=$YEAR>Manage Campsite Use</a>\n"; 

    echo "</ul>\n";

// *********************** GENERAL ADMIN *********************************************************
  echo "<td class=Stafftd><h2>General Admin</h2>\n";
    echo "<ul>\n";
    echo "<li><a href=Login.php?ACTION=NEWPASSWD>New Password</a>\n";
    if (Access('Committee','Users')) {
      echo "<li><a href=ListUsers.php>List Users</a>";
      echo "<li><a href=AddUser.php>Add User</a>";
      echo "<li><a href=UserDocs.php>Storage Used</a>";
    }
    if (Access('Steward')) {
      echo "<li><a href=AddBug.php>New Bug/Feature request</a>\n";
      echo "<li><a href=ListBugs.php>List Bugs/Feature requests</a>\n";
    }
    if (Access('SysAdmin')) echo "<li><a href=General.php>General Settings</a> \n";
//    if (Access('Committee','OldAdmin')) echo "<li><a href=/admin/index.php>Original Admin (James's)</a> \n";
    echo "</ul>\n";

  echo "</table>\n";

  dotail();
?>

