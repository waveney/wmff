<!DOCTYPE html>
<html lang="en">
<head>
<title>Wimborne Minster Folk Festival | Apply to Trade in 2017</title>
<?php include("files/header.php"); ?>
</head>
<body>

<?php include("files/facebook.php"); ?>

    <script>
      $(function() {
        $(".rslides").responsiveSlides();
      });
    </script>

<a href="/" rel="bookmark"><h1>Wimborne Minster Folk Festival | Apply to Trade in 2017</h1></a>
<div class="navigation"><?php include("files/navigation.php"); ?></div>
<div class="content">

<?php
$getaction = strip_tags($_GET['send']);
$confirm = "confirm";

if($getaction === $confirm)
  {
  include("send.php");
  echo "</div>";
  include("files/footer.php");
  echo "</body></html>";
  die('');
  }

?>

<div class="biodiv">
<img src="/images/Jewellery-Trader-2016.jpg" alt="Wimborne Minster Folk Festival" class="bioimg" />
<p>Trading at Wimborne Minster Folk Festival 2016.</p>
</div>

<h2 class="maintitle">Apply to Trade in 2017</h2>
<p>Thank you for your interest in trading at Wimborne Minster Folk Festival in 2017, the festival will be held across the weekend of 9, 10, 11 June 2017.</p>

<h2 class="subtitle">Trade Application Form</h2>

<p>Please complete and send this <strong>non-food</strong> application form by <strong>31 March 2017</strong>.
<form method="post" action="?send=confirm">
<input type="text" name="business" size="31" maxlength="150" placeholder="Business Name" />
<br /><input type="text" name="contactname" size="31" maxlength="150" placeholder="Contact Name *" />
<br /><textarea name="invoiceaddress" rows="4" cols="25" placeholder="Invoice Address *"></textarea>
<br /><input type="text" name="email" size="31" maxlength="150" placeholder="Email *" />
<br /><input type="text" name="phone" size="31" maxlength="15" placeholder="Phone *" />
<br /><input type="text" name="products" size="31" maxlength="150" placeholder="Products Sold" />
<br /><input type="text" name="pitchsize" size="31" maxlength="150" placeholder="Size of Pitch (feet/meters)" />
<br /><input type="text" name="charity" size="31" maxlength="150" placeholder="Charity Number" /></p>
<h2 class="mintitle">Are you a Wimborne...</h2>
<p><select name="bidlevy">
<option value="No">BID Levy Payer? - No</option>
<option value="Yes">BID Levy Payer? - Yes</option>
</select>
<br /><select name="chamber">
<option value="No">Chamber of Trade Member? - No</option>
<option value="Yes">Chamber of Trade Member? - Yes</option>
</select>
<br /><select name="festivaltrader">
<option value="No">Previous Festival Trader? - No</option>
<option value="Yes">Previous Festival Trader? - Yes</option>
</select></p>
<h2 class="mintitle">Risk Assessment</h2>
<p><input type="file" name="risk" size="30" id="file" disabled/>
<br />If you are unable to attach your risk assessment, please send to <a href="mailto:trade@wimbornefolk.co.uk"><strong>trade@wimbornefolk.co.uk</strong></a> no later than <strong>5 May 2017</strong>.</p>
<h2 class="mintitle">Insurance Certificate</h2>
<p><input type="file" name="insurance" size="30" id="file" disabled/>
<br />If you are unable to attach your insurance certificate, please send to <a href="mailto:trade@wimbornefolk.co.uk"><strong>trade@wimbornefolk.co.uk</strong></a>. You <strong>must</strong> have a copy available with you during the festival weekend.</p>
<h2 class="mintitle">Terms & Conditions</h2>
<p><ul>
<li>You, the stallholder, must supply a gazebo and table, we are unable to locate your stand under cover.</li>
<li>Generators are not permitted unless previously arranged and agreed with the festival organisers.</li>
<li>You will be responsible for the health and safety of the general public, yourself and others around you and must co-operate with festival organisers and supervisors at all times.</li>
<li>The festival organisers reserve the right to refuse trade stand applications and without explanation.</li>
<li>The festival organisers accept no liability for lost, damaged or stolen property.</li>
<li>All information specified on this form is treated as strictly confidential and will be held securely.</li>
<li>Any persons sleeping in trade stands will result in removal of the stand immediately.</li>
<li>You will be solely responsible for removal of any rubbish and for cleaning your stand area to its original condition or paying for any damage caused.</li>
</ul><strong>By signing below, you agree to our Terms & Conditions
<br />and confirm that details provided are correct.</strong>
<br /><input type="text" name="esig" size="31" maxlength="150" placeholder="E-Signature *" />
<br /><input type="submit" value="Send my Application Form" />

<?php 
  include_once("int/fest.php");
  Set_User();
  if (Access('Committee','OldAdmin')) {
	    echo "<br />";
        echo "<input type=\"submit\" value=\"STAFF SUBMIT\"/>";
  }
?>
</form>
</p>

<p>* Required field</p>

<h2 class="subtitle">Pricing</h2>

<p>Pricing ranges from &#163;200.00 to &#163;490.00 depending on size and location of pitch, please view our <a href="/info/trade" rel="bookmark"><strong>Trade Stands</strong></a> page for more information about our pricing structure. Discounts are available for charity stands upon application.</p>

<p>Our committee will be in touch via email shortly to confirm if your application has been accepted, and will enclose a deposit invoice for &#163;50.00 with details for payment. Your deposit payment will need to be with us by <strong>31 March 2017</strong> in order to secure your place.</p>


</div>
<?php include("files/footer.php"); ?>
</body>
</html>
