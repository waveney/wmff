<?php
  include_once("fest.php");
  /* Remove any Participant overlay */
  if (isset($_COOKIE{'WMFFD'})) {
    unset($_COOKIE{'WMFFD'});
    setcookie('WMFFD','',1,'/');
  }
  A_Check('Upload');
  $host= "https://" . $_SERVER['HTTP_HOST'];

  dostaffhead("Staff Pages", ["/js/jquery.typeahead.min.js", "/css/jquery.typeahead.min.css", "/js/Staff.js"]);

  global $YEAR,$PLANYEAR,$YEARDATA;
  include_once("ProgLib.php");
  include_once("TradeLib.php");
  $Years = Get_Years();
  $Days = array('All','Sat','Sun','&lt;Sat','Sat&amp;Sun');
  $Heads = [];

  function StaffTable($Section,$Heading,$cols=1) {
    global $Heads;
    static $ColNum = 3;
    $txt = '';
    if ($Section != 'Any' && !Capability("Enable$Section")) return '';
    $Heads[] = $Heading;
    if ($ColNum+$cols > 3) {
      $txt .= "<tr>";
      $ColNum =0;
    }
    $hnam = preg_replace("/[^A-Za-z0-9]/", '', $Heading);
    $txt .= "<td class=Stafftd colspan=$cols >";
    $txt .= "<h2 id='Staff$hnam'>$Heading</h2>";
    $ColNum+=$cols;
    return $txt;
  }

  if (isset($ErrorMessage)) echo "<h2 class=ERR>$ErrorMessage</h2>";

//echo php_ini_loaded_file() . "<P>";

  echo "<div class=floatright><h2>";
  if (isset($Years[$YEARDATA['PrevFest']])) echo "<a href=Staff?Y=" . $YEARDATA['PrevFest'] .">" . $YEARDATA['PrevFest'] . "</a> &nbsp; ";
  if (isset($Years[$YEARDATA['NextFest']])) echo "<a href=Staff?Y=" . $YEARDATA['NextFest'] .">" . $YEARDATA['NextFest'] . "</a>\n";
  echo "</h2></div>";
  echo "<h2>Staff Pages - $YEAR <span style='font-size:16;font-weight:normal;'>For other years select &gt;&gt;&gt;</span></h2>\n";
  
  $txt = "<div class=tablecont><table border width=100% class=Staff style='min-width:800px'>\n";
   
  if ($x = StaffTable('Docs','Document Storage')) {
    $txt .= $x;
      $txt .= "<ul>\n";
      if (Access('Staff')) {
        $txt .= "<li><a href=Dir>View Document Storage</a>\n";
        $txt .= "<li><a href=Search>Search Document Storage</a>\n";
      }
      $txt .= "<p>";
//      $txt .= "<li><a href=ProgrammeDraft1.pdf>Programme Draft</a>\n";
      $txt .= "<li><a href=StaffHelp>General Help</a>\n";

      if (Access('SysAdmin')) {
        $txt .= "<p>";
        $txt .= "<li class=smalltext><a href=DirRebuild?SC>Scan Directories - Report File/Database discrepancies</a>";    
//      $txt .= "<li><a href=DirRebuild?FI>Rebuild Directorys - Files are YEARDATA</a>";
//      $txt .= "<li><a href=DirRebuild?DB>Rebuild Directorys - Database is YEARDATA</a>";
      }
      $txt .= "</ul>\n";
    }
    
// *********************** TIMELINE ****************************************************
  if ($x = StaffTable('TLine','Timeline')) {
    $txt .= $x;
    $txt .= "<ul>\n";
    $txt .= "<li><a href=TimeLine?Y=$YEAR>Time Line Management</a>\n<p>";
    $txt .= "<li><a href=TLHelp>Timeline Help</a>\n";
//    $txt .= "<li>Timeline Stats\n";
    if (Access('SysAdmin')) {
      $txt .= "<p>";
//      $txt .= "<li class=smalltext><a href=TLImport1>Timeline Import 1</a>\n";
      }
    $txt .= "</ul><p>\n";
  }

