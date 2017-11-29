<!DOCTYPE html>
<html lang="en">
<head>
<title>Apply for Wimborne Buskers Bash 2017</title>
<?php include("files/header.php"); ?>
</head>
<body>

<?php include("files/facebook.php"); ?>

    <script>
      $(function() {
        $(".rslides").responsiveSlides();
      });
    </script>

<a href="/" rel="bookmark"><h1>Apply for Wimborne Buskers Bash 2017</h1></a>
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
<img src="/images/Buskers-Bash-3-2017.jpg" alt="Wimborne Minster Folk Festival" class="bioimg" />
<p>Apply for Wimborne Buskers Bash 2017!</p>
</div>

<h2 class="maintitle">Apply for Wimborne Buskers Bash 2017</h2>
	<p>Wimborne Buskers Bash will take place around the town on <strong>Saturday 13 May 2017</strong>. If you would like to take part, please apply below!</p>

<h2 class="subtitle">Wimborne Buskers Bash Application Form</h2>

<p>Please complete and send this <strong>Wimborne Buskers Bash</strong> application form.
<br />
<br /><i>If you are under 16, please ask a parent/guardian to submit an application.</i>
<form method="post" action="?send=confirm">
<input type="text" name="contactname" size="31" maxlength="150" placeholder="Contact Name *" />
<br /><textarea name="address" rows="4" cols="25" placeholder="Address *"></textarea>
<br /><input type="text" name="email" size="31" maxlength="150" placeholder="Email *" />
<br /><input type="text" name="phone" size="31" maxlength="15" placeholder="Phone *" /></p>

<h2 class="subtitle">Performance details</h2>
<p><input type="text" name="performername" size="31" maxlength="25" placeholder="Performer/Band Name *" />
<br /><input type="text" name="performernumber" size="31" maxlength="25" placeholder="Number of Performers *" />
<br /><input type="text" name="performerage" size="31" maxlength="25" placeholder="Performer Age(s): e.g. 17, 21 *" />
<br /><input type="text" name="performerinst" size="31" maxlength="150" placeholder="Instruments: e.g. guitar, drums *" />
<br /><input type="text" name="performergenre" size="31" maxlength="150" placeholder="Music genre: e.g. folk, rock *" /></p>

<p><strong>Buskers that are accepted will be expected to perform outside (inside in the event of wet weather) unaided from mains powered equipment, during the day on Saturday 13 May followed by an early evening final on The Square if you have been successfully accepted into the final.</strong>
<br />
<br /><i>A PA system will be provided for the final to amplify your instruments and vocals, your set must reflect your busking set from around the town.
<br /><input type="checkbox" name="tech" style="margin:0px 5px 0px 5px;" value="Yes"> I have a technical question. (We will get in touch)</i></p>

<h2 class="mintitle">Terms & Conditions</h2>
<p><ul>
<li>I am 16 or over and give/have permission for all musicians listed to perform.</li>
<li>As a family orientated music competition, all music must be clean and family friendly.</li>
<li>Disregard for the safety of other people or equipment will result in immediate removal from the stage and termination of your application/performance.</li>
<li>The festival organisers reserve the right to refuse applications and without explanation.</li>
<li>The festival organisers accept no liability for lost, damaged or stolen property.</li>
<li>All information specified on this form is treated as strictly confidential and will be held securely.</li>
</ul><strong>By signing below, you agree to our Terms & Conditions
<br />and confirm that details provided are correct.</strong>
<br /><input type="text" name="esig" size="31" maxlength="150" placeholder="E-Signature *" />
<br /><input type="submit" value="Send my Application Form"/>
</form>
</p>

<p>* Required field</p>

</div>
<?php include("files/footer.php"); ?>
</body>
</html>
