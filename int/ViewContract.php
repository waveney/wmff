<?php
  include_once("fest.php");
 
  
  dostaffhead("View Contract");
  include_once("Contract.php");
  include_once("ViewLib.php");
  global $YEAR;

  $snum=0;
  $ctype = 1;
  if (isset($_GET{'sidenum'})) $snum = $_GET{'sidenum'};
  if (isset($_GET{'ctype'})) $ctype = $_GET{'ctype'};

  $Side = Get_Side($snum);
  if     ($Side['IsAnAct']) { $ctype = 1; }
  elseif ($Side['IsOther']) { $ctype = 2; }
  elseif ($Side['IsASide']) { $ctype = 0; }
  
  $Sidey = ((Feature('NewPERF') || $ctype==0) ? Get_SideYear($snum) : Get_ActYear($snum));
  $Opt = 0;
  $IssNum = $Sidey['Contracts']; 
  if ($Sidey['YearState'] == $Book_State['Contract Signed']) $Opt += 1;
  if ($Sidey['Contracts']) $Opt +=2;
  if (isset($_GET{'I'})) { $IssNum = $_GET{'I'}; $Opt += 4; }

  switch ($Opt) {
  case 0:
  case 1:
  case 2:
    echo Show_Contract($snum,0,$ctype);
    break;
  case 3:
    echo Show_Contract($snum,1,$ctype);
    break;
  default:
    ViewFile("Contracts/$YEAR/$snum.$IssNum.html");
    break;
  }

  echo "</div>";  
  dotail();
?>
