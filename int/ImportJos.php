<?php

  include_once("fest.php");
  A_Check('SysAdmin');
  
  include_once("DanceLib.php");
  include_once("MusicLib.php");
  include_once("ProgLib.php"); 
  include_once("DateTime.php");
  include_once("BudgetLib.php");
 
  dostaffhead("Import Jo's spreadsheet");
  
  require 'vendor/autoload.php';


  use PhpOffice\PhpSpreadsheet\Spreadsheet;
  /* 
    Walk OldInvoices - find first xls for each company
    
    Read xls
    
    Find:
      Business - Look it up - if we have it and have a Sage Code defined skip company
      Find Sage Code used
      Find Contact Name
      Find Address
      
      If BZ exists - set all the data above not held, save, report, end for company
      
      Create BZ with details above, report, end for company
      
   */

$ActFix = [
'Court Rogue - CANCELLED'=>'Skip',
'Andrew Helson (celeidh caller)'=>['SN'=>'Andrew Helson'],
'Murphys Lore + Yeti'=>['SN'=>'Murphy\'s Lore'],
'Atlantico'=>['AltContact'=>'Guy','AltMobile'=>'07976 778340'],
'Julian Mount '=>['Phone'=>'01727 868118','IsFunny'=>1],
'Sheepstealers'=>['AltContact'=>'Fiona','AltMobile'=>'07979 762139'],
'The Shackleton Trio'=>['AltContact'=>'Nic','AltMobile'=>'07528727546'],
'Two Man Travelling Medicine Show '=>['AltContact'=>'Alison Jay','AltMobile'=>'07769 592047'],
'Steve Faulkner'=>['SortCode'=>''],
'The Polly Morris Band'=>['SN'=>'Polly Morris Band'],
'The Folk Orc Session Band'=>['SN'=>'The Folk Orc'],
'Witchampton Ukuele Orchestra'=>['SN'=>'Witchampton Ukulele Orchestra'],
'India Electric Company'=>['SN'=>'India Electric Co.'],
'The Wareham Whalers'=>['SN'=>'Wareham Whalers'],
'Kim Lowings'=>['SN'=>'Kim Lowings and the Greenwood'],
'Alden, Patterson & Dashwood'=>['SN'=>'Alden, Patterson and Dashwood'],
'Ben Morgan Brown'=>['SN'=>'Ben Morgan-Brown'],
'Just Sing Choir'=>['SN'=>'Just Sing Adult Choir'],
'Mikey Ball & the Company'=>['SN'=>'Mikey Ball and the Company'],
'Mitchell & Vincent'=>['SN'=>'Mitchell and Vincent'],
'Odette Mitchell'=>['SN'=>'Odette Michell'],
'Krista'=>['SN'=>'Krista Green and the Bees'],
'Rob Lane'=>['SN'=>'Robert Lane'],
'Scuttle Shake'=>['SN'=>'ScuttleShake'],
'The Shackleton Trio'=>['SN'=>'Shackleton Trio'],
'Susie Dawson'=>['SN'=>'Susie Dobson'],
'Vicki Swann & Johnny Dyer'=>['SN'=>'Vicki Swan & Johnny Dyer'],
'Will Finn & Rosie Calvert'=>['SN'=>'Will Finn and Rosie Calvert'],
'Tom Moore & Archie Churchill-Moss'=>['SN'=>'Tom Moore and Archie Churchill-Moss'],
'Antoine and Owena'=>['SN'=>'Antoine & Owena'],
'Black Sheep Band'=>['SN'=>'Black Sheep'],
'Atlantico'=>['SN'=>'Atlántico'],
'Rhaniket'=>['SN'=>'Ranikhet'],
'Tatterdamalion'=>['SN'=>'Tatterdemalion'],
'Ed Loftsedt Assembly'=>['SN'=>'The Ed Lofstedt Assembly'],
'Footlight Performance Academy'=>['SN'=>'Footlights Performance Academy'],
'Two Man Travelling Medicine Show'=>['SN'=>'The Two Man Travelling Medicine Show'],
'x'=>['SN'=>''],
'x'=>['SN'=>''],
'x'=>['SN'=>''],


];