// *********************** Users  **************************************************************
  if ($x = StaffTable('Any','Users')) {
    $txt .= $x;
    $txt .= "<ul>\n";
    $txt .= "<li><a href=Login?ACTION=NEWPASSWD>New Password</a>\n";
    if (Access('Committee','Users')) {
      $txt .= "<li><a href=AddUser>Add User</a>";
      $txt .= "<li><a href=ListUsers?FULL>List Committee/Group Users</a>";
      $txt .= "<li><a href=UserDocs>Storage Used</a>";
      $txt .= "<li><a href=ContactCats>Contact Categories</a>";      
    } else {
      $txt .= "<li><a href=ListUsers>List Committee/Group Users</a>";    
    }
    $txt .= "</ul><p>\n";
  }

// *********************** MUSIC ****************************************************
  if ($x = StaffTable('Music','Music')) {
    $txt .= $x;
    $txt .= "<ul>\n";
    $txt .= "<li><a href=MusicFAQ>Music FAQ</a>\n";
    if (Access('Staff')) {
      $txt .= "<li><a href=ListMusic?SEL=ALL&Y=$YEAR&T=M>List All Music Acts in Database</a>\n";
      $txt .= "<li><a href=ListMusic?SEL=Booking&Y=$YEAR&T=M>List Music Acts Booking</a>\n";
//      $txt .= "<li>Music Acts Summary"; //<a href=MusicSummary?Y=$YEAR>Music Acts Summary</a>\n";
    }
    if (Access('Staff','Music')) {
//      $txt .= "<li>Invite Music Acts\n";
      $txt .= "<li><a href=CreatePerf?T=Music&Y=$YEAR>Add Music Act to Database</a>";
/*
//      if ($YEAR == $PLANYEAR) $txt .= "<li><a href=MusicProg?>Edit Music Programming</a>";
*/
//      $txt .= "<li>Edit Music Programming";
      if (Access('SysAdmin')) {
        $txt .= "<li><a href=ShowMusicProg?Y=$YEAR>View Music Programming\n</a>"; 
      } else {
//        $txt .= "<li>View Music Programming\n"; 
      }
    } else {
//      $txt .= "<li><a href=ShowMusicProg?Y=$YEAR>View Music Programme</a>";
    }
    if (Access('SysAdmin')) {
      $txt .= "<p><div class=tablecont><table><tr><td>";
      $txt .= "<li class=smalltext><a href=ShowMusicProg?Pub=1&Y=$YEAR>Public Music Programme</a>";
      $txt .= "<li class=smalltext><a href=MusicTypes>Set Music Types</a>";
//      $txt .= "<li class=smalltext><a href=ResetImageSizes?PERF>Scan and save Image sizes</a>";
//      $txt .= "<li class=smalltext><a href=CopyActYear>Copy all ActYear data to SideYear</a>";
      $txt .= "</table></div><p>\n";
    }
    $txt .= "<li><a href=ContractView?t=1>Dummy Music Contract</a>";
    $txt .= "<li><a href=LiveNLoudView?Y=$YEAR>Show Live N Loud applications</a>";
    $txt .= "<li><a href=BuskersBashView?Y=$YEAR>Show Buskers Bash applications</a>";
//    if (Access('SysAdmin')) $txt .= "<li class=smalltext><a href=LiveNLoudEmail>Send LNL bulk email</a>";
    $txt .= "</ul>\n";
  }
  
