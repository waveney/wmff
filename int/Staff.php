<?php
  include_once("fest.php");
  /* Remove any Participant overlay */
  if (isset($_COOKIE{'WMFFD'})) {
    unset($_COOKIE{'WMFFD'});
    setcookie('WMFFD','',1,'/');
  }
  A_Check('Upload');
  $host= "https://" . $_SERVER['HTTP_HOST'];

  dostaffhead("Staff Pages", "/js/jquery.typeahead.min.js", "/css/jquery.typeahead.min.css", "/js/Staff.js");

  global $YEAR,$PLANYEAR;
  include_once("ProgLib.php");
  $Years = Get_Years();
  $Days = array('All','Sat','Sun','&lt;Sat','Sat&amp;Sun');

  if (isset($ErrorMessage)) echo "<h2 class=ERR>$ErrorMessage</h2>";

//echo php_ini_loaded_file() . "<P>";

  echo "<div class=floatright><h2>";
  if (isset($Years[$YEAR-1])) echo "<a href=Staff.php?Y=" . ($YEAR-1) .">" . ($YEAR-1) . "</a> &nbsp; ";
  if (isset($Years[$YEAR+1])) echo "<a href=Staff.php?Y=" . ($YEAR+1) .">" . ($YEAR+1) . "</a>\n";
  echo "</h2></div>";
  echo "<h2>Staff Pages - $YEAR</h2>\n";
  echo "<table border width=90% class=Staff>\n";
  echo "<tr><td class=Stafftd><h2>Document Storage</h2>\n";
    echo "<ul>\n";
    if (Access('Staff')) {
      echo "<li><a href=Dir.php>View Document Storage</a>\n";
      echo "<li><a href=Search.php>Search Document Storage</a>\n";
    }
    echo "<p>";
//    echo "<li><a href=ProgrammeDraft1.pdf>Programme Draft</a>\n";
    echo "<li><a href=StaffHelp.php>General Help</a>\n";

    if (Access('SysAdmin')) {
      echo "<p>";
      echo "<li class=smalltext><a href=DirRebuild.php?SC>Scan Directories - Report File/Database discrepancies</a>";    
//      echo "<li><a href=DirRebuild.php?FI>Rebuild Directorys - Files are master</a>";
//      echo "<li><a href=DirRebuild.php?DB>Rebuild Directorys - Database is master</a>";
    }
    echo "</ul>\n";

// *********************** TIMELINE ****************************************************
  echo "<td class=Stafftd><h2>Timeline</h2>\n";
    echo "<ul>\n";
    echo "<li><a href=TimeLine.php?Y=$YEAR>Time Line Management</a>\n<p>";
    echo "<li><a href=TLHelp.php>Timeline Help</a>\n";
//    echo "<li>Timeline Stats\n";
    if (Access('SysAdmin')) {
      echo "<p>";
//      echo "<li class=smalltext><a href=TLImport1.php>Timeline Import 1</a>\n";
    }
    echo "</ul><p>\n";

// *********************** Users  **************************************************************
  echo "<td class=Stafftd><h2>Users</h2>\n";
    echo "<ul>\n";
    echo "<li><a href=Login.php?ACTION=NEWPASSWD>New Password</a>\n";
    if (Access('Committee','Users')) {
      echo "<li><a href=AddUser.php>Add User</a>";
      echo "<li><a href=ListUsers.php?FULL>List Committee/Group Users</a>";
      echo "<li><a href=UserDocs.php>Storage Used</a>";
    } else {
      echo "<li><a href=ListUsers.php>List Committee/Group Users</a>";    
    }
    echo "</ul><p>\n";

