</div></div>
<br clear=all  style='height=0'>
<div class="footer">

<div id="flex">

<!--
<?php
  global $FESTSYS,$CALYEAR;
  include_once("int/NewsLib.php");
  $items = Get_All_News(0,5);
  if ($items) {
    echo "<div class=mini style='border-bottom:0; margin-bottom:0;'>";
    echo "<h2 class=footersubtitle>Latest News</h2>\n";
    foreach ($items as $n) News_List_Item($n);
    echo "</div>";
  }
?>
-->
<div class="mini" style="border-bottom:0; margin-bottom:0;text-decoration:none;">

Photos remain Copyright of their respective owners and cannot be reproduced or used without permission.</p>

<p>Festival Software by <a href=http://wavwebs.com style=color:white;>Waveney Web Services</a> - Version 
<?php 
  echo $FESTSYS['V'];
  if (!isset($_COOKIE{'WMFF2'})) echo " <a href=/int/Login style='color:white; float:right;'> Staff Login</a><p>\n";
?>
</div>
</div>
</div>
