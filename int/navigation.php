<div class=navigation>
<!-- <img src="/images/new.png" style="max-height:22px; margin:-1px 2px 0 -5px; float:left;" /> //-->

<ul id="menu">
<li><a style="color:#FFFFFF; margin-left:15px;" class="navbutton" href="/" rel="bookmark">Home</a></li>
<li><a style="color:#FFFFFF;" class="navbutton" href="/news" rel="bookmark">News</a></li>
<li>
  <a style="color:#FFFFFF;" class="navbutton" href="/line-up" rel="bookmark">Line-up</a>
    <ul>
        <li><a style="color:#FFFFFF;" class="navbuttondrop" href="/line-up/comedy" rel="bookmark">Comedy</a></li>
        <li><a style="color:#FFFFFF;" class="navbuttondrop" href="/line-up/dance" rel="bookmark">Dance</a></li>
        <li><a style="color:#FFFFFF;" class="navbuttondrop" href="/line-up/family" rel="bookmark">Family</a></li>
        <li><a style="color:#FFFFFF;" class="navbuttondrop" href="/line-up/music" rel="bookmark">Music</a></li>
        <li><a style="color:#FFFFFF;" class="navbuttondrop" href="/line-up/workshops" rel="bookmark">Workshops</a></li>
    </ul>
</li>
<li><a style="color:#FFFFFF;" class="navbutton" href="/tickets" rel="bookmark">Tickets</a></li>
<li>
  <a style="color:#FFFFFF;" class="navbutton" href="/info" rel="bookmark">Info</a>
    <ul>
        <li><a style="color:#FFFFFF;" class="navbuttondrop" href="/info/camping" rel="bookmark">Camping & Parking</a></li>
        <li><a style="color:#FFFFFF;" class="navbuttondrop" href="/info/getting-here" rel="bookmark">Getting Here</a></li>
        <li><a style="color:#FFFFFF;" class="navbuttondrop" href="/radio" rel="bookmark">Radio Wimborne</a></li>
        <li><a style="color:#FFFFFF;" class="navbuttondrop" href="/info/sponsorship" rel="bookmark">Sponsorship</a></li>
        <li><a style="color:#FFFFFF;" class="navbuttondrop" href="/info/stewards" rel="bookmark">Stewards</a></li>
        <li><a style="color:#FFFFFF;" class="navbuttondrop" href="/info/trade" rel="bookmark">Trade Stands</a></li>
        <li><a style="color:#FFFFFF;" class="navbuttondrop" href="/whats-on" rel="bookmark">What's On</a></li>
        <li><a style="color:#FFFFFF;" class="navbuttondrop" href="/info/thanks" rel="bookmark">With Thanks</a></li>
    </ul>
</li>
<li><a style="color:#FFFFFF;" class="navbutton" href="/gallery" rel="bookmark">Gallery</a>
    <ul>
        <li><a style="color:#FFFFFF;" class="navbuttondrop" href="/gallery/2016" rel="bookmark">2016 Photos</a></li>
        <li><a style="color:#FFFFFF;" class="navbuttondrop" href="/gallery/2015" rel="bookmark">2015 Photos</a></li>
        <li><a style="color:#FFFFFF;" class="navbuttondrop" href="/gallery/2014" rel="bookmark">2014 Photos</a></li>
        <li><a style="color:#FFFFFF;" class="navbuttondrop" href="/gallery/2013" rel="bookmark">2013 Photos</a></li>
    </ul>
</li>
<li>
  <a style="color:#FFFFFF;" class="navbutton" href="/contact" rel="bookmark">Contact</a></li>
<?php 
  include_once("int/fest.php");
  global $Access_Type;
  if (!isset($_SESSION) && !headers_sent()) session_start(); // should move this elsewhere and make test better
  if (isset($Access_Type) && isset($_SESSION{'WMFFtype'}) && $_SESSION{'WMFFtype'} >= $Access_Type['Committee']) {
    echo '<li><a style="color:#FFFFFF;" class="navbutton" href=/int/Staff.php>Staff Tools</a>';
  }
?>
<li><a href="http://facebook.com/WimborneFolk" rel="tag" target="_blank"><img src="/images/Facebook.png" alt="Facebook" title="Wimborne Minster Folk Festival on  Facebook" style="max-height:16px;" /></a></li>
<li><a href="http://twitter.com/WimborneFolk" rel="tag" target="_blank"><img src="/images/Twitter.png" alt="Twitter" title="Wimborne Minster Folk Festival on Twitter" style="max-height:16px;" /></a></li>
<li><a href="http://instagram.com/WimborneFolk" rel="tag" target="_blank"><img src="/images/Instagram.png" alt="Instagram" title="Wimborne Minster Folk Festival on Instagram" style="max-height:16px;" /></a></li>
<?php 
  if (isset($Access_Type) && isset($_SESSION{'WMFFtype'}) && $_SESSION{'WMFFtype'} >= $Access_Type['Upload']) {
    $naviuser = Get_User($_SESSION{'WMFFid'});
    echo "<li><a href=/int/Login.php?ACTION=LOGOUT>Logout " . $naviuser['Login'] . "</a>\n";
  }
?>
</ul></div>
