<?php
  include_once("fest.php");
  A_Check('SysAdmin');

  dostaffhead("Email to Live N Load Applications");
  global $db,$THISYEAR;
  include("SignupLib.php");
  include("TradeLib.php");

  $res=$db->query("SELECT * FROM SignUp WHERE Year=$THISYEAR AND State<2 ORDER BY Name");
  
  if ($res) {
    while ($lnl = $res->fetch_assoc()) {
      $id = $lnl['id'];
      $whoto = $lnl['Email'];

      Email_Signup($lnl,'LNL_formLetter1',$whoto);
      echo "Emailed $id - $whoto<br>\n";
      flush();
      sleep(5);
    }
  }
  echo "All Done<p>";

  dotail();
?>
