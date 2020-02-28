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

  global $YEAR,$PLANYEAR;
  include_once("ProgLib.php");
  include_once("TradeLib.php");
  $Years = Get_Years();
  $Days = array('All','Sat','Sun','&lt;Sat','Sat&amp;Sun');

  function StaffTable($Section,$cols=1) {
    static $ColNum = 3;
    if ($Section != 'Any' && !Capability("Enable$Section")) return 0;
    if ($ColNum+$cols > 3) {
      echo "<tr>";
      $ColNum =0;
    }
    echo "<td class=Stafftd colspan=$cols>";
    $ColNum+=$cols;
    return 1;
  }

  if (isset($ErrorMessage)) echo "<h2 class=ERR>$ErrorMessage</h2>";

//echo php_ini_loaded_file() . "<P>";

  echo "<div class=floatright><h2>";
  if (isset($Years[$YEAR-1])) echo "<a href=Staff?Y=" . ($YEAR-1) .">" . ($YEAR-1) . "</a> &nbsp; ";
  if (isset($Years[$YEAR+1])) echo "<a href=Staff?Y=" . ($YEAR+1) .">" . ($YEAR+1) . "</a>\n";
  echo "</h2></div>";
  echo "<h2>Staff Pages - $YEAR <span style='font-size:16;font-weight:normal;'>For other years select &gt;&gt;&gt;</span></h2>\n";
  echo "<div class=tablecont><table border width=100% class=Staff style='min-width:800px'>\n";
   
  if (StaffTable('Docs')) {
    echo "<h2>Document Storage</h2>\n";
      echo "<ul>\n";
      if (Access('Staff')) {
        echo "<li><a href=Dir>View Document Storage</a>\n";
        echo "<li><a href=Search>Search Document Storage</a>\n";
      }
      echo "<p>";
//      echo "<li><a href=ProgrammeDraft1.pdf>Programme Draft</a>\n";
      echo "<li><a href=StaffHelp>General Help</a>\n";

      if (Access('SysAdmin')) {
        echo "<p>";
        echo "<li class=smalltext><a href=DirRebuild?SC>Scan Directories - Report File/Database discrepancies</a>";    
//      echo "<li><a href=DirRebuild?FI>Rebuild Directorys - Files are YEARDATA</a>";
//      echo "<li><a href=DirRebuild?DB>Rebuild Directorys - Database is YEARDATA</a>";
      }
      echo "</ul>\n";
    }
    
// *********************** TIMELINE ****************************************************
  if (StaffTable('TLine')) {
    echo "<h2>Timeline</h2>\n";
    echo "<ul>\n";
    echo "<li><a href=TimeLine?Y=$YEAR>Time Line Management</a>\n<p>";
    echo "<li><a href=TLHelp>Timeline Help</a>\n";
//    echo "<li>Timeline Stats\n";
    if (Access('SysAdmin')) {
      echo "<p>";
//      echo "<li class=smalltext><a href=TLImport1>Timeline Import 1</a>\n";
      }
    echo "</ul><p>\n";
  }

// *********************** Users  **************************************************************
  if (StaffTable('Any')) {
    echo "<h2>Users</h2>\n";
    echo "<ul>\n";
    echo "<li><a href=Login?ACTION=NEWPASSWD>New Password</a>\n";
    if (Access('Committee','Users')) {
      echo "<li><a href=AddUser>Add User</a>";
      echo "<li><a href=ListUsers?FULL>List Committee/Group Users</a>";
      echo "<li><a href=UserDocs>Storage Used</a>";
      echo "<li><a href=ContactCats>Contact Categories</a>";      
    } else {
      echo "<li><a href=ListUsers>List Committee/Group Users</a>";    
    }
    echo "</ul><p>\n";
  }

