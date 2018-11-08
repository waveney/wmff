<?php
  include_once("fest.php");
  include_once("ImageLib.php"); 

  A_Check('Staff','Photos');
  dostaffhead("Upload Photos");
  global $SHOWYEAR,$PLANYEAR;

  $Places = array('General Images'=>'images',
        "Gallery for $SHOWYEAR"=>"images/gallery/$SHOWYEAR",
        "Gallery for $PLANYEAR"=>"images/gallery/$PLANYEAR",
        "Icons"=>"images/icons",
        "Sponsors"=>"images/Sponsors");
  $Pflip = array_flip($Places);

// var_dump($_POST);
// var_dump($_FILES);
  
  if (isset($_POST['Action'])) {
    if (!isset($_POST['Where']) || !$_POST['Where']) $_POST['Where'] = "images";
    $target_dir = "" . $_POST['Where'];
    $prefix = $_POST['Prefix'];
    umask(0);
    if (!file_exists($target_dir)) mkdir($target_dir,0775,true);
    // Count # of uploaded files in array
    $total = count($_FILES['PhotoForm']['name']);
    if ($total > 6) $total = 6;
    // Loop through each file
    for($i=0; $i<$total; $i++) {
      //Get the temp file path
      $tmpFilePath = $_FILES['PhotoForm']['tmp_name'][$i];
      $target_file = "$target_dir/$prefix" . basename($_FILES["PhotoForm"]["name"][$i]);
      $basdir = dirname($target_file);
      if (!file_exists($basdir)) mkdir($basdir,0777,true);
      $check = getimagesize($tmpFilePath);
      if ($check == false) {
        echo "<div class=Err>$tmpFilePath is not an image</div>";
      } else {
        if ((!$_POST['NoReSize']) && ($check[0] > 800 || $check[1] > 536)) { // Need to resize
          $move = Image_Convert($tmpFilePath,800,536, $target_file);
        } else {
          $move = move_uploaded_file($tmpFilePath, $target_file);
        }
        echo "Done $target_file<br>\n";
      }
    }
  }
  echo "<h2>Upload no more than 6 photos at once or more than 16M of photos</h2>";
  echo "<form method=post action=PhotoUpload.php enctype='multipart/form-data' id=Photosform>";
  echo fm_radio("Where to put them",$Pflip,$_POST,'Where','',0) . "<p>";
  echo fm_text("Prefix - if it has a / it will make any necessary directories",$_POST,'Prefix') . "<br>\n";
  echo fm_hidden('Action', 'Upload');
  echo fm_checkbox("Supress resizing",$_POST,'NoReSize') . "<br>";
  
  echo "Select Photo files to upload:";
  echo '<input type=file name="PhotoForm[]" multiple id=manyfiles onchange=this.form.submit()>';
  echo "<p><input type=submit name=Action2 value=Upload id=PhotoButton>";

  echo "</form><p>";

  dotail();

?>  