$ActYFix = [
'India Electric Company'=>['CampFri'=>2],
'Jack Cookson'=>['CampFri'=>1],
'The Askew Sisters'=>['CampFri'=>2],
'Kim Lowings'=>['CampSun'=>4],
'Thom Ashworth'=>['CampSun'=>4],
];

$Budget4 = [
'Minster'=>'Music',
'Methodist'=>'Music',
'Micro-Brew'=>'Microbrewery',
'Olive Branch'=>'Olive Branch Bands',
'Olive Eve'=>'Olive Branch Bands',
'Square'=>'Music',
'Allendale House'=>'Allendale House Activity',
'Allendale Centre'=>'Music',
'Willow Walk'=>'Music',
'Minster Arms'=>'Minster Arms Bands',
'Green Man'=>'The Green Man Sessions',
'Rising Sun'=>'The Rising Sun Sessions',
'Ceilidhs'=>'Ceilidhs',
];

// Trim text to this
$EventFix = [
'DOORS' => 1,
'Soundcheck'=> 2,
'JACK COOKSON'=>'Jack Cookson',
'ASKEW SISTERS'=>'The Askew Sisters',
'INDIA ELECTRIC CO'=>'India Electric Company',
'Floor Spots'=>3,
'Singaround with Kitty and Derek'=>['SN'=>'Singaround','Side1'=>'Kitty and Derek'],
];


$VenueXlate = [ // Trim to access
'Minster'=>'Minster Church',
'Methodist'=>'Methodist Church',
'Square'=>'Square (stage)',
'Rising Sun'=>'Rising Sun',
'Olive Branch'=>'Olive Branch Garden Stage',
'Alendale House'=>'Allendale House (Downstairs room)',
'Allendale House'=>'Allendale House (Downstairs room)',
'Willow Walk'=>'Willow Walk Stage',
'Minster Arms'=>'Minster Arms',
'Green Man'=>'Green Man (Inside)',
'Micro-Brew'=>'Microbreweries Stage',
'Microbreweries'=>'Microbreweries Stage',

];

function Get_SideByName($who) {
  global $db;
  $res = $db->query('SELECT * FROM Sides WHERE SN="' . $who . '"');
  if (!$res || $res->num_rows == 0) return 0;
  $data = $res->fetch_assoc();
  return $data;
}



function Update(&$side,$sidef,$shtf,$spec='') {
  global $Data, $SideChange;
  $val = $Data[$shtf];
  if (!$val) return;
  switch ($spec) {
  case 'M':
    if (substr($val,0,1) != '0') $val = '0' . $val;
    break;
  case 'N': 
    if ($val == 'N/A' || !$val) return;
    break;
  }
  
  if (!isset($side[$sidef]) || $side[$sidef] != $val) {
    $side[$sidef] = $val;
    $SideChange = 1;
    echo "Change $sidef to $val<br>";
  }
}

  $DOIT = 0;
  if (isset($_REQUEST['DOIT'])) $DOIT = 1;
  
  $ColsWanted = ['Act','Minster','Methodist','Micro-Brew','Olive Branch','Olive Eve','Square','Allendale House','Allendale Centre','Willow Walk','Minster Arms','Green Man','Rising Sun',
                 'Ceilidhs','Artist Total','Sort Code','Account No.','Account Name','Email','Contact phone','Alt contact phone ','Contact name '];

  $OrigFile = "../../Wimborne Schedule.xlsx";
      
  $ss = \PhpOffice\PhpSpreadsheet\IOFactory::load($OrigFile);
  $sheet = $ss->getSheetByName("Costs");
  
  $highestRow = $sheet->getHighestRow(); // e.g. 10
  $highestColumn = $sheet->getHighestColumn(); // e.g 'F'
  // Increment the highest column letter
  $highestColumn++;

  // Get Col Headings
  $colHeads = [];
  for ($col = 'A'; $col != $highestColumn; ++$col) {
    $heading = $sheet->getCell($col . "1")->getValue();
    $colHeads[$col] = $heading;
  }
  
  $ColbyName = array_flip($colHeads);