// *********************** MUSIC ****************************************************
  if (StaffTable('Music')) {
    echo "<h2>Music</h2>\n";
    echo "<ul>\n";
    echo "<li><a href=MusicFAQ>Music FAQ</a>\n";
    if (Access('Staff')) {
      echo "<li><a href=ListMusic?SEL=ALL&Y=$YEAR&T=M>List All Music Acts in Database</a>\n";
      echo "<li><a href=ListMusic?SEL=Booking&Y=$YEAR&T=M>List Music Acts Booking</a>\n";
//      echo "<li>Music Acts Summary"; //<a href=MusicSummary?Y=$YEAR>Music Acts Summary</a>\n";
    }
    if (Access('Staff','Music')) {
//      echo "<li>Invite Music Acts\n";
      echo "<li><a href=CreatePerf?T=Music&Y=$YEAR>Add Music Act to Database</a>";
/*
//      if ($YEAR == $PLANYEAR) echo "<li><a href=MusicProg?>Edit Music Programming</a>";
*/
//      echo "<li>Edit Music Programming";
      if (Access('SysAdmin')) {
        echo "<li><a href=ShowMusicProg?Y=$YEAR>View Music Programming\n</a>"; 
      } else {
//        echo "<li>View Music Programming\n"; 
      }
    } else {
//      echo "<li><a href=ShowMusicProg?Y=$YEAR>View Music Programme</a>";
    }
    if (Access('SysAdmin')) {
      echo "<p><div class=tablecont><table><tr><td>";
      echo "<li class=smalltext><a href=ShowMusicProg?Pub=1&Y=$YEAR>Public Music Programme</a>";
      echo "<li class=smalltext><a href=MusicTypes>Set Music Types</a>";
//      echo "<li class=smalltext><a href=ResetImageSizes?PERF>Scan and save Image sizes</a>";
//      echo "<li class=smalltext><a href=CopyActYear>Copy all ActYear data to SideYear</a>";
      echo "</table></div><p>\n";
    }
    echo "<li><a href=ContractView?t=1>Dummy Music Contract</a>";
    echo "<li><a href=LiveNLoudView?Y=$YEAR>Show Live N Loud applications</a>";
    echo "<li><a href=BuskersBashView?Y=$YEAR>Show Buskers Bash applications</a>";
//    if (Access('SysAdmin')) echo "<li class=smalltext><a href=LiveNLoudEmail>Send LNL bulk email</a>";
    echo "</ul>\n";
  }
  
