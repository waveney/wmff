<?php 
// Set uploaded fields in data
include_once("fest.php");
include_once("festfm.php");
include_once("DanceLib.php");
include_once("TradeLib.php");

function Archive_Stack($loc,$pth,$id) {
  if (!file_exists($loc)) return;
  $sfx = pathinfo($loc,PATHINFO_EXTENSION);
  $hist = 1;
  while (file_exists("$pth/Old$hist.$id.$sfx")) $hist++;
  rename($loc,"$pth/Old$hist.$id.$sfx");
}

  global $DDdata;
//********************************* START HERE **************************************************************

$Type = $_REQUEST['Type'];
$id = $_REQUEST['Id'];
$Cat = $_REQUEST['Cat'];
$Mode = (isset($_REQUEST['Mode'])?$_REQUEST['Mode'] :0);
$Class = (isset($_REQUEST['Class'])?$_REQUEST['Class'] :"");
$DDd = $DDdata[$Type];
$Name = $Type;
if (isset($DDd['Name'])) $Name = $DDd['Name'];
$PathCat = $Cat;

switch ($Cat) {
case 'Sides':
  $Data = Get_SideYear($id);
  $Put = 'Put_SideYear';
  break;

case 'Perf':
  $Data = Get_Side($id);
  $Put = 'Put_Side';
  $PathCat = "Sides";
  break;

case 'Trade':
  if ($DDd['UseYear']) {
    $Data = Get_Trade_Year($id);
    $Put = 'Put_Trade_Year';
  } else {
    $Data = Get_Trader($id);
    $Put = 'Put_Trader';
  }
  break;
  
case 'Venue':
case 'Venue2':
case 'Sponsor':
case 'Event':
  // TODO
  
default:
  echo fm_DragonDrop(0,$Type,$Cat,$id,$Data,$Mode,"Unknown Data Category $Cat",1,'',$Class);
  exit;
}

if (!$Data) { 
  echo fm_DragonDrop(0,$Type,$Cat,$id,$Data,$Mode,"No Data found to update - $Type - $Cat - $Id ",1,'',$Class);
  exit;
}

//TODO paths bellow only work for per year data not fixed eg PA 

// Existing file?
if (isset($DDd['path'])) {
  if ($DDd['path'] == 'images')  {
    $pdir = "images/$PathCat";
  } else {
    $pdir = $DDd['path'];
  }
} else {
  $pdir = ($DDd['UseYear']?"$Type/$YEAR/$Cat":$Type);
}
$path = "$pdir/$id";

$files = glob("$path.*");
if ($files) {
  Archive_Stack($files[0],$pdir,$id );
}

// New file

$target_dir = $pdir;
umask(0);
if (!file_exists($target_dir)) mkdir($target_dir,0775,true);

$suffix = pathinfo($_FILES["Upload"]["name"],PATHINFO_EXTENSION);
$target_file = "$target_dir/$id.$suffix";

if (!move_uploaded_file($_FILES["Upload"]["tmp_name"], $target_file)) {
  echo fm_DragonDrop(0,$Type,$Cat,$id,$Data,'',$Mode,1,"Uploaded file failed to be stored",1,'',$Class);
  exit;
}

if (is_numeric($DDd['SetValue'])) {
  $Data[$Type] = $DDd['SetValue']; //TODO PAspec fix DDd
} elseif ($DDd['SetValue'] == 'URL') {
  $Data[$Type] = "/" . $target_file . "?" . time();
} else {
  $Data[$Type] = $DDd['SetValue'];
}
$Put($Data);

if ($files) {
  $Mess = "The $Name file has been replaced by " . $_FILES["Upload"]["name"];
} else {
  $Mess = $_FILES["Upload"]["name"] . " has been stored as the $Name file";
}
$Mess .= ".  Refresh the page if you wish to change it.";

echo fm_DragonDrop(0,$Type,$Cat,$id,$Data,$Mode,$Mess,1,'',$Class);

