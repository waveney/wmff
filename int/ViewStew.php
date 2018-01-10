<?php
  include_once("fest.php");
  A_Check('Committee');

  dostaffhead("View Stewarding form");
  include_once("SignupLib.php");
  global $db, $StewClasses, $Days, $Relations;
  
  if (isset($_POST{'id'})) { /* Response to update button */
    $id = $_POST['id'];
    $stew = Get_Steward($id);
    Clean_Email($_POST{'Email'});
    Update_db_post('Stewards',$stew);
  } else {
    $id = $_GET['id'];
    $stew = Get_Steward($id);
  }

  echo "<h2 class=subtitle>Steward / Volunteer Application Form</h2>\n";
  echo "<form method=post action=ViewStew.php>";
  echo "<table border>\n";
  echo "<tr>" . fm_text('Name',$stew,'Name',2);
  echo fm_hidden('id',$id);
  echo "<tr>" . fm_text('Email',$stew,'Email',2);
  echo "<tr>" . fm_text('Phone(s)',$stew,'Phone',2);
  echo "<tr>" . fm_text('Address',$stew,'Address',4);
  echo "<tr>" . fm_text('Postcode',$stew,'PostCode');
  echo "<tr>" . fm_text('Date of Birth',$stew,'Birthday');

  echo "<tr><td colspan=4><h3>Which Team(s) would you like to volunteer for?</h3>\n";
  echo "<tr><td colspan=4>";
  foreach ($StewClasses as $c=>$exp) {
    echo fm_checkbox($c,$stew,"SC_$c") . $exp . "<br>\n";
  }

  echo "<tr>" . fm_text('Prefered Duties',$stew,'Prefer',4) . "<br>Include any activity you would particularly like to be a steward for";
  echo "<tr>" . fm_text('Disliked Duties',$stew,'Dislike',4) . "<br>Include any activity you would particularly like to NOT be a steward for";

  echo "<tr><td colspan=4><h3>Availability</h3>If you could help on the days below, please give the times you would be available\n";
  $D = -2;
  foreach ($Days as $d=>$ld) {
    if ($D >=0 && $D<3) {
      echo "<tr>" . fm_text($ld . " " . ($MASTER['DateFri']+$D++) . "th June $THISYEAR",$stew,"Avail$d",4);
    } else { $D++; };
  }

  echo "<tr><td colspan=4><h3>Legal</h3>\n";
  echo "<tr><td colspan=4>Do you have a current DBS check? if so please give details<br>" . fm_textinput('DBS',$stew['DBS'],'size=100');
  echo "<tr><td colspan=4><h3>Emergency Contact</h3>\n";
  echo "<tr>" . fm_text('Contact Name',$stew,'ContactName',2);
  echo "<tr>" . fm_text('Contact Phone',$stew,'ContactPhone',2);
  echo "<tr><td>Relationship:<td>" . fm_select($Relations,$stew,'Relation');
  echo "</table><p>";
  echo "<input type=submit name=submit value='Change Application'><p>\n"; 
  echo "</form>\n";

  echo "<h2><a href=StewardView.php>Back to list of Stewards</a>";
  dotail();
?>