// *********************** DANCE ****************************************************
  if ($x = StaffTable('Dance','Dance',2)) {
    $txt .= $x;
    $txt .= "<ul>\n";
    if (Access('Staff','Dance')) {
      $txt .= "<li><a href=InviteDance?Y=$YEAR>Invite Dance Sides</a>\n";
      $txt .= "<li><a href=InviteDance?Y=$YEAR&INVITED>List Ongoing Dance Sides</a>\n";
    }
    if (Access('Staff')) {

      $txt .= "<li><a href=ListDance?SEL=Coming&Y=$YEAR>List Dance Sides Coming</a>\n";
      $txt .= "<li><a href=DanceSummary?Y=$YEAR>Dance Sides Summary</a>\n";
    }
    if (Access('Staff','Dance')) $txt .= "<li><a href=CreatePerf?T=Dance&Y=$YEAR>Add Dance Side to Database</a>";

//      $txt .= "<li><input class=typeahead type=text placeholder='Find a Side'>\n";
    if (0 && Access('SysAdmin')) {
      $txt .= "<li>";
//        $txt .= "<form id=form-sidefind name=form-sidefind>\n";
      $txt .= "<span class=typeahead__container><span class=typeahead__field>\n";
      $txt .= "<span class=typeahead__query><input class=findaside name=sidefind type=search placeholder='Find Side' autocomplete=off></span>";
      $txt .= "<span class=typeahead__button><button type=submit><i class=typeahead__search-icon></i></button></span>\n";
      $txt .= "</span>"; //</span>";
//        $txt .= "</form>\n"; 
    } else {
//      $txt .= "<li>Find a Side\n";
//         $txt .= "<li><input class=typeahead type=text placeholder='Find a Side'>\n";
    }

    if (Access('Staff'))  $txt .= "<li><a href=ListDance?SEL=ALL&Y=$YEAR>List All Dance Sides in Database</a>\n";
    $txt .= "<li><a href=DanceFAQ>Dance FAQ</a>\n";
    if (Access('Staff','Dance')) {
      if ($YEAR == $PLANYEAR) {
        /* $txt .= "<li><a href=DanceProg?Y=$YEAR>Edit Dance Programme</a>"; */
        $txt .= "<li><a href=NewDanceProg?Y=$YEAR>Edit Dance Programme</a>";
      } else {
        $txt .= "<li><a href=NewDanceProg?Y=$YEAR&SAND>Edit $YEAR Dance Programme in Sandbox</a>";
      }
    }
    $txt .= "<li><a href=ShowDanceProg?Y=$YEAR>View Dance Programme</a>";
    $txt .= "<li><a href=/Map?F=3>Dance Location Map</a>";

    if (Access('SysAdmin')) {
//      $txt .= "<li><a href=ShowDanceProg?Y=$YEAR>View Dance Programme</a>";
      $txt .= "<p><div class=tablecont><table class=FullWidth><tr><td width=300px>";
      $txt .= "<li class=smalltext><a href=ShowDanceProg?Cond=1&Y=$YEAR>Condensed Dance Programme</a>";
      $txt .= "<li class=smalltext><a href=DanceCheck?Y=$YEAR>Dance Checking</a>";
      $txt .= "<li class=smalltext><a href=DanceTypes>Set Dance Types</a>";
      $txt .= "<li class=smalltext><a href=LineUpDance?MIN&Y=$YEAR>Picture free List of Dance Sides Coming</a>\n";
//      $txt .= "<li class=smalltext><a href=ModifyDance2>Modify Dance Structure #2</a>\n";
      $txt .= "<li class=smalltext><a href=WhereDance?Y=$YEAR>Where did Dance Sides Come from</a>\n";
      $txt .= "<td width=300px>";
      $txt .= "<li class=smalltext><a href=PrintLabels?Y=$YEAR>Print Address Labels</a>";
      $txt .= "<li class=smalltext><a href=CarPark?Y=$YEAR>Car Park Tickets</a>";
      if ($YEAR == $PLANYEAR) $txt .= "<li class=smalltext><a href=WristbandsSent>Mark Wristbands Sent</a>";
      $txt .= "<li class=smalltext><a href=ShowDanceProg?Cond=1&Pub=1&Y=$YEAR>Public Dance Programme</a>";
      $txt .= "<li class=smalltext><a href=FixBug2?Y=$YEAR>Change order of message records</a>";
      $txt .= "<td width=300px>";
      $txt .= "<li class=smalltext><a href=ShowDanceProg?Cond=0&Pub=1&Head=0&Day=Sat&Y=$YEAR>Dance Programme - Sat - no headers</a>";
      $txt .= "<li class=smalltext><a href=ShowDanceProg?Cond=0&Pub=1&Head=0&Day=Sun&Y=$YEAR>Dance Programme - Sun - no headers</a>";
      $txt .= "<li class=smalltext><a href=CheckDuplicates?Y=$YEAR>Check for Duplicate Year Tables Entries</a>";      
//      $txt .= "<li class=smalltext><a href=ImportDance2>Import Appalachian List</a>"; // Should never be needed again
      $txt .= "<li class=smalltext><a href=CheckAccessKeys>Check and fix Blank Access Keys</a>"; 
      $txt .= "<li class=smalltext><a href=ResetImageSizes>Scan and save all Perf Image sizes</a>";
      $txt .= "</table></div>\n";
    }
    $txt .= "</ul>\n";
  }