//var_dump($ColbyName);

  // look up each performer - for table side (not yet sideyear)
  

  for ($row = 2; $row<=$highestRow; $row++) {
//  echo "Row $row<br>";
    $Act = $sheet->getCell($ColbyName['Act'] . $row)->getValue();
    if (!$Act) continue;
    $Fixes = [];
    if (isset($ActFix[$Act])) {
      if (!is_array($ActFix[$Act])) { echo "Skipping $Act<br>"; continue; }
      $Fixes = $ActFix[$Act];
    }
    if (isset($Fixes['SN'])) $Act = $Fixes['SN'];
    $Act = preg_replace('/ \(Fatea showcase\)/','',$Act);
    $OrigSide = $side = Get_SideByName($Act);
    
    // Grab Row
    $Data = [];
    foreach ($ColsWanted as $c) $Data[$c] = $sheet->getCell($ColbyName[$c] . $row)->getCalculatedValue();

    $SideChange = $New = 0;
    if ($side) { 
      echo "Found $Act<br>"; 
    } else { 
      $side = [];
      echo "Not Found $Act<br>"; 
      $side['SN'] = $Act;
      if (!isset($Fixes['IsFunny'])) $side['IsAnAct'] = 1;
      $New = 1;
    };
    Update($side,'SortCode', 'Sort Code','N');
    Update($side,'Account', 'Account No.','N');
    Update($side,'AccountName', 'Account Name','N');
    Update($side,'Email', 'Email');
    Update($side,'Contact', 'Contact name ');
    Update($side,'Mobile', 'Contact phone','M');
    if ($Fixes) foreach ($Fixes as $f=>$v) { 
      if ($side[$f] != $v) {
        $side[$f] = $v;
        $SideChange = 1;  
      }
    } 
    if ($SideChange && $DOIT) {
      if ($New) {
//var_dump($side); exit;
        $snum = Insert_db('Sides',$side);
      } else if ($SideChange) {

        Update_db('Sides',$OrigSide,$side);
        $snum = $side['SideId'];
      }
    } else {
      $snum = ($New?0:$side['SideId']);
    }
   
    // SideYear

    $OrigSY = $sy = Get_SideYear($snum);
    if (!$sy) $sy = Default_SY($snum);

    $SideChange = $New = 0;
    if (isset($sy['syId'])) {
      if ($sy['YearState'] < 2) {
        $sy['YearState']=2;  //Booking
        $SideChange = 1;
      }
    } else {
      $sy['YearState']=2;
      $SideChange = $New = 1;
    }

    $Fixes = [];
    if (isset($ActYFix[$Act])) $Fixes = $ActYFix[$Act];

    Update($sy,'TotalFee','Artist Total');

    if ($Fixes) foreach ($Fixes as $f=>$v) if ($sy[$f] != $v) {
      $sy[$f] = $v;
      $SideChange = 1;
    }

    // Budget Areas...

    $sy["BudgetArea"] = $sy["BudgetArea2"] = $sy["BudgetArea3"] = $sy["BudgetValue2"] = $sy["BudgetValue3"] = 0;
    $Bud='';
    foreach ($Budget4 as $jb=>$bn) {
      $val = $Data[$jb];
      if ($val) {
        $ba = $bn;
        $bnum = FindBudget($bn);
        if ($Bud) {
          if ($sy["BudgetArea"] == $bnum) { // Do nothing
          } elseif ($Bud == 3 && $sy["BudgetArea2"] == $bnum) {
            $sy["BudgetValue2"] += $val;
          } else {
            $sy["BudgetArea$Bud"] = $bnum;
            $sy["BudgetValue$Bud"] = $val;
          }
        } else { 
          $sy["BudgetArea$Bud"] = $bnum;
          if ($Bud) $sy["BudgetValue$Bud"] = $val;
        }
        $SideChange = 1;
        if ($Bud == '') { $Bud = 2; }
        elseif ($Bud == 2) { $Bud = 3; }
        else $Bud = 'XX';
      }
    }

    if ($DOIT) {
      if ($SideChange && $New) {
        $syid = Insert_db('SideYear',$sy);
      } else if ($SideChange) {
        Update_db('SideYear',$OrigSY,$sy);
        $snum = $side['SideId'];
      }
    } else {
      if ($SideChange && $New) {
        echo "Would Create Side Year<p>";
      } else {
        echo "Would Update Side Year<p>";
      }
      
    }
    
  }
