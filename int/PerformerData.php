<?php
  include_once("fest.php");
  A_Check('Staff');

  dostaffhead("Performer Data");
  include_once("DanceLib.php");
  include_once("MusicLib.php");
  include_once("DateTime.php");
  include_once("PLib.php");

  global $YEAR,$PLANYEAR,$Mess,$BUTTON;


  $id = $_REQUEST['id'];
  $Side = Get_Side($id);

  $UpdateValid = ($Side['IsASide'] && Access('Staff','Dance') || ($Side['IsAnAct'] && Access('Staff','Music')) || ($Side['IsOther'] && Access('Staff','Other')));
  
  echo '<h2>Performer Files For ' . $Side['SN'] . '</h2>';
  switch ($_REQUEST['ACTION']) {
  
  case 'STORE':
    if (!$UpdateValid) break;
    $target_dir = "Store/Performers/$id";
    
    mkdir($target_dir,0777,true);
    // Count # of uploaded files in array
    $total = count($_FILES['uploads']['name']);

    // Loop through each file
    for($i=0; $i<$total; $i++) {
      //Get the temp file path
      $tmpFilePath = $_FILES['uploads']['tmp_name'][$i];

      //Make sure we have a filepath
      if ($tmpFilePath != ""){
        //Setup our new file path
        $file = basename($_FILES['uploads']['name'][$i]);
        $target_file = $target_dir . "/" . $file;
//var_dump($tmpFilePath,$target_file);
        //Upload the file into the temp dir
        if(!move_uploaded_file($tmpFilePath, $target_file)) echo "File failed $file to move to the Store";
      }
    };
          
    break;
    
  case 'DELETE':
    if (!$UpdateValid) break;
    $fname = base64_decode($_REQUEST['f']);
    if (file_exists("Store/Performers/$id/$fname")) {
      if (unlink("Store/Performers/$id/$fname")) {
        echo "$fname Deleted<p>";   
      } else {
        echo "$fname failed to be deleted<p>";      
      }
    } else {
      echo "$fname not found<p>";
    }
    
 // Deliberate fall through to list
    
 //   break;
    
  case 'LIST':
  default:
    break;
  }
  
  // List
    $files = glob("Store/Performers/$id/*");
    if ($files) {
      echo "<table border><tr><td>File Name<td>Size<td>Uploaded<td>Actions";
      foreach ($files as $file) {
        $fname = basename($file);
        echo "<tr><td><a href=ShowFile.php?l64=" . base64_encode("Store/Performers/$id/$fname") . ">$fname</a>";
        echo "<td>" . formatBytes(filesize($file)) . "<td>" . date('j/m/Y H:i:s',filectime($file));
        echo "<td><a href=ShowFile.php?D=Store/Performers/$id/$fname>download</a>";
        if ($UpdateValid) echo ", <a href=PerformerData.php?id=$id&ACTION=DELETE&f=" . base64_encode($fname) . ">delete</a>";
      }
      echo "</table><p>";
    } else {
      echo "No Files are currently stored";
    }

  
  // Upload && back to edit performer
  if ($UpdateValid) {
    echo '<form action="PerformerData.php" method="post" enctype="multipart/form-data" id=Uploads>';
      echo "Select file(s) to upload:";
      echo fm_hidden('id', $id);
      echo fm_hidden('ACTION', 'STORE');
      echo '<input type="file" name="uploads[]" multiple onchange=this.form.submit()>';
      echo " &nbsp; &nbsp; Do not upload more than 15M at once, for large files contact <a href=mailto:Richard@wavwebs.com>Richard</a>.\n";
      echo "</form>\n";
  };
      
  echo "<h2><a href=" . ($Side['IsASide']?'AddDance.php':'AddMusic.php') . "?sidenum=$id>Back to " . $Side['SN'] . "</a></h2>\n";
  
  dotail();
  
  
?>
