<?php
  include_once("fest.php");
  A_Check('Committee','News');

  dostaffhead("Manage Front Page Articles");
  global $Importance,$SHOWYEAR,$ArticleFormats;

  include_once("NewsLib.php");
  include_once("Uploading.php");
  include_once("DateTime.php");
   
//var_dump($_POST);
  $Dates = array('StartDate','StopDate');
  
  if (isset($_REQUEST['ACTION'])) { /* Response to create/update button */
    Parse_DateInputs($Dates);
    
    if (isset($_REQUEST['Image']) && $_REQUEST['Image']) {
      $img = $_REQUEST['Image'];
      if (preg_match('/^https?:\/\//i',$img)) {
        $stuff = getimagesize($img);
      } else if (preg_match('/^\/(.*)/',$img,$mtch)) {
        if (file_exists($mtch[1])) {
          $stuff = getimagesize($mtch[1]);
        } else {
          $stuff = [0,0];
        }
      } else {
        $stuff = getimagesize($img);
      }
      if ($stuff) {
        $_POST['ImageWidth'] = $stuff[0];
        $_POST['ImageHeight'] = $stuff[1];
      }
    }

    if ($_REQUEST['ACTION'] == 'UPDATE') {
      $id = $_REQUEST['id'];
      $Art = Get_Article($id);
      Update_db_post('Articles',$Art);
    } elseif ($_REQUEST['ACTION'] == 'CREATE') {
      $id = Insert_db_post('Articles',$Art);
    } 
  } elseif (isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
    $Art = Get_Article($id);
  } else {
    $id = -1;
    $Art = [];
  }
  
//  var_dump($Art);
  echo "To limit when article will appear give a start and/or end date.<p>Do NOT use a facebook image as a link - they are transient.<p>\n";
  echo "Set Title as @[Dance|Music|Other]_[Imp,Many] to have a random important Performer or a random performer along with a count<p>\n";

  echo "<form method=post>";
  echo "<table border>\n";
  echo "<tr>" . fm_text("Title",$Art,'SN');
    echo fm_text("Usage",$Art,'UsedOn');
    echo "<td colspan=2 rowspan=4>";
    if ($Art['Image']) {
      echo "<img src=" . $Art['Image'] . " height=200>";
    } else {
      echo "No Image";
    }
  echo "<tr><td>Importance:<td>" . fm_select($Importance,$Art,'Importance');
    echo "<td>Format:<td>" . fm_select($ArticleFormats,$Art,'Format');
  echo "<tr>" . fm_date("Start Date",$Art,'StartDate') . fm_date("Stop Date",$Art,'StopDate');
  echo "<tr>" . fm_text("Link - may be blank",$Art,'Link') . "<td>";
    if ($id > 0) echo fm_hidden('id',$id) . "id: $id";
  echo "<tr>" . fm_textarea("Text:<br>(some html)", $Art,'Text',6,10);
  echo "</table>";
  
  if ($id > 0) {
    echo "<input type=submit name=ACTION value=UPDATE>";
  } else {
    echo "<input type=submit name=ACTION value=CREATE>";
  }
  
  echo "</form><p>\n";
  
  echo "<h2><a href=ListArticles.php>List Articles</a></h2>\n";

  dotail();

?>