// *********************** Comedy, Childrens Ent, Other Perf
  if ($x = StaffTable('Comedy','Comedy')) {
    $txt .= $x;
    $txt .= "<ul>\n";    
    if (Access('Staff')) {
      $txt .= "<li><a href=ListMusic?SEL=ALL&Y=$YEAR&T=C>List All Comedy Performers in Database</a>\n";
      $txt .= "<li><a href=ListMusic?SEL=Booking&Y=$YEAR&T=C>List Comedy Performers Booking</a>\n";
    }
    if (Access('Staff','Comedy')) {
      $txt .= "<li><a href=CreatePerf?T=C&Y=$YEAR>Add Comedy Performer to Database</a>";
    }
    $txt .= "</ul>\n";
  }
  if ($x = StaffTable('Family',"Children's Entertainers")) {
    $txt .= $x;
    $txt .= "<ul>\n";    
    if (Access('Staff')) {
      $txt .= "<li><a href=ListMusic?SEL=ALL&Y=$YEAR&T=Y>List All Children's Entertainers in Database</a>\n";
      $txt .= "<li><a href=ListMusic?SEL=Booking&Y=$YEAR&T=Y>List Children's Entertainers Booking</a>\n";
    }
    if (Access('Staff','Family')) {
      $txt .= "<li><a href=CreatePerf?T=Y&Y=$YEAR>Add Children's Entertainers to Database</a>";
    }
    $txt .= "</ul>\n";
  }
  if ($x = StaffTable('OtherPerf', 'Other Performers')) {
    $txt .= $x;
    $txt .= "<ul>\n";    
    if (Access('Staff')) {
      $txt .= "<li><a href=ListMusic?SEL=ALL&Y=$YEAR&T=O>List All Other Performers in Database</a>\n";
      $txt .= "<li><a href=ListMusic?SEL=Booking&Y=$YEAR&T=O>List Other Performers Booking</a>\n";
    }
    if (Access('Staff','Other')) {
      $txt .= "<li><a href=CreatePerf?T=O&Y=$YEAR>Add Other Performer to Database</a>";
    }
    $txt .= "</ul>\n";
  }
    