// *********************** MUSIC ****************************************************
  echo "<tr>";
    echo "<td class=Stafftd><h2>Music</h2>\n";
    echo "<ul>\n";
    echo "<li><a href=MusicFAQ.php>Music FAQ</a>\n";
    if (Access('Staff')) {
      echo "<li><a href=ListMusic.php?SEL=ALL&Y=$YEAR&T=M>List All Music Acts in Database</a>\n";
      echo "<li><a href=ListMusic.php?SEL=Booking&Y=$YEAR&T=M>List Music Acts Booking</a>\n";
//      echo "<li>Music Acts Summary"; //<a href=MusicSummary.php?Y=$YEAR>Music Acts Summary</a>\n";
    }
    if (Access('Staff','Music')) {
//      echo "<li>Invite Music Acts\n";
      echo "<li><a href=CreatePerf.php?T=Music&Y=$YEAR>Add Music Act to Database</a>";
/*
//      if ($YEAR == $PLANYEAR) echo "<li><a href=MusicProg.php?>Edit Music Programming</a>";
*/
//      echo "<li>Edit Music Programming";
      if (Access('SysAdmin')) {
        echo "<li><a href=ShowMusicProg.php?Y=$YEAR>View Music Programming\n</a>"; 
      } else {
//        echo "<li>View Music Programming\n"; 
      }
    } else {
//      echo "<li><a href=ShowMusicProg.php?Y=$YEAR>View Music Programme</a>";
    }
    if (Access('SysAdmin')) {
      echo "<p><table><tr><td>";
      echo "<li class=smalltext><a href=ShowMusicProg.php?Pub=1&Y=$YEAR>Public Music Programme</a>";
      echo "<li class=smalltext><a href=MusicTypes.php>Set Music Types</a>";
//      echo "<li class=smalltext><a href=ResetImageSizes.php?PERF>Scan and save Image sizes</a>";
//      echo "<li class=smalltext><a href=CopyActYear.php>Copy all ActYear data to SideYear</a>";
      echo "</table><p>\n";
    }
    echo "<li><a href=ContractView.php?t=1>Dummy Music Contract</a>";
    echo "<li><a href=LiveNLoudView.php?Y=$YEAR>Show Live N Loud applications</a>";
    echo "<li><a href=BuskersBashView.php?Y=$YEAR>Show Buskers Bash applications</a>";
//    if (Access('SysAdmin')) echo "<li class=smalltext><a href=LiveNLoudEmail.php>Send LNL bulk email</a>";
    echo "</ul>\n";

// *********************** DANCE ****************************************************
  echo "<td class=Stafftd colspan=2><h2>Dance</h2>\n";
    echo "<ul>\n";
    if (Access('Staff','Dance')) {
      echo "<li><a href=InviteDance.php?Y=$YEAR>Invite Dance Sides</a>\n";
      echo "<li><a href=InviteDance.php?Y=$YEAR&INVITED>List Ongoing Dance Sides</a>\n";
    }
    if (Access('Staff')) {

      echo "<li><a href=ListDance.php?SEL=Coming&Y=$YEAR>List Dance Sides Coming</a>\n";
      echo "<li><a href=DanceSummary.php?Y=$YEAR>Dance Sides Summary</a>\n";
    }
    if (Access('Staff','Dance')) echo "<li><a href=CreatePerf.php?T=Dance&Y=$YEAR>Add Dance Side to Database</a>";

