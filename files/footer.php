</div></div>
<br clear=all  style='height=0'>
<div class="footer">

<div id="flex">

<!--
<?php
  global $MASTER_DATA,$CALYEAR;
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
<div class="mini" style="border-bottom:0; margin-bottom:0;">
Copyright &copy; <a href="/" style="color:#FFFFFF;">Wimborne Minster Folk Festival Ltd</a> <?php echo $CALYEAR ?>; All Rights Reserved. Registered Company: 08290423</p>

<p>Photos remain Copyright of their respective owners and cannot be reproduced or used without permission.</p>

<p>Website supported by <a href=http://wavwebs.com style=color:white;>Waveney Web Services</a> - Version 
<?php 
  echo $MASTER_DATA['V'];
  if (!isset($_COOKIE{'WMFF2'})) echo "<a href=/int/Login.php style='color:white; float:right;'>Staff Login</a><p>\n";
?>
</div>
</div>
</div>