// *********************** STALLS   ****************************************************
  if ($x = StaffTable('Trade','Trade')) {
    $txt .= $x;
    $Tlocs = Get_Trade_Locs(0,"WHERE InUse=1");
    $txt .= "<ul>\n";
    $txt .= "<li><a href=ListCTrade?Y=$YEAR>List Active Traders This Year</a>\n";
    $txt .= "<li><a href=ListTrade?Y=$YEAR>List All Traders</a>\n";
    $txt .= "<li><a href=TradeFAQ>Trade FAQ</a>\n";
    $txt .= "<li><a href=ListCTrade?Y=$YEAR&SUM>Traders Summary</a>\n";

    $txt .= "<li><form method=Post action=TradeStandMap class=staffform>";
      $txt .= "<input type=submit name=l value='Trade Stand Map' id=staffformid>" . 
                fm_hidden('Y',$YEAR) .
                fm_select($Tlocs,0,'l',0," onchange=this.form.submit()") . "</form>\n";

    $txt .= "<li><a href=TradeShow>Trade Show</a>\n";
    if (Access('Committee','Trade')) {
      $txt .= "<li><a href=Trade?Y=$YEAR>Add Trader</a>\n";
      $txt .= "<li><form method=Post action=TradeAssign class=staffform>";
      $txt .= "<input type=submit name=l value='Trade Pitch Assign' id=staffformid>" . 
                fm_hidden('Y',$YEAR) .
                fm_select($Tlocs,0,'i',0," onchange=this.form.submit()") . "</form>\n";

      $txt .= "<li><a href=TradeLocs?Y=$YEAR>Trade Locations</a>\n";
//      $txt .= "<li><a href=TradeSetup>Trade Pitch Setup</a>\n";
      if (Access('SysAdmin')) $txt .= "<li><a href=TradeTypes>Trade Types and base Prices</a>\n";
      if (Access('SysAdmin')) $txt .= "<li><a href=EmailTraders>Email Groups of Traders</a>\n"; // Old code needs lots of changes
      if (Access('SysAdmin')) $txt .= "<li><a href=TradeDateChange>Bump Trade Year Data to new dates</a>\n";
//      if (Access('SysAdmin')) $txt .= "<li><a href=TradeImport2>Merge Mandy's Trade Data</a>\n";
//      if (Access('SysAdmin')) $txt .= "<li><a href=TradeImport3>Fix Access Keys</a>\n";
//      $txt .= "<li><a href=/admin/trade/index>Old Trade Stand Section</a>\n";
      $txt .= "<li><a href=Trade2CSV?Y=$YEAR>Traders as CSV</a>\n";
    }
    if (Capability('EnableTrade') && !Capability('EnableFinance')) $txt .= "<li><a href=InvoiceManage?Y=$YEAR>Invoice/Payment Management</a>\n";
    if (Access('SysAdmin')) {
      $txt .= "<p><div class=tablecont><table><tr><td>";
      $txt .= "<li class=smalltext><a href=ResetImageSizes?TRADE>Scan and save Image sizes</a>";
      $txt .= "</table></div><p>\n";
    }
    $txt .= "</ul>\n";
  }
  
// *********************** VENUES & EVENTS *******************************************************
  $_POST['DAYS'] = 0; $_POST['Pics'] = 1;
  if ($x = StaffTable('Events','Events and Venues',2)) {
    $txt .= $x;
    $Vens = Get_AVenues();
    $txt .= "<ul>\n";
    $txt .= "<li><a href=VenueList?Y=$YEAR>List Venues</a>\n";
    $txt .= "<li><a href=EventList?Y=$YEAR>List All Events</a>\n";
    if (Access('Staff','Venues') && $YEAR==$PLANYEAR) $txt .= "<li><a href=EventAdd>Create Event(s)</a>";
    
    $txt .= "<li><form method=Post action=EventList class=staffform>";
      $txt .= "<input type=submit name=a value='List Events at' id=staffformid>" . 
                fm_hidden('Y',$YEAR) .
                fm_select($Vens,0,'V',0," onchange=this.form.submit()") . "</form>\n";

    $txt .= "<li><form method=Post action=VenueShow?Mode=1 class=staffform>";
      $txt .= "<input type=submit name=a value='Show Events at' id=staffformid>" . 
                fm_hidden('Y',$YEAR) .
                fm_select($Vens,0,'v',0," onchange=this.form.submit()") . " - A public view of events even if they are not public</form>\n";
    $txt .= "<li><form method=Post action=VenueShow?Poster=1 class=staffform>";
      $txt .= "<input type=submit name=a value='Poster For' id=Posterid>" . 
                fm_hidden('Y',$YEAR) .
                fm_select($Vens,0,'v',0," onchange=this.form.submit()") . 
                fm_radio('',$Days,$_POST,'DAYS','',0) . fm_checkbox('Pics',$_POST,'Pics') .
                "</form>\n";

    if (Access('Staff','Venues')) $txt .= "<li><a href=EventTypes>Event Types</a>\n";
    if (Access('Staff','Venues')) $txt .= "<li><a href=VenueComplete?Y=$YEAR>Mark Venues as Complete</a>\n";
    if (Access('SysAdmin')) $txt .= "<li><a href=TicketEvents?Y=$YEAR>List Ticketed Events</a>\n";
    if (Access('Staff')) $txt .= "<li><a href=StewList?Y=$YEAR>List Stewarding Events</a>\n";
    if (Access('Committee','Venues')) $txt .= "<li><a href=MapPoints>Additional Map Points</a>\n";
    if (Access('SysAdmin')) $txt .= "<li><a href=MapPTypes>Map Point Types</a>\n";
    $txt .= "<li><a href=EventSummary?Y=$YEAR>Event Summary</a>\n";
    $txt .= "<li><form method=Post action=PAShow class=staffform>";
      $txt .= "<input type=submit name=a value='PA Requirements for' id=staffformid>" . 
                fm_hidden('Y',$YEAR) .
                fm_select($Vens,0,'pa4v',0," onchange=this.form.submit()") . "</form>\n";

//    if (Access('SysAdmin')) $txt .= "<li><a href=BusTimes>Fetch and Cache Bus Times</a>\n";
//    if (Access('SysAdmin')) $txt .= "<li><a href=ConvertEvents>Convert Old Format Events to New Format Events</a>\n";
    if (Access('SysAdmin')) $txt .= "<li><a href=AddVenue?NEWACCESS onClick=\"javascript:return confirm('are you sure you update these?');\">Generate New Access Keys for Venues</a>\n";
    if ($YEAR == $PLANYEAR && Access('Staff')) $txt .= "<li><a href=VenueActive>Refresh Active Venue List</a>\n";
    $txt .= "<li><form method=Post action=/WhatsOnNow class=staffform>";
      $txt .= "<input type=submit name=a value='Whats On At ' id=staffformid>" . 
                fm_hidden('Y',$YEAR) . fm_text0('',$_POST,'AtTime') .' on ' . fm_text0('',$_POST,'AtDate');
    
    $txt .= "</ul>\n";
  }
  