// *********************** DANCE ****************************************************
  if (StaffTable('Dance',2)) {
    echo "<h2>Dance</h2>\n";
    echo "<ul>\n";
    if (Access('Staff','Dance')) {
      echo "<li><a href=InviteDance?Y=$YEAR>Invite Dance Sides</a>\n";
      echo "<li><a href=InviteDance?Y=$YEAR&INVITED>List Ongoing Dance Sides</a>\n";
    }
    if (Access('Staff')) {

      echo "<li><a href=ListDance?SEL=Coming&Y=$YEAR>List Dance Sides Coming</a>\n";
      echo "<li><a href=DanceSummary?Y=$YEAR>Dance Sides Summary</a>\n";
    }
    if (Access('Staff','Dance')) echo "<li><a href=CreatePerf?T=Dance&Y=$YEAR>Add Dance Side to Database</a>";

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

    if (Access('Staff'))  echo "<li><a href=ListDance?SEL=ALL&Y=$YEAR>List All Dance Sides in Database</a>\n";
    echo "<li><a href=DanceFAQ>Dance FAQ</a>\n";
    if (Access('Staff','Dance')) {
      if ($YEAR == $PLANYEAR) {
        /* echo "<li><a href=DanceProg?Y=$YEAR>Edit Dance Programme</a>"; */
        echo "<li><a href=NewDanceProg?Y=$YEAR>Edit Dance Programme</a>";
      } else {
        echo "<li><a href=NewDanceProg?Y=$YEAR&SAND>Edit $YEAR Dance Programme in Sandbox</a>";
      }
    }
    echo "<li><a href=ShowDanceProg?Y=$YEAR>View Dance Programme</a>";
    echo "<li><a href=/Map?F=3>Dance Location Map</a>";

    if (Access('SysAdmin')) {
//      echo "<li><a href=ShowDanceProg?Y=$YEAR>View Dance Programme</a>";
      echo "<p><div class=tablecont><table class=FullWidth><tr><td width=300px>";
      echo "<li class=smalltext><a href=ShowDanceProg?Cond=1&Y=$YEAR>Condensed Dance Programme</a>";
      echo "<li class=smalltext><a href=DanceCheck?Y=$YEAR>Dance Checking</a>";
      echo "<li class=smalltext><a href=DanceTypes>Set Dance Types</a>";
      echo "<li class=smalltext><a href=LineUpDance?MIN&Y=$YEAR>Picture free List of Dance Sides Coming</a>\n";
//      echo "<li class=smalltext><a href=ModifyDance2>Modify Dance Structure #2</a>\n";
      echo "<li class=smalltext><a href=WhereDance?Y=$YEAR>Where did Dance Sides Come from</a>\n";
      echo "<td width=300px>";
      echo "<li class=smalltext><a href=PrintLabels?Y=$YEAR>Print Address Labels</a>";
      echo "<li class=smalltext><a href=CarPark?Y=$YEAR>Car Park Tickets</a>";
      if ($YEAR == $PLANYEAR) echo "<li class=smalltext><a href=WristbandsSent>Mark Wristbands Sent</a>";
      echo "<li class=smalltext><a href=ShowDanceProg?Cond=1&Pub=1&Y=$YEAR>Public Dance Programme</a>";
      echo "<li class=smalltext><a href=FixBug2?Y=$YEAR>Change order of message records</a>";
      echo "<td width=300px>";
      echo "<li class=smalltext><a href=ShowDanceProg?Cond=0&Pub=1&Head=0&Day=Sat&Y=$YEAR>Dance Programme - Sat - no headers</a>";
      echo "<li class=smalltext><a href=ShowDanceProg?Cond=0&Pub=1&Head=0&Day=Sun&Y=$YEAR>Dance Programme - Sun - no headers</a>";
      echo "<li class=smalltext><a href=CheckDuplicates?Y=$YEAR>Check for Duplicate Year Tables Entries</a>";      
//      echo "<li class=smalltext><a href=ImportDance2>Import Appalachian List</a>"; // Should never be needed again
      echo "<li class=smalltext><a href=CheckAccessKeys>Check and fix Blank Access Keys</a>"; 
      echo "</table></div>\n";
    }
    echo "</ul>\n";
  }
// *********************** Comedy, Childrens Ent, Other Perf
  if (StaffTable('Comedy')) {
    echo "<h2>Comedy</h2>\n";
    echo "<ul>\n";    
    if (Access('Staff')) {
      echo "<li><a href=ListMusic?SEL=ALL&Y=$YEAR&T=C>List All Comedy Performers in Database</a>\n";
      echo "<li><a href=ListMusic?SEL=Booking&Y=$YEAR&T=C>List Comedy Performers Booking</a>\n";
    }
    if (Access('Staff','Comedy')) {
      echo "<li><a href=CreatePerf?T=C&Y=$YEAR>Add Comedy Performer to Database</a>";
    }
    echo "</ul>\n";
  }
  if (StaffTable('Family')) {
  echo "<h2>Children's Entertainers</h2>\n";
    echo "<ul>\n";    
    if (Access('Staff')) {
      echo "<li><a href=ListMusic?SEL=ALL&Y=$YEAR&T=Y>List All Children's Entertainers in Database</a>\n";
      echo "<li><a href=ListMusic?SEL=Booking&Y=$YEAR&T=Y>List Children's Entertainers Booking</a>\n";
    }
    if (Access('Staff','Family')) {
      echo "<li><a href=CreatePerf?T=Y&Y=$YEAR>Add Children's Entertainers to Database</a>";
    }
    echo "</ul>\n";
  }
  if (StaffTable('OtherPerf')) {
    echo "<h2>Other Performers</h2>\n";
    echo "<ul>\n";    
    if (Access('Staff')) {
      echo "<li><a href=ListMusic?SEL=ALL&Y=$YEAR&T=O>List All Other Performers in Database</a>\n";
      echo "<li><a href=ListMusic?SEL=Booking&Y=$YEAR&T=O>List Other Performers Booking</a>\n";
    }
    if (Access('Staff','Other')) {
      echo "<li><a href=CreatePerf?T=O&Y=$YEAR>Add Other Performer to Database</a>";
    }
    echo "</ul>\n";
  }
    

