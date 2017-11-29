<html>
<head>
<title>Wimborne Minster Folk Festival | Photo Upload</title>
<?php 
  include("files/header.php");
  include_once("festcon.php");
  include_once("DanceLib.php"); 
  include_once("ImageLib.php"); 
?>

</head>
<body>
<?php

//  include("files/navigation.php"); 
// <div class="content"><h2>Upload Photo</h2>

  global $db,$Mess,$Where;
  $snum = $_POST{'Id'};
  $type = $_POST['Type'];
  $From = $_POST['From'];
  $Where = 'Photo';

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

  A_Check('Participant',$type,$snum);

  $target_dir = "images/$type" . "s/";
  $suffix = strtolower(pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION));
  $target_file = $target_dir . $type . "_$snum.$suffix";
  $uploadOk = 1;
  // Check if image file is a actual image or fake image
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
echo "H1";
  if ($check == false) {
    $Mess = "File is not an image.<p>";
    $uploadOk = 0;
  } else {
echo "H2";
    if ($check[0] > 800 || $check[1] > 536) { // Need to resize
      $move = Image_Convert($_FILES["fileToUpload"]["tmp_name"],800,536, $target_file);
    } else {
echo "H3";
      $move = move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
var_dump("uploaded",$move);
    }

    if ($move) {
echo "H4";
      if (isset($Side['Photo']) && $Side['Photo'] == "/" . $target_file) {
        $Mess = "The photo has been replaced by ". basename( $_FILES["fileToUpload"]["name"]) ;
      } else {
        $Side['Photo'] = "/" . $target_file;
        if ($Put($Side)) {
          $Mess = "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        } else {
          $Mess = "File uploaded but database did not update... " . $db->error ;
        }
      }
    } else {
echo "H5";
      $Mess = "Sorry, there was an error uploading your file.";
    }
  }
  include($From);
  exit();
  $Act($snum);
?>

<?php include("files/footer.php"); ?>
</body>
</html>
