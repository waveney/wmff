<?php
  include_once("int/fest.php");

  dohead("Trade Stands");

  global $PLANYEAR;
  echo "<div class=biodiv>
<img src=/images/Chutney-Trader-2016.jpg alt='Wimborne Minster Folk Festival' class=bioimg>
<p>One of our traders.</p>
</div>

<h2 class=maintitle>Trade Stands</h2>

We are proud to host a wide range of traders at Wimborne Minster Folk Festival and if you're a trader, you can apply to trade at the $PLANYEAR festival now!<p>

<a href=/TraderList.php>Partial List of traders</a><p>

<h2 class=subtitle>Trade in $PLANYEAR</h2>
Applications for trading at the festival, which will be held over the weekend of " . FestDate(0,'L') . " to " . FestDate(2,'L') . " are <a href=/int/Trader.php>OPEN</a>.<p>

Prices for $PLANYEAR trade stands are listed below.  See also the <a href=/int/TradeFAQ.php><strong>Trade FAQ</strong></a>.  
Any queries should be sent to <a href='mailto:trade@wimbornefolk.co.uk'><strong>trade@wimbornefolk.co.uk</strong></a>.<p>

Trade Stands are not allocated on a first come first served basis, each application is assessed on the choice, quality, 
origin and pricing structure to ensure a fair and varied amount of traders are offered a place.<p>

After your application is submitted and accepted. You will, in most cases, need to promptly pay a deposit to secure your place.<p>

All applicants are required to provide their insurance and a completed Risk Assessment form. <p>

All food and drink Traders are required to 
indicate which local authority they are registered with.<p>

";
  echo "<h2 class=subtitle><a href=/int/Trader.php>To Trade Start Here</a></h2>\n";

  include_once "int/TradePublic.php";
  Trade_Type_Table('Sattab');

  echo "<h2 class=subtitle><a href=/int/Trader.php>To Trade Start Here</a></h2>\n";

  dotail();
?>