// *********************** STALLS   ****************************************************
  if (StaffTable('Trade')) {
  echo "<h2>Trade </h2>\n";
    $Tlocs = Get_Trade_Locs(0,"WHERE InUse=1");
    echo "<ul>\n";
    echo "<li><a href=ListCTrade?Y=$YEAR>List Active Traders This Year</a>\n";
    echo "<li><a href=ListTrade?Y=$YEAR>List All Traders</a>\n";
    echo "<li><a href=TradeFAQ>Trade FAQ</a>\n";
    echo "<li><a href=ListCTrade?Y=$YEAR&SUM>Traders Summary</a>\n";

    echo "<li><form method=Post action=TradeStandMap class=staffform>";
      echo "<input type=submit name=l value='Trade Stand Map' id=staffformid>" . 
                fm_hidden('Y',$YEAR) .
                fm_select($Tlocs,0,'l',0," onchange=this.form.submit()") . "</form>\n";

    echo "<li><a href=TradeShow>Trade Show</a>\n";
    if (Access('Committee','Trade')) {
      echo "<li><a href=Trade?Y=$YEAR>Add Trader</a>\n";
      echo "<li><form method=Post action=TradeAssign class=staffform>";
      echo "<input type=submit name=l value='Trade Pitch Assign' id=staffformid>" . 
                fm_hidden('Y',$YEAR) .
                fm_select($Tlocs,0,'i',0," onchange=this.form.submit()") . "</form>\n";

      echo "<li><a href=TradeLocs?Y=$YEAR>Trade Locations</a>\n";
//      echo "<li><a href=TradeSetup>Trade Pitch Setup</a>\n";
      if (Access('SysAdmin')) echo "<li><a href=TradeTypes>Trade Types and base Prices</a>\n";
      if (Access('SysAdmin')) echo "<li><a href=EmailTraders>Email Groups of Traders</a>\n"; // Old code needs lots of changes
//      if (Access('SysAdmin')) echo "<li><a href=TradeImport1>Convert old Trade Data</a>\n";
//      if (Access('SysAdmin')) echo "<li><a href=TradeImport2>Merge Mandy's Trade Data</a>\n";
//      if (Access('SysAdmin')) echo "<li><a href=TradeImport3>Fix Access Keys</a>\n";
//      echo "<li><a href=/admin/trade/index>Old Trade Stand Section</a>\n";
      echo "<li><a href=Trade2CSV?Y=$YEAR>Traders as CSV</a>\n";
    }
    if (Capability('EnableTrade') && !Capability('EnableFinance')) echo "<li><a href=InvoiceManage?Y=$YEAR>Invoice/Payment Management</a>\n";
    if (Access('SysAdmin')) {
      echo "<p><div class=tablecont><table><tr><td>";
      echo "<li class=smalltext><a href=ResetImageSizes?TRADE>Scan and save Image sizes</a>";
      echo "</table></div><p>\n";
    }
    echo "</ul>\n";
  }
  