//      echo "<li><input class=typeahead type=text placeholder='Find a Side'>\n";
    if (0 && Access('SysAdmin')) {
      echo "<li>";
//        echo "<form id=form-sidefind name=form-sidefind>\n";
      echo "<span class=typeahead__container><span class=typeahead__field>\n";
      echo "<span class=typeahead__query><input class=findaside name=sidefind type=search placeholder='Find Side' autocomplete=off></span>";
      echo "<span class=typeahead__button><button type=submit><i class=typeahead__search-icon></i></button></span>\n";
      echo "</span>"; //</span>";
//        echo "</form>\n"; 
    } else {
//      echo "<li>Find a Side\n";
//         echo "<li><input class=typeahead type=text placeholder='Find a Side'>\n";
    }

    if (Access('Staff'))  echo "<li><a href=ListDance.php?SEL=ALL&Y=$YEAR>List All Dance Sides in Database</a>\n";
    echo "<li><a href=DanceFAQ.php>Dance FAQ</a>\n";
    if (Access('Staff','Dance')) {
      if ($YEAR == $PLANYEAR) {
        /* echo "<li><a href=DanceProg.php?Y=$YEAR>Edit Dance Programme</a>"; */
        echo "<li><a href=NewDanceProg.php?Y=$YEAR>Edit Dance Programme</a>";
      } else {
        echo "<li><a href=NewDanceProg.php?Y=$YEAR&SAND>Edit $YEAR Dance Programme in Sandbox</a>";
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
      echo "<li class=smalltext><a href=PrintLabels.php?Y=$YEAR>Print Address Labels</a>";
      echo "<li class=smalltext><a href=CarPark.php?Y=$YEAR>Car Park Tickets</a>";
      if ($YEAR == $PLANYEAR) echo "<li class=smalltext><a href=WristbandsSent.php>Mark Wristbands Sent</a>";
      echo "<li class=smalltext><a href=ShowDanceProg.php?Cond=1&Pub=1&Y=$YEAR>Public Dance Programme</a>";
      echo "<li class=smalltext><a href=ShowDanceProg.php?Cond=0&Pub=1&Head=0&Day=Sat&Y=$YEAR>Dance Programme - Sat - no headers</a>";
      echo "<li class=smalltext><a href=ShowDanceProg.php?Cond=0&Pub=1&Head=0&Day=Sun&Y=$YEAR>Dance Programme - Sun - no headers</a>";
      echo "<li class=smalltext><a href=CheckDuplicates.php?Y=$YEAR>Check for Duplicate Year Tables Entries</a>";      
//      echo "<li class=smalltext><a href=ImportDance2.php>Import Appalachian List</a>"; // Should never be needed again
      echo "</table>\n";
    }
    echo "</ul>\n";
// *********************** Comedy, Childrens Ent, Other Perf
  echo "<tr>";
  echo "<td class=Stafftd><h2>Comedy</h2>\n";
    echo "<ul>\n";    
    if (Access('Staff')) {
      echo "<li><a href=ListMusic.php?SEL=ALL&Y=$YEAR&T=C>List All Comedy Performers in Database</a>\n";
      echo "<li><a href=ListMusic.php?SEL=Booking&Y=$YEAR&T=C>List Comedy Performers Booking</a>\n";
    }
    if (Access('Staff','Comedy')) {
      echo "<li><a href=CreatePerf.php?T=C&Y=$YEAR>Add Comedy Performer to Database</a>";
    }
    echo "</ul>\n";
  echo "<td class=Stafftd><h2>Children's Entertainers</h2>\n";
    echo "<ul>\n";    
    if (Access('Staff')) {
      echo "<li><a href=ListMusic.php?SEL=ALL&Y=$YEAR&T=Y>List All Children's Entertainers in Database</a>\n";
      echo "<li><a href=ListMusic.php?SEL=Booking&Y=$YEAR&T=Y>List Children's Entertainers Booking</a>\n";
    }
    if (Access('Staff','Family')) {
      echo "<li><a href=CreatePerf.php?T=Y&Y=$YEAR>Add Children's Entertainers to Database</a>";
    }
    echo "</ul>\n";
  echo "<td class=Stafftd><h2>Other Performers</h2>\n";
    echo "<ul>\n";    
    if (Access('Staff')) {
      echo "<li><a href=ListMusic.php?SEL=ALL&Y=$YEAR&T=O>List All Other Performers in Database</a>\n";
      echo "<li><a href=ListMusic.php?SEL=Booking&Y=$YEAR&T=O>List Other Performers Booking</a>\n";
    }
    if (Access('Staff','Other')) {
      echo "<li><a href=CreatePerf.php?T=O&Y=$YEAR>Add Other Performer to Database</a>";
    }
    echo "</ul>\n";
    
    

// *********************** STALLS   ****************************************************
  echo "<tr>";
  echo "<td class=Stafftd><h2>Trade </h2>\n";
    echo "<ul>\n";
    echo "<li><a href=ListCTrade.php?Y=$YEAR>List Active Traders This Year</a>\n";
    echo "<li><a href=ListTrade.php?Y=$YEAR>List All Traders</a>\n";
    echo "<li><a href=TradeFAQ.php>Trade FAQ</a>\n";
    echo "<li><a href=ListCTrade.php?Y=$YEAR&SUM>Traders Summary</a>\n";
    
    if (Access('Committee','Trade')) {
      echo "<li><a href=Trade.php?Y=$YEAR>Add Trader</a>\n";
      echo "<li><a href=TradeLocs.php>Trade Locations</a>\n";
      if (Access('SysAdmin')) echo "<li><a href=TradeTypes.php>Trade Types and base Prices</a>\n";
      if (Access('SysAdmin')) echo "<li><a href=EmailTraders.php>Email Groups of Traders</a>\n"; // Old code needs lots of changes
//      if (Access('SysAdmin')) echo "<li><a href=TradeImport1.php>Convert old Trade Data</a>\n";
//      if (Access('SysAdmin')) echo "<li><a href=TradeImport2.php>Merge Mandy's Trade Data</a>\n";
//      if (Access('SysAdmin')) echo "<li><a href=TradeImport3.php>Fix Access Keys</a>\n";
//      echo "<li><a href=/admin/trade/index.php>Old Trade Stand Section</a>\n";
      echo "<li><a href=Trade2CSV.php?Y=$YEAR>Traders as CSV</a>\n";
    }
    if (Access('SysAdmin')) {
      echo "<p><table><tr><td>";
      echo "<li class=smalltext><a href=ResetImageSizes.php?TRADE>Scan and save Image sizes</a>";
      echo "</table><p>\n";
    }
    echo "</ul>\n";

// *********************** VENUES & EVENTS *******************************************************
  $_POST['DAYS'] = 0; $_POST['Pics'] = 1;
  echo "<td class=Stafftd colspan=2><h2>Venues and Events</h2>\n";
    $Vens = Get_AVenues();
    echo "<ul>\n";
    echo "<li><a href=VenueList.php?Y=$YEAR>List Venues</a>\n";
    echo "<li><a href=EventList.php?Y=$YEAR>List All Events</a>\n";
    if (Access('Staff','Venues') && $YEAR==$PLANYEAR) echo "<li><a href=EventAdd.php>Create Event(s)</a>";
    
    echo "<li><form method=Post action=EventList.php class=staffform>";
      echo "<input type=submit name=a value='List Events at' id=staffformid>" . 
                fm_hidden('Y',$YEAR) .
                fm_select($Vens,0,'V',0," onchange=this.form.submit()") . "</form>\n";

    echo "<li><form method=Post action=VenueShow.php?Mode=1 class=staffform>";
      echo "<input type=submit name=a value='Show Events at' id=staffformid>" . 
                fm_hidden('Y',$YEAR) .
                fm_select($Vens,0,'v',0," onchange=this.form.submit()") . " - A public view of events even if they are not public</form>\n";
    echo "<li><form method=Post action=VenueShow.php?Poster=1 class=staffform>";
      echo "<input type=submit name=a value='Poster For' id=Posterid>" . 
                fm_hidden('Y',$YEAR) .
                fm_select($Vens,0,'v',0," onchange=this.form.submit()") . 
                fm_radio('',$Days,$_POST,'DAYS','',0) . fm_checkbox('Pics',$_POST,'Pics') .
                "</form>\n";

    if (Access('Staff','Venues')) echo "<li><a href=EventTypes.php>Event Types</a>\n";
    if (Access('SysAdmin')) echo "<li><a href=TicketEvents.php?Y=$YEAR>List Ticketed Events</a>\n";
    if (Access('Staff')) echo "<li><a href=StewList.php?Y=$YEAR>List Stewarding Events</a>\n";
    if (Access('Committee','Venues')) echo "<li><a href=MapPoints.php>Additional Map Points</a>\n";
    if (Access('SysAdmin')) echo "<li><a href=MapPTypes.php>Map Point Types</a>\n";
    echo "<li><a href=EventSummary.php?Y=$YEAR>Event Summary</a>\n";
    echo "<li><form method=Post action=PAShow.php class=staffform>";
      echo "<input type=submit name=a value='PA Requirements for' id=staffformid>" . 
                fm_hidden('Y',$YEAR) .
                fm_select($Vens,0,'pa4v',0," onchange=this.form.submit()") . "</form>\n";

//    if (Access('SysAdmin')) echo "<li><a href=BusTimes.php>Fetch and Cache Bus Times</a>\n";
//    if (Access('SysAdmin')) echo "<li><a href=ConvertEvents.php>Convert Old Format Events to New Format Events</a>\n";
    echo "</ul>\n";

// *********************** Misc *****************************************************************
  echo "<tr>";
  echo "<td class=Stafftd><h2>Misc</h2>\n";
    echo "<ul>\n";
//    echo "<li><a href=StewardView.php>Stewarding Applications (old)</a>\n";
    echo "<li><a href=Volunteers.php?A=New>Volunteering Application Form</a>\n";
    echo "<li><a href=Volunteers.php?A=List>Volunteers (new)</a>\n";
    if (Access('Staff','Photos')) {
      echo "<li><a href=PhotoUpload.php>Photo Upload</a>";
      echo "<li><a href=PhotoManage.php>Photo Manage</a>";
      echo "<li><a href=GallManage.php>Gallery Manage</a>";
    }
    echo "<p>";
    
//    echo "<li><a href=LaughView.php?Y=$YEAR>Show Laugh Out Loud applications</a>";
    if (Access('Committee')) echo "<li><a href=Campsite.php?Y=$YEAR>Manage Campsite Use</a>\n"; 
    if (Access('Committee')) echo "<li><a href=CarerTickets.php?Y=$YEAR>Manage Carer Tickets</a>\n"; 
    if (Access('Staff','Sponsors')) echo "<li><a href=TaxiCompanies.php>Manage Taxi Company List</a>\n"; 
//    echo "<li><a href=ContractView.php>Dummy Music Contract</a>";
    echo "</ul>\n";

// *********************** Finance **************************************************************
  echo "<td class=Stafftd><h2>Finance and Sponsors</h2>\n";
    echo "<ul>\n";
    if (Access('Committee','Finance')) {
      echo "<li><a href=BudgetManage.php>Budget Management</a>\n";
      echo "<li><a href=InvoiceManage.php>Invoice Management</a>\n";
      if (Access('SysAdmin')) echo "<li><a href=InvoiceManage.php?ACTION=NEW>New Invoice</a>\n";   
      echo "<li><a href=InvoiceCodes.php>Invoice Codes</a>\n";   
      echo "<li><a href=InvoiceSummary.php>Invoice Summary</a>\n";   
      echo "<li><a href=OtherPaymentSummary.php>Other Payment Summary</a>\n";   
      if (Access('SysAdmin')) echo "<li><a href=ListTrade.php?ORGS>Businesses and Organistaions List</a>\n"; 
      if (Access('SysAdmin')) echo "<li><a href=Trade.php?ORGS>New Business or Organistaion</a>\n";  
    } elseif (Access('Committee')) {
      echo "<li><a href=BudgetManage.php>Budget View</a>\n";
      echo "<li><a href=InvoiceManage.php>Invoice Management</a>\n";
    }
    if (Access('SysAdmin')) {
      echo "<p>";
      echo "<li class=smalltext><a href=ImportDebtorCodes.php>Import Debtor Codes</a>";
      echo "<p>";
      echo "<li><a href=Sponsors.php>Sponsors</a>\n";
      echo "<li><a href=WaterManage.php>Water Refills</a>\n";
      echo "<li><a href=InportOldInvoice.php>Import Old Invoices</a>\n";  
    }
    echo "</ul>\n";
    
// *********************** GENERAL ADMIN *********************************************************
  echo "<td class=Stafftd><h2>General Admin</h2>\n";
    echo "<ul>\n";

    if (Access('Committee','News')) {
//      echo "<li><a href=NewsManage.php>News Management</a>";
      echo "<li><a href=ListArticles.php>Front Page Article Management</a>";
      echo "<li><a href=LinkManage.php>Manage Other Fest Links</a>\n";
    }
    if (Access('Steward')) {
      echo "<li><a href=AddBug.php>New Bug/Feature request</a>\n";
      echo "<li><a href=ListBugs.php>List Bugs/Feature requests</a><p>\n";
    }

    if (Access('Staff')) echo "<li><a href=TEmailProformas.php>EMail Proformas</a>";
    if (Access('Staff')) echo "<li><a href=AdminGuide.php>Admin Guide</a> \n";
    if (Access('SysAdmin')) {
      echo "<li><a href=General.php>General Year Settings</a> \n";
      echo "<li><a href=MasterData.php>Master Data Settings</a> \n";
    }
    echo "</ul>\n";


  echo "</table>\n";

  dotail();
?>

