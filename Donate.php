<?php
  include_once('int/fest.php');

  dohead("Donate",[],1);
  
  echo "<div class=donateCont>";
  echo "<div class=donate><img src='https://wimbornefolk.co.uk/int/images/gallery/2018/Workshops/18_LG5A8634_18-2048-STEPHENAJONES.jpg'><p>";
  echo "<h2>&pound;5</h2>Your donation will help our team of volunteers create a great festival!<p>";

  echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick" />
<input type="hidden" name="hosted_button_id" value="L239RDMFTQ3WN" />
<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1" />
</form>';
  echo "</div>";
  
  echo "<div class=donate><img src=https://wimbornefolk.co.uk/int/images/gallery/2017/20170611-WFF-Sunday-027-2048.jpg><p>";
  echo "<h2>&pound;10</h2>Your donation will support dance displays all around Wimborne.<p>";

  echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick" />
<input type="hidden" name="hosted_button_id" value="PSVATCKVB7GSL" />
<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1" />
</form>';
  echo "</div>";
  
  echo "<div class=donate><img src='https://wimbornefolk.co.uk/int/images/gallery/2018/Music/09_E25A9739_09-2048-HELENSJONES.jpg'><p>";
  echo "<h2>&pound;20</h2>Your donation will help us provide free outdoor music.<p>";
  
  echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick" />
<input type="hidden" name="hosted_button_id" value="YLC36ZFZHC78Y" />
<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1" />
</form>';
  echo "</div>";
  
  echo "<div class=donate><img src='https://wimbornefolk.co.uk/int/images/gallery/2018/Dance/DSCG6479.jpg'><p>";
  echo "<h2>Other</h2><p>";
  
  echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick" />
<input type="hidden" name="hosted_button_id" value="DGJJ58N7GPF26" />
<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1" />
</form>';

  echo "</div></div>";

//  echo "<p class=smaller>Donations handled through Paypal, all major credit cards accepted<p>";
  dotail();
?>
