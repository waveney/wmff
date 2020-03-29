<?php
  include_once("fest.php");

  A_Check('SysAdmin');
  
  include_once("TradeLib.php");
  dostaffhead("Update TradeYear Records");

  global $db,$YEAR;
  
  $Trads = [499,575,41,39,734,27,723,5,634,742,64,727,26,118,681,120,508];
  
  foreach ($Trads as $tid) {
    $Trady = Get_Trade_Year($tid);
    $Trady['DateChange'] = 2; 
    Put_Trade_Year($Trady);
    echo "Done $tid<br>";
  }
  echo "Finished<p>";

  dotail();
?>

