<?php
  include_once("fest.php");
  A_Check('SysAdmin');

  dostaffhead("Email to Live N Load Applications");
  global $db,$PLANYEAR;
  include_once("SignupLib.php");
  include_once("TradeLib.php");

  $res=$db->query("SELECT * FROM SignUp WHERE Year='$PLANYEAR' AND State<2 ORDER BY SN");
  
  if ($res) {
    while ($lnl = $res->fetch_assoc()) {
      $id = $lnl['id'];
      $whoto = $lnl['Email'];

      Email_Signup($lnl,'LNL_formLetter1',$whoto);
      echo "Emailed $id - $whoto<br>\n";
      for($i=0;$i<100;$i++) echo "                                                                                                                                     ";
      sleep(5);
    }
  }
  echo "All Done<p>";

  dotail();
?>
