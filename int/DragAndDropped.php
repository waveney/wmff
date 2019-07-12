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


//********************************* START HERE **************************************************************

$Type = $_REQUEST['Type'];
$SType = preg_replace('/ */','',$Type);
$id = $_REQUEST['Id'];
$Cat = $_REQUEST['Cat'];
$Mode = $_REQUEST['Mode'];

switch ($Cat) {
case 'Sides':
case 'Perf':
  $Data = Get_SideYear($id);
  $Put = 'Put_SideYear';
  break;
  
case 'Trade':
  $Data = Get_Trade_Year($id);
  $Put = 'Put_Trade_Year';
  break;
  
default:
  echo fm_DragonDropInner($Type,$Cat,$id,$Data,'',$Mode,1,"Unknown Data Category $Cat");
  exit;
}

if (!$Data) { 
  echo fm_DragonDropInner($Type,$Cat,$id,$Data,'',$Mode,1,"No Data found to update - $Type - $Cat - $Id ");
  exit;
}

//TODO paths bellow only work for per year data not fixed eg PA 

// Existing file?
$files = glob("$SType/$YEAR/$Cat/$id.*");
if ($files) {
  Archive_Stack($files[0],"$SType/$YEAR/$Cat",$id );
}

// New file

$target_dir = "$SType/$YEAR/$Cat";
umask(0);
if (!file_exists($target_dir)) mkdir($target_dir,0775,true);

$suffix = pathinfo($_FILES["Upload"]["name"],PATHINFO_EXTENSION);
$target_file = "$target_dir/$id.$suffix";

if (!move_uploaded_file($_FILES["Upload"]["tmp_name"], $target_file)) {
  echo fm_DragonDropInner($Type,$Cat,$id,$Data,'',$Mode,1,"Uploaded file failed to be stored");
  exit;
}

$Data[$SType] = 1;
$Put($Data);

if ($files) {
  $Mess = "The $Type file has been replaced by " . $_FILES["Upload"]["name"];
} else {
  $Mess = $_FILES["Upload"]["name"] . " has been stored as the $Type file";
}

echo fm_DragonDropInner($Type,$Cat,$id,$Data,'',$Mode,1,$Mess);

