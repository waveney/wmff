<?php
  include_once("int/fest.php");

  include_once("int/ContactLib.php");
  global $ContCatState, $ContCatColours,$PLANYEAR, $FESTSYS, $db;
  
  dohead("Contact Us",[],1);

  echo "<div class=tablecont><table class='FullWidth InfoTable'>";
  echo "<tr><th colspan=4>Main Contacts for $PLANYEAR Festival";
  
  $Teams=Get_ContactCats(1);
  
  foreach ($Teams as $t) {
    echo "<tr><td>" . $t['SN'] . "<td style='font-weight:bold; color:" . $ContCatColours[$t['OpenState']] . "'>" . $ContCatState[$t['OpenState']];
    echo "<td><a href='mailto:" . $t['Email'] . '@' . $FESTSYS['HostURL'] . "'>"  . $t['Email'] . '@' . $FESTSYS['HostURL'] . "</a>";
    echo "<td>" . $t['Description'];
  }
  echo "</table></div><p>";
  
/*
</tr>
<tr>
<td>Music</td>
<td style="font-weight:bold; color:#00CC00;">Open</td>
<td>Music applications are open for the 2020 festival.  If you or your band would like to perform please send a short description of your act with contact details and video or audio link </td>
</tr>
<tr>
<td>Dance</td>
<td style="font-weight:bold; color:#00CC00;">Open</td>
<td>Dance applications are open for the 2020 festival.</td>
</tr>
<tr>
<td>Trade Stands</td>
<td style="font-weight:bold; color:orange;">Closed</td>
<td>Trade stands will open in October.  For all <a href="/InfoTrade"><b>Trade Stand enquiries</b></a> that can not be answered by the <a href=/int/TradeFAQ><b>Trade FAQ</b></a>
</tr>
<tr>
<td>Volunteers</td>
<td style="font-weight:bold; color:#00CC00;">Open</td>
<td>Visit our <a href=/InfoStewards><strong>Volunteers</strong></a> page to apply online for stewarding, setup/cleardown crew, technical crew
and media team positions.</td>
</tr>
*/

  $ans = $db->query("SELECT * FROM FestUsers WHERE Contacts!=0 ORDER BY RelOrder DESC");
  while ($user = $ans->fetch_assoc()) {
    echo "<div class=smallfloatleft><div class=mini>\n";
    if ($user['WMFFemail']) echo "<a href=mailto:" . $user['WMFFemail'] . '@' . $FESTSYS['HostURL'] . ">";
    if (feature('ShowContactPhotos')) {
      echo "<img alt='Wimborne Minster Folk Festival' style='float:left; max-width:100px;max-height:100px;margin-right:5px' src='";
      echo ($user['Image']?$user['Image']:"/images/WimborneFolkLogo.png") . "'>";
    }
    if ($user['Contacts'] == 1) echo "<h2 class=minittl>" . $user['SN'] . "</h2>";
    echo "<span class=minitxt>" . $user['Roll'] . "</span>";
    if ($user['WMFFemail']) echo "<br>" . $user['WMFFemail'] . '@' . $FESTSYS['HostURL'] . "</a>";
    echo "</div></div>\n";
  }

/*
<!-- <h2 class="subtitle">Contact Us during the Festival Weekend</h2>
<p>If you need to get in touch with the festival committee during the festival weekend, please find a steward, ask at our Information Points on The Square or at The Allendale Centre or send an email to <a href="mailto:info@wimbornefolk.co.uk">info@wimbornefolk.co.uk</a> and we'll put you in touch with the committee.</p>

<p style="color:#CC0000; font-weight:bold;">In the event of an emergency, please find a steward or security or go to our first aid points (The Square or Willow Walk) or if life threatening, dial 999.</p> */

  echo "<br clear=all>";
  echo "Please note that we may not be able to respond to urgent emails and bear in mind that due to the high number of enquiries we get throughout the year, " .
       "we may not be able to respond every email.<p>";

  dotail();

