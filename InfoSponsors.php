<?php
  include_once("int/fest.php");

  dohead("Sponsorship",[],1);

?>

<h2>About the Festival</h2>

<p><strong>Wimborne Minster Folk Festival</strong> is Wimborne's biggest event of the year.  Over the second weekend of June, it attracts tens of thousands of people into the town for three days of folk, family and fun.</p>

<p>One of the UK's premier in-town folk festivals, it draws people from all over the UK, including a high proportion from across the Dorset, Hampshire and Wiltshire area likely to return to spend money in the town.</p>

<p>'Folk, family and fun' are at the heart of the festival: celebrating folk music and dance; offering a family-friendly atmosphere; and providing a range of fun activities for all ages.</p>

<p>Wimborne is in the spotlight over the folk festival weekend, with TV, radio, press and online media coverage showcasing what the town has to offer.  We are highly active on social media, reaching thousands of people across <a href="http://facebook.com/WimborneFolk" rel="tag" target="_blank"><strong>Facebook</strong></a>, <a href="http://twitter.com/WimborneFolk" rel="tag" target="_blank"><strong>Twitter</strong></a> and <a href="http://instagram.com/WimborneFolk" rel="tag" target="_blank"><strong>Instagram</strong></a>.</p>

<p>Organised entirely by volunteers, the festival aims to deliver a range of benefits to Wimborne's economy and community.  We are proud to have the backing of East Dorset District Council, Wimborne Minster Town Council and the Wimborne Business Improvement District (BID).</p>

<h2 class="subtitle">How to Help</h2>

<p>Our sponsors play a vital role in helping the festival thrive and continue 'giving back' to Wimborne for years to come.  As a sponsor, your business would be aligned with our family-friendly values and enjoy multiple opportunities to reach thousands of valuable potential customers.  We offer a range of sponsorship packages to suit any budget.</p>

<p>If you are interested in supporting the festival, please contact committee secretary <a href="/contact.php" rel="bookmark"><strong>Graham Brown</strong></a>.</p>

<?php 
  global $SHOWYEAR;
  set_ShowYear();
  echo "<h2 class=subtitle>Our Sponsors in $SHOWYEAR</h2>";

  echo "<div id=flex>\n";


  include_once("int/TradeLib.php");
  $Spons = Get_Sponsors();

  foreach ($Spons as $s) {
    echo "<div class=mini>\n";
    if ($s['Website']) echo weblinksimple($s['Website']);
    if ($s['Image']) echo "<img src='" . $s['Image'] . "' class=miniing width=200>";
    echo "<h2 class=minittl>" . $s['SN'] . "</h2>";
    if ($s['Website']) echo "</a>";
    if ($s['Description']) echo "<p><span class=minitxt>" . $s['Description'] . "</span>";
    echo "</div>\n";
  }

  dotail();
?>
