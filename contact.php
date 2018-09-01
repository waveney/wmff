<?php
  include_once("int/fest.php");

  dohead("Contact Us");

?>
<h2 class="maintitle">Contact Us</h2>

<p><table cellspacing="5" cellpadding="5" style="background-color:#005983; border-color:#005983;">
<tr>
<th colspan="3">Applications for 2019 Festival</th>
</tr>
<tr>
<td>Music</td>
<td style="font-weight:bold; color:orange;">Opening soon</td>
<td>Music applications are <strong>Opening soon</strong> for the 2019 festival.</td>
</tr>
<tr>
<td>Dance</td>
<td style="font-weight:bold; color:#00CC00;">Open</td>
<td>Dance applications are open for the 2019 festival, please email Judith.</td>
</tr>
<tr>
<td>Trade Stands</td>
<td style="font-weight:bold; color:#00cc00;">Open</td>
<td><a href="/info/trade"><strong>Trade Stands</strong></a>
</tr>
<tr>
<td>Volunteers</td>
<td style="font-weight:bold; color:#00CC00;">Open</td>
<td>Visit our <a href=/InfoStewards.php><strong>Volunteers</strong></a> page to apply online for stewarding, technical crew, artistic team and media team positions.</td>
</tr>
</table></p>

<?php
  global $db;
  $ans = $db->query("SELECT * FROM FestUsers WHERE Contacts!=0 ORDER BY SNAME");
  while ($user = $ans->fetch_assoc()) {
    echo "<div class=smallfloatleft><div class=mini>\n";
    if ($user['WMFFemail']) echo "<a href=mailto:" . $user['WMFFemail'] . "@wimbornefolk.co.uk>";
    echo "<img alt='Wimborne Minster Folk Festival' style='float:left; max-width:100px;max-height:100px;margin-right:5px' src='";
    echo ($user['Image']?$user['Image']:"/images/WimborneFolkLogo.png") . "'>";
    if ($user['Contacts'] == 1) echo "<h2 class=minittl>" . $user['SN'] . "</h2>";
    echo "<br><p class=minitxt>" . $user['Roll'] ;
    if ($user['WMFFemail']) echo "<br>" . $user['WMFFemail'] . "@wimbornefolk.co.uk</a>";
    echo "</div></div>\n";
  }

?>

<!-- <h2 class="subtitle">Contact Us during the Festival Weekend</h2>
<p>If you need to get in touch with the festival committee during the festival weekend, please find a steward, ask at our Information Points on The Square or at The Allendale Centre or send an email to <a href="mailto:info@wimbornefolk.co.uk">info@wimbornefolk.co.uk</a> and we'll put you in touch with the committee.</p>

<p style="color:#CC0000; font-weight:bold;">In the event of an emergency, please find a steward or security or go to our first aid points (The Square or Willow Walk) or if life threatening, dial 999.</p> //-->

<p>Please note that we may not be able to respond to urgent emails and bear in mind that due to the high number of enquiries we get throughout the year, we may not be able to respond every email.</p>

<?php
  dotail()
?>