//  exit;
  
  //****************************************************  Events
  
  function  FinishEvent($Start,$VenueId,$End,$Content,$row) {
    global $dnum;
    echo "Found an event from $Start to $End at $VenueId with $Content ";  // Test code
    
    $Evs = Get_Event_VTs($VenueId,$Start,$dnum);
    if (!$Evs) {
      echo " - No Event Found for this.";
    } elseif (isset($Evs[1])) { // Event & sub event case
    } else { // Simple event
    
    }
    echo "<br>";
  }
  
  $DayCodes = ['Fri','Sat','Sun'];
  $Venues = Get_Venues(1);
  $Loops = 0;
  // Events for Friday
  foreach ($DayCodes as $dnum=>$dname) {
  
    echo "<h2>$dname</h2>";
    $sheet = $ss->getSheetByName($dname);
   
    $highestRow = $sheet->getHighestRow(); // e.g. 10
    $highestColumn = $sheet->getHighestColumn(); // e.g 'F'
    // Increment the highest column letter
    $highestColumn++;

    // Get Col Headings
    $colHeads = [];
    for ($col = 'B'; $col != $highestColumn; ++$col) {
      $heading = $sheet->getCell($col . "1")->getFormattedValue();
      $colHeads[$col] = $heading;
    }
//echo "BB";
    $ColbyName = array_flip($colHeads);

    $times = [];
    for ($row = 2; $row <= $highestRow; ++$row) {
      $time = $sheet->getCell("A$row")->getFormattedValue();
//echo "Time is $time<br>";
//exit;
      $lasttime = $times[$row] =  ($time ? Time_BestGuess($time) : timeadd($lasttime,5));
    }

//echo "AA"; exit;
    // Scan by Venue map heading to actual venue #
    // Look for multi row entry
    // find period needed
    // if soundcheck record for next act, if SouundChecks should be own event
    // Look at text match to EventFix and SideNames
    // If No enclosing Event highlight and drop out
    // Otherwise is there a sub event with the right times, does it have that performer?  If another flag, if none add
    
    for ($col = 'B'; $col != $highestColumn; ++$col) {
      $ColHead = trim($colHeads[$col]);
      if (!$ColHead) continue;
      $VenueName = $VenueXlate[$ColHead];
      echo "Looking 4: $VenueName<br>";
      if (!$ColHead) continue;
      $VenueId = 0;
      foreach ($Venues as $vi=>$Ven) if ($Ven['SN'] == $VenueName) { $VenueId = $vi; break; }
      
      if ($VenueId == 0) { echo "Can't Find Venue $ColHead<br>"; continue; }
      
      $Mode = 0; // 0 Looking, 1 inside
      for ($row =2; $row <= $highestRow; ++$row) {
        $Cell = $sheet->getCell($col . $row);
        $Range = $Cell->isInMergeRange();
        if ($Mode == 0 && !$Range) continue;
        if ($Mode == 0) { // Start
          $Start = $times[$row];
          $Mode = 1;
          $Content = $Cell->getValue();
        } else if ($Range) {
          if ($Cell->isMergeRangeValueCell()) { // First Cell
            $End = $times[$row];
            FinishEvent($Start,$VenueId,$End,$Content,$row);
            $Start = $End;
            $Content = $Cell->getValue();           
          } else {// Continue - prob no action
          }
        } else { // End of event
            $End = $times[$row];
            FinishEvent($Start,$VenueId,$End,$Content,$row); 
            $Mode = 0;         
        }
      }
      
      if ($Mode) { // End of event
            $End = $times[$row];
            FinishEvent($Start,$VenueId,$End,$Content,$row);      
      }
//exit;
    }
  }
   
  dotail();
?>