// *********************** VENUES & EVENTS *******************************************************
  $_POST['DAYS'] = 0; $_POST['Pics'] = 1;
  if (StaffTable('Events',2)) {
    echo "<h2>Events and Venues</h2>\n";
    $Vens = Get_AVenues();
    echo "<ul>\n";
    echo "<li><a href=VenueList?Y=$YEAR>List Venues</a>\n";
    echo "<li><a href=EventList?Y=$YEAR>List All Events</a>\n";
    if (Access('Staff','Venues') && $YEAR==$PLANYEAR) echo "<li><a href=EventAdd>Create Event(s)</a>";
    
    echo "<li><form method=Post action=EventList class=staffform>";
      echo "<input type=submit name=a value='List Events at' id=staffformid>" . 
                fm_hidden('Y',$YEAR) .
                fm_select($Vens,0,'V',0," onchange=this.form.submit()") . "</form>\n";

    echo "<li><form method=Post action=VenueShow?Mode=1 class=staffform>";
      echo "<input type=submit name=a value='Show Events at' id=staffformid>" . 
                fm_hidden('Y',$YEAR) .
                fm_select($Vens,0,'v',0," onchange=this.form.submit()") . " - A public view of events even if they are not public</form>\n";
    echo "<li><form method=Post action=VenueShow?Poster=1 class=staffform>";
      echo "<input type=submit name=a value='Poster For' id=Posterid>" . 
                fm_hidden('Y',$YEAR) .
                fm_select($Vens,0,'v',0," onchange=this.form.submit()") . 
                fm_radio('',$Days,$_POST,'DAYS','',0) . fm_checkbox('Pics',$_POST,'Pics') .
                "</form>\n";

    if (Access('Staff','Venues')) echo "<li><a href=EventTypes>Event Types</a>\n";
    if (Access('Staff','Venues')) echo "<li><a href=VenueComplete?Y=$YEAR>Mark Venues as Complete</a>\n";
    if (Access('SysAdmin')) echo "<li><a href=TicketEvents?Y=$YEAR>List Ticketed Events</a>\n";
    if (Access('Staff')) echo "<li><a href=StewList?Y=$YEAR>List Stewarding Events</a>\n";
    if (Access('Committee','Venues')) echo "<li><a href=MapPoints>Additional Map Points</a>\n";
    if (Access('SysAdmin')) echo "<li><a href=MapPTypes>Map Point Types</a>\n";
    echo "<li><a href=EventSummary?Y=$YEAR>Event Summary</a>\n";
    echo "<li><form method=Post action=PAShow class=staffform>";
      echo "<input type=submit name=a value='PA Requirements for' id=staffformid>" . 
                fm_hidden('Y',$YEAR) .
                fm_select($Vens,0,'pa4v',0," onchange=this.form.submit()") . "</form>\n";

//    if (Access('SysAdmin')) echo "<li><a href=BusTimes>Fetch and Cache Bus Times</a>\n";
//    if (Access('SysAdmin')) echo "<li><a href=ConvertEvents>Convert Old Format Events to New Format Events</a>\n";
    if (Access('SysAdmin')) echo "<li><a href=AddVenue?NEWACCESS onClick=\"javascript:return confirm('are you sure you update these?');\">Generate New Access Keys for Venues</a>\n";
    if ($YEAR == $PLANYEAR && Access('Staff')) echo "<li><a href=VenueActive>Refresh Active Venue List</a>\n";
    echo "<li><form method=Post action=/WhatsOnNow class=staffform>";
      echo "<input type=submit name=a value='Whats On At ' id=staffformid>" . 
                fm_hidden('Y',$YEAR) . fm_text0('',$_POST,'AtTime') .' on ' . fm_text0('',$_POST,'AtDate');
    
    echo "</ul>\n";
  }
  
// *********************** Misc *****************************************************************
  if (StaffTable('Misc')) {
  echo "<h2>Misc</h2>\n";
    echo "<ul>\n";
//    echo "<li><a href=StewardView>Stewarding Applications (old)</a>\n";
    echo "<li><a href=Volunteers?A=New>Volunteering Application Form</a>\n";
    echo "<li><a href=Volunteers?A=List>Volunteers (new)</a>\n";
    if (Access('Staff','Photos')) {
      echo "<li><a href=PhotoUpload>Photo Upload</a>";
      echo "<li><a href=PhotoManage>Photo Manage</a>";
      echo "<li><a href=GallManage>Gallery Manage</a>";
    }
    echo "<p>";
    
//    echo "<li><a href=LaughView?Y=$YEAR>Show Laugh Out Loud applications</a>";
    if (Access('Committee')) echo "<li><a href=Campsite?Y=$YEAR>Manage Campsite Use</a>\n"; 
    if (Access('Staff')) echo "<li><a href=CarerTickets?Y=$YEAR>Manage Carer / Partner Tickets</a>\n"; 
    if (Access('Staff','Sponsors')) echo "<li><a href=TaxiCompanies>Manage Taxi Company List</a>\n"; 
    if (Access('SysAdmin')) echo "<li><a href=ConvertPhotos>Convert Archive Format</a>";
//    echo "<li><a href=ContractView>Dummy Music Contract</a>";
    echo "</ul>\n";
  }