// *********************** Misc *****************************************************************
  if ($x = StaffTable('Misc','Misc')) {
    $txt .= $x;
    $txt .= "<ul>\n";
//    $txt .= "<li><a href=StewardView>Stewarding Applications (old)</a>\n";
    $txt .= "<li><a href=Volunteers?A=New>Volunteering Application Form</a>\n";
    $txt .= "<li><a href=Volunteers?A=List>Volunteers (new)</a>\n";
    if (Access('Staff','Photos')) {
      $txt .= "<li><a href=PhotoUpload>Photo Upload</a>";
      $txt .= "<li><a href=PhotoManage>Photo Manage</a>";
      $txt .= "<li><a href=GallManage>Gallery Manage</a>";
    }
    $txt .= "<p>";
    
//    $txt .= "<li><a href=LaughView?Y=$YEAR>Show Laugh Out Loud applications</a>";
    if (Access('Committee')) $txt .= "<li><a href=Campsite?Y=$YEAR>Manage Campsite Use</a>\n"; 
    if (Access('Staff')) $txt .= "<li><a href=CarerTickets?Y=$YEAR>Manage Carer / Partner Tickets</a>\n"; 
    if (Access('Staff','Sponsors')) $txt .= "<li><a href=TaxiCompanies>Manage Taxi Company List</a>\n"; 
    if (Access('SysAdmin')) $txt .= "<li><a href=ConvertPhotos>Convert Archive Format</a>";
//    $txt .= "<li><a href=ContractView>Dummy Music Contract</a>";
    $txt .= "</ul>\n";
  }

