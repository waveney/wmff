</div></div>
<br clear=all>
<div class="footer">

<div id="flex">
<?php
  include_once("int/NewsLib.php");
  $items = Get_All_News(0,5);
  if ($items) {
    echo "<div class=mini style='border-bottom:0; margin-bottom:0;'>";
    echo "<h2 class=footersubtitle>Latest News</h2>\n";
    foreach ($items as $n) News_List_Item($n);
    echo "</div>";
  }
?>

<!--
<div class="mini" style="border-bottom:0; margin-bottom:0;">
<div class="text-align:center; margin-bottom:25px;"><a href="http://facebook.com/WimborneFolk" rel="tag" target="_blank"><img src="/images/Facebook-Logo-White.png" alt="Wimborne Minster Folk Festival" style="max-width:120px;" /></a>

<a href="http://twitter.com/WimborneFolk" rel="tag" target="_blank"><img src="/images/Twitter-Logo-White.png" alt="Wimborne Minster Folk Festival" style="max-width:120px;" /></a>

<a href="http://instagram.com/WimborneFolk" rel="tag" target="_blank"><img src="/images/Instagram-Logo-White.png" alt="Wimborne Minster Folk Festival" style="max-width:120px;" /></a>

</div></div>
-->

<div class="mini" style="border-bottom:0; margin-bottom:0;">
<!--
<h2 class=footersubtitle>Legal</h2>

<p><a href="/" rel="bookmark" style="color:#FFFFFF;">Home</a> | 
<a href="/news" rel="bookmark" style="color:#FFFFFF;">News</a> | 
<a href="/line-up" rel="bookmark" style="color:#FFFFFF;">Line-up</a> | 
<a href="/tickets" rel="bookmark" style="color:#FFFFFF;">Tickets</a> | 
<a href="/info" rel="bookmark" style="color:#FFFFFF;">Info</a> | 
<a href="/gallery" rel="bookmark" style="color:#FFFFFF;">Gallery</a> | 
<a href="/contact" rel="bookmark" style="color:#FFFFFF;">Contact</a></p>
-->
<p>Copyright &copy; <a href="/" rel="bookmark" style="color:#FFFFFF;">Wimborne Minster Folk Festival Ltd</a> <?php echo gmdate("Y"); ?>; All Rights Reserved. Registered Company: 08290423</p>

<p>Photos remain Copyright @ of their respective owners and cannot be reproduced or used without permission.</p>

<p>Website supported by <a href=http://wavwebs.com style=color:white;>Waveney Web Services</a>
<?php 
  if (!isset($_COOKIE{'WMFF2'})) echo "<a href=/int/Login.php style='color:white; float:right;'>Staff Login</a><p>\n";
?>
</div>
</div>
</div>