// *********************** Finance **************************************************************
  if (StaffTable('Finance')) {
    echo "<h2>Finance and Sponsors</h2>\n";
    echo "<ul>\n";
    if (Access('Committee','Finance')) {
      echo "<li><a href=BudgetManage?Y=$YEAR>Budget Management</a>\n";
      echo "<li><a href=InvoiceManage?Y=$YEAR>Invoice/Payment Management</a>\n";
      echo "<li><a href=InvoiceManage?ACTION=NEW>New Invoice</a>\n";   
      echo "<li><a href=InvoiceCodes?Y=$YEAR>Invoice Codes</a>\n";   
      echo "<li><a href=InvoiceSummary?Y=$YEAR>Invoice Summary</a>\n";   
      echo "<li><a href=OtherPaymentSummary?Y=$YEAR>Other Payment Summary</a>\n";   
      echo "<li><a href=ListTrade?ORGS>Businesses and Organistaions List</a>\n"; 
      echo "<li><a href=Trade?ORGS>New Business or Organistaion</a>\n";  
      echo "<li><a href=Payments?Y=$YEAR>List All Performer Payments</a>\n";  
    } elseif (Access('Committee')) {
      echo "<li><a href=BudgetManage?Y=$YEAR>Budget View</a>\n";
      echo "<li><a href=InvoiceManage?Y=$YEAR>Invoice Management</a>\n";
    }
    if (Access('SysAdmin')) {
//      echo "<p>";
//      echo "<li class=smalltext><a href=ImportDebtorCodes>Import Debtor Codes</a>";
//      echo "<li class=smalltext><a href=ImportProgAds>Import Programme ads</a>\n";  
//      echo "<p>";
      echo "<li><a href=Sponsors>Sponsors</a>\n";
      echo "<li><a href=WaterManage>Water Refills</a>\n";

//      echo "<li><a href=ImportOldInvoice>Import Old Invoices</a>\n";  
    }
    echo "</ul>\n";
  }
    
// *********************** Art & Craft *********************************************************
  if (StaffTable('Craft')) {
    echo "<h2>Art and Craft</h2>\n";
    echo "<ul>\n";
    echo "<li><a href=ArtForm>Art Application Form</a>\n";
    echo "<li><a href=ArtView>Show Art Applications</a>\n";
    echo "</ul>";
  }

// *********************** GENERAL ADMIN *********************************************************
  if (StaffTable('Any')) {
    echo "<h2>General Admin</h2>\n";
    echo "<ul>\n";

    if (Capability('EnableAdmin') && Access('Committee','News')) {
//      echo "<li><a href=NewsManage>News Management</a>";
      echo "<li><a href=ListArticles>Front Page Article Management</a>";
      echo "<li><a href=LinkManage>Manage Other Fest Links</a>\n";
    }
    if (Access('Steward')) {
      echo "<li><a href=AddBug>New Bug/Feature request</a>\n";
      echo "<li><a href=ListBugs>List Bugs/Feature requests</a><p>\n";
    }

    if (Access('Staff')) echo "<li><a href=TEmailProformas>EMail Proformas</a>";
    if (Access('Staff')) echo "<li><a href=AdminGuide>Admin Guide</a> \n";
    if (Access('SysAdmin')) {
//      echo "<li><a href=BannerManage>Manage Banners</a> \n";
      if ( Capability("EnableMusic") || Capability("EnableMusic")) echo "<li><a href=PerformerTypes?Y=$YEAR>Performer Types</a> \n";
      echo "<li><a href=YearData?Y=$YEAR>General Year Settings</a> \n";
      echo "<li><a href=MasterData>Festival System Data Settings</a> \n";
    }
    echo "</ul>\n";
  }

  echo "</table></div>\n";

  dotail();
?>