// *********************** Finance **************************************************************
  if ($x = StaffTable('Finance','Finance and Sponsors')) {
    $txt .= $x;
    $txt .= "<ul>\n";
    if (Access('Committee','Finance')) {
      $txt .= "<li><a href=BudgetManage?Y=$YEAR>Budget Management</a>\n";
      $txt .= "<li><a href=InvoiceManage?Y=$YEAR>Invoice/Payment Management</a>\n";
      $txt .= "<li><a href=InvoiceManage?ACTION=NEW>New Invoice</a>\n";   
      $txt .= "<li><a href=InvoiceCodes?Y=$YEAR>Invoice Codes</a>\n";   
      $txt .= "<li><a href=InvoiceSummary?Y=$YEAR>Invoice Summary</a>\n";   
      $txt .= "<li><a href=OtherPaymentSummary?Y=$YEAR>Other Payment Summary</a>\n";   
      $txt .= "<li><a href=ListTrade?ORGS>Businesses and Organistaions List</a>\n"; 
      $txt .= "<li><a href=Trade?ORGS>New Business or Organistaion</a>\n";  
      $txt .= "<li><a href=Payments?Y=$YEAR>List All Performer Payments</a>\n";  
    } elseif (Access('Committee')) {
      $txt .= "<li><a href=BudgetManage?Y=$YEAR>Budget View</a>\n";
      $txt .= "<li><a href=InvoiceManage?Y=$YEAR>Invoice Management</a>\n";
    }
    if (Access('SysAdmin')) {
//      $txt .= "<p>";
//      $txt .= "<li class=smalltext><a href=ImportDebtorCodes>Import Debtor Codes</a>";
//      $txt .= "<li class=smalltext><a href=ImportProgAds>Import Programme ads</a>\n";  
//      $txt .= "<p>";
      $txt .= "<li><a href=Sponsors>Sponsors</a>\n";
      $txt .= "<li><a href=WaterManage>Water Refills</a>\n";

//      $txt .= "<li><a href=ImportOldInvoice>Import Old Invoices</a>\n";  
    }
    $txt .= "</ul>\n";
  }
    
// *********************** Art & Craft *********************************************************
  if ($x = StaffTable('Craft','Art and Craft')) {
    $txt .= $x;
    $txt .= "<h2></h2>\n";
    $txt .= "<ul>\n";
    $txt .= "<li><a href=ArtForm>Art Application Form</a>\n";
    $txt .= "<li><a href=ArtView>Show Art Applications</a>\n";
    $txt .= "</ul>";
  }

// *********************** GENERAL ADMIN *********************************************************
  if ($x = StaffTable('Any','General Admin')) {
    $txt .= $x;
    $txt .= "<ul>\n";

    if (Capability('EnableAdmin') && Access('Committee','News')) {
//      $txt .= "<li><a href=NewsManage>News Management</a>";
      $txt .= "<li><a href=ListArticles>Front Page Article Management</a>";
      $txt .= "<li><a href=LinkManage>Manage Other Fest Links</a>\n";
    }
    if (Access('Steward')) {
      $txt .= "<li><a href=AddBug>New Bug/Feature request</a>\n";
      $txt .= "<li><a href=ListBugs>List Bugs/Feature requests</a><p>\n";
    }

    if (Access('Staff')) $txt .= "<li><a href=TEmailProformas>EMail Proformas</a>";
    if (Access('Staff')) $txt .= "<li><a href=AdminGuide>Admin Guide</a> \n";
    if (Access('SysAdmin')) {
//      $txt .= "<li><a href=BannerManage>Manage Banners</a> \n";
      if ( Capability("EnableMusic") || Capability("EnableMusic")) $txt .= "<li><a href=PerformerTypes?Y=$YEAR>Performer Types</a> \n";
      $txt .= "<li><a href=YearData?Y=$YEAR>General Year Settings</a> \n";
      $txt .= "<li><a href=MasterData>Festival System Data Settings</a> \n";
    }
    $txt .= "</ul>\n";
  }

  $txt .= "</table></div>\n";

  echo "<h3>Jump to: ";
  $d = 0;
  foreach ($Heads as $Hd) {
    $hnam = preg_replace("/[^A-Za-z0-9]/", '', $Hd);
    $Hd = preg_replace("/ /",'&nbsp;',$Hd);
//    if ($d++) echo ", ";
    echo "&gt;&nbsp;<a href='#Staff$hnam'>$Hd</a> ";
  }
  echo "</h3><br>";
  echo $txt;
  dotail();
?>

