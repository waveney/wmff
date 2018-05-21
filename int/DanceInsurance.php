<html>
<head>
<title>Wimborne Minster Folk Festival | Insurance Upload</title>
<?php include_once("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
<?php include_once("DanceLib.php"); ?>
</head>
<body>
<?php 
//  include_once("files/navigation.php"); 
// <div class="content"><h2>Upload Insurance</h2>
  global $YEAR,$Mess,$Where;
  $snum = $_POST{'Id'};
  $type = $_POST['Type'];
  $From = $_POST['From'];
  $Where = 'Insurance';

  switch ($type) {
  case 'Side':
    $Side = Get_Side($snum);
    $Sidey = Get_SideYear($snum);
    $Put = "Put_Side";
    $Puty = 'Put_SideYear';
    $Act = "Show_Side";
    break;

  case 'Act':
    $Side = Get_Act($snum);
    $Sidey = Get_ActYear($snum);
    $Put = "Put_Act";
    $Puty = 'Put_ActYear';
    $Act = "Show_Act";
    break;

  case 'Other':
    $Side = Get_Other($snum);
    $Sidey = Get_OtherYear($snum);
    $Put = "Put_Other";
    $Puty = 'Put_OtherYear';
    $Act = "Show_Other";
    break;
  }

/*
  if existing file.sfx
    rename existing file as temp.sfx
    if move to new file delete temp - replaced message
    else rename temp back to old file.sfx - error uploading
  else if !move to new file error uploading
    else if database update then uploaded
    else file up, no  db
 */

  A_Check('Participant',$type,$snum);
  $target_dir = "Insurance/$type" . "s/$YEAR/";
  umask(0);
  if (!file_exists($target_dir)) mkdir($target_dir,0775,true);
  $suffix = pathinfo($_FILES["InsuraneUpload"]["name"],PATHINFO_EXTENSION);
  $target_file = $target_dir . "$snum.$suffix";

var_dump($_FILES);
exit;
  if ($Sidey['Insurance']) {
    $files = glob($target_dir . $snum . ".*");
    $Current = $files[0];
//    $Cursfx = pathinfo($Current,PATHINFO_EXTENSION );
    rename($Current, "$Current.old");
    if (move_uploaded_file($_FILES["InsuraneUpload"]["tmp_name"], $target_file)) {
      unlink("$Current.old");
      $Mess = "The Insurance file has been replaced by " . basename( $_FILES["fileToUpload"]["name"]);
    } else {
      rename("$Current.old",$Current);
      $Mess = "<div class=Err>Sorry, there was an error uploading your Insurance file.</div>";
    }
  } else {
    if (move_uploaded_file($_FILES["InsuraneUpload"]["tmp_name"], $target_file)) {
      $Sidey['Insurance'] = 1;
      if ($Puty($Sidey)) {
        $Mess = "The Insurance file ". basename( $_FILES["InsuraneUpload"]["name"]). " has been uploaded.";
      } else {
        $Mess = "<div class=Err>File uploaded but database did not update... " . $db->error . "</div>";
      }
    } else {
      $Mess = "<div class=Err>Sorry, there was an error uploading your Insurance file.</div>";
    }
  }

  include_once($From);
  exit();
//  $Act($snum);
?>

<?php include_once("files/footer.php"); ?>
</body>
</html>
