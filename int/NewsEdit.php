<?php
  include_once("fest.php");
  A_Check('Staff','Other');

  dostaffhead("Manage News");
  global $SHOWYEAR;

  include_once("NewsLib.php");
  include_once("DateTime.php");

  echo "<h2>Edit News</h2>\n";
  
  $Action = 0; 
  $Mess = '';
  if (isset($_POST{'Action'})) {
    include_once("Uploading.php");
    $Action = $_POST{'Action'};
    switch ($Action) {
    case 'Photo':
      $Mess = Upload_Image('News','image');
      break;
    default:
      $Mess = "!!!";
    }
  }

  if (isset($_POST['id'])) {
    $n = Get_News($_POST['id']);
    $_POST['created'] = Date_BestGuess($_POST['created']);
    Update_db_post('News',$n);
  } else {
    $n = Get_News($_GET['n']);
  }

  echo "<form method=post action=NewsEdit.php enctype='multipart/form-data' ><table border>\n";
  echo "<tr hidden><td hidden><input type=submit>\n";
  echo "<tr><td>ID:" . $n['id'] . fm_hidden('id',$n['id']);
    echo "<td>" . fm_checkbox('Display',$n,'display');
    echo "<td>Visible from:<td>" . fm_textinput("created",date('j M Y G:i',$n['created']));
  echo "<tr>" . fm_text('Title',$n,'SN');
  echo "<tr><td colspan=5>" . fm_basictextarea($n,'content',10,10);
  echo "<tr><td colspan=2 rowspan=3>";
    if ($n['image']) {
      $img=$n['image'];
      if (!preg_match('/^(http(s?):|\/images\/)/',$img)) $img = "/images/" . $img;
      echo "<img src='$img' width=500>";
    } else {
      echo "No Image yet";
    }
    echo fm_text('Image',$n,'image',2);

    echo "<tr><td colspan=4>Select image file to upload:";
    echo "<input type=file name=PhotoForm id=PhotoForm onchange=document.getElementById('PhotoButton').click()>";
    echo "<input hidden type=submit name=Action value=Photo id=PhotoButton>";
    if ($Mess && $Action == 'Photo') echo "<br>$Mess\n";
    echo "<tr>" . fm_text('Caption',$n,'caption',2);

  echo "<tr>" . fm_text('Link',$n,'Link') . fm_text('Link Text',$n,'LinkText');
 

  echo "</table><p>";
  echo "<input type=submit></form><p>\n";

  echo "<h2><a href=NewsManage.php>Back to News Management</a></h2><p>\n";
  dotail();

?>
