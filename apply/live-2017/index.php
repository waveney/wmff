<!DOCTYPE html>
<html lang="en">
<head>
<title>Wimborne Minster Folk Festival | Apply for Live & Loud 2017</title>
<?php include("files/header.php"); ?>
</head>
<body>

<?php include("files/facebook.php"); ?>

    <script>
      $(function() {
        $(".rslides").responsiveSlides();
      });
    </script>

<a href="/" rel="bookmark"><h1>Wimborne Minster Folk Festival | Apply for Live & Loud 2017</h1></a>
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
<img src="/images/Live-and-Loud-2017.jpg" alt="Wimborne Minster Folk Festival" class="bioimg" />
<p>Aged 13 - 26? Apply for Live & Loud 2017!</p>
</div>

<h2 class="maintitle">Apply for Live & Loud 2017</h2>
<p>Aged 13 to 26? Apply to take part in our youth music competition taking place between April & May and win a performance slot at Wimborne Minster Folk Festival on the weekend of 9, 10, 11 June 2017, plus many more prizes!</p>

<h2 class="subtitle">Live & Loud Application Form</h2>

<p>Please complete and send this <strong>Live & Loud</strong> application form.
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
<br /><input type="text" name="performergenre" size="31" maxlength="150" placeholder="Music genre: e.g. folk, rock *" />
<br /><input type="text" name="performerdemo" size="31" maxlength="150" placeholder="Demo Link (live preferred) *" /></p>

<h2 class="subtitle">Preferred audition date</h2>
<p><input type="checkbox" name="squarerecords" style="margin:0px 10px 0px 0px;" value="Yes" disabled>Square Records, BH21 1HU -  23 April (solos 16+)
<br /><input type="checkbox" name="whitehart" style="margin:0px 10px 0px 0px;" value="Yes">White Hart, BH21 1JL - 28 April 7:30pm to 11pm (duos/trios 16+)
<br /><input type="checkbox" name="risingsun" style="margin:0px 10px 0px 0px;" value="Yes">Rising Sun, BH21 1DX - 4 May 7pm to 11pm (bands 16+)
<br /><strong>Over 16's semi-finals take place at Olive Branch, BH21 1PF - 12 May 7pm to 11pm!</strong>
<br /><input type="checkbox" name="qe" style="margin:0px 10px 0px 0px;" value="Yes">QE School, BH21 4DT - 19 May 7pm to 11pm (under 16's)
<br /><strong>The grand final takes place at Allendale Centre, BH21 1AS - 26 May 7pm to 11pm!</strong>
<br />
<br /><i>A PA system will be provided at each venue, but please bring any amps, drums, guitar cables etc <u>that you will need so that you can perform</u>! <input type="checkbox" name="tech" style="margin:0px 5px 0px 5px;" value="Yes"> I have a technical question. (We will get in touch)</i></p>

<h2 class="mintitle">Terms & Conditions</h2>
<p><ul>
<li>I am 16 or over and give/have permission for all musicians listed to perform.</li>
<li>As a youth music competition, all music must be clean and family friendly.</li>
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

<p>If you successfully win a place on stage at the festival, you will be expected to perform a 30 minute set.</p>


</div>
<?php include("files/footer.php"); ?>
</body>
</html>
