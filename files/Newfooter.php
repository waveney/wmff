<?php
  global $CALYEAR,$MASTER_DATA;
  echo "</div></div><br clear=all  style='height=0'><div class=footer>";

  echo "<div class=widthLim>";
  echo "<div class=VersionBy>";
  echo "Website supported by <a href=http://wavwebs.com style=color:white;>Waveney Web Services</a> - Version " . $MASTER_DATA['V'];
  if (!isset($_COOKIE{'WMFF2'})) echo "<a href=/int/Login.php style='color:white; float:right;'>Staff Login</a><p>\n";

  echo "</div><div class=copyright style='text-decoration:none;'>";
  echo "Copyright &copy; <a href='/' style='color:white;'>Wimborne Minster Folk Festival Ltd</a> $CALYEAR All Rights Reserved. Registered Company: 08290423<br>";

  echo "Photos remain Copyright of their respective owners and cannot be reproduced or used without permission.";
  echo "</div></div></div><div id=LastDiv></div>";
?>
