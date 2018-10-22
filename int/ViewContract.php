<html>
<head>
<title>Wimborne Minister Folk Festival | View Contract</title>
<?php include_once("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
<?php include_once("DanceLib.php"); ?>
<?php include_once("MusicLib.php"); ?>
<?php include_once("ProgLib.php"); ?>
<?php include_once("PLib.php"); ?>
<?php include_once("Contract.php"); ?>
<?php include_once("ViewLib.php"); ?>
</head>
<body>
<?php 
  include_once("files/navigation.php");
  global $YEAR;

  $snum=0;
  $ctype = 1;
  if (isset($_GET{'sidenum'})) $snum = $_GET{'sidenum'};
  if (isset($_GET{'ctype'})) $ctype = $_GET{'ctype'};

  $Side = Get_Side($snum);
  $Sidey = (($ctype==0) ? Get_SideYear($snum) : Get_ActYear($snum));
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
