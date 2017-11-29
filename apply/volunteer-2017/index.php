<!DOCTYPE html>
<html lang="en">
<head>
<title>Wimborne Minster Folk Festival | Apply to Volunteer in 2017</title>
<?php include("files/header.php"); ?>
</head>
<body>

<?php include("files/facebook.php"); ?>

    <script>
      $(function() {
        $(".rslides").responsiveSlides();
      });
    </script>

<a href="/" rel="bookmark"><h1>Wimborne Minster Folk Festival | Apply to Volunteer in 2017</h1></a>
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
<img src="/images/Young-Volunteer-2015.jpg" alt="Wimborne Minster Folk Festival" class="bioimg" />
<p>Volunteers getting stuck in at Wimborne Minster Folk Festival!</p>
</div>

<h2 class="maintitle">Apply to Volunteer in 2017</h2>
<p>Thank you for your interest in volunteering at Wimborne Minster Folk Festival in 2017, we really couldn't do it without you!! Save the date for the next festival, which will be held across the weekend of 9, 10, 11 June 2017.</p>

<h2 class="subtitle">Volunteer Application Form</h2>

<p>Please complete and send this <strong>volunteer</strong> application form ASAP and no later than <strong>31 May 2017</strong>.
<form method="post" action="?send=confirm">
<input type="text" name="contactname" size="31" maxlength="150" placeholder="Contact Name *" />
<br /><textarea name="address" rows="4" cols="25" placeholder="Address *"></textarea>
<br /><input type="text" name="email" size="31" maxlength="150" placeholder="Email *" />
<br /><input type="text" name="phone" size="31" maxlength="15" placeholder="Phone *" />
<br /><input type="text" name="dob" size="31" maxlength="25" placeholder="Date of Birth *" />
<br /><input type="text" name="workteam" size="31" maxlength="150" placeholder="I would like to work with:" /></p>
<h2 class="subtitle">Which team suits you?</h2>

<p><input type="checkbox" name="steward" style="margin:0px 10px 0px 0px;" value="Yes">Stewarding (Info Points, Concerts, Road Closures, Street Collecting etc)
<br /><input type="checkbox" name="tech" style="margin:0px 10px 0px 0px;" value="Yes">Technical (Stage Crew, Runners, Setup/Packdown etc)
<br /><input type="checkbox" name="artistic" style="margin:0px 10px 0px 0px;" value="Yes">Artistic (Setting up art displays, town decorations etc)
<br /><input type="checkbox" name="media" style="margin:0px 10px 0px 0px;" value="Yes">Media (Photography, Videography etc)
<br /><input type="text" name="duties" size="31" maxlength="150" placeholder="My preferred duties are:" />
<br /><input type="text" name="unable" size="31" maxlength="150" placeholder="Jobs I am unable to do:" />
</p>
<h2 class="mintitle">Availability</h2>
<p>Please list times that you are <strong>NOT</strong> available during the festival weekend.</p>
<p><input type="text" name="thu" size="31" maxlength="25" placeholder="Thursday: e.g. 4pm - 6pm" />
<br /><input type="text" name="fri" size="31" maxlength="25" placeholder="Friday: e.g. 9am - 11am" />
<br /><input type="text" name="sat" size="31" maxlength="25" placeholder="Saturday: e.g. 4pm - 6pm" />
<br /><input type="text" name="sun" size="31" maxlength="25" placeholder="Sunday: e.g. 9am - 11am" />
<br /><input type="text" name="mon" size="31" maxlength="25" placeholder="Monday: e.g. 4pm - 6pm" /></p>
<h2 class="mintitle">Legal Stuff</h2>
<p>Do you have a current DBS check? <input type="radio" name="dbs" value="Yes" /> Yes <input type="radio" name="dbs" value="No" checked /> No
<br /><input type="text" name="dbsdetails" size="31" maxlength="150" placeholder="If Yes, please give details:" /></p>
<p>Please enter details of an <strong>Emergency Contact</strong>.
<br /><input type="text" name="emergencycontact" size="31" maxlength="150" placeholder="Emergency Contact Name *" />
<select name="relationship">
<option value="">Relationship: *</option>
<option value"Husband">Husband</option>
<option value"Wife">Wife</option>
<option value"Partner">Partner</option>
<option value"Mother">Mother</option>
<option value"Father">Father</option>
<option value"Son">Son</option>
<option value"Daughter">Daughter</option>
<option value"Brother">Brother</option>
<option value"Sister">Sister</option>
<option value"Grandparent">Grandparent</option>
<option value"Grandchild">Grandchild</option>
<option value"Uncle">Uncle</option>
<option value"Auntie">Auntie</option>
<option value"CloseFriend">Close Friend</option>
<option value"Other">Other</option>
</select>
<br /><input type="text" name="emergencyphone" size="31" maxlength="150" placeholder="Mobile & Landline *" /></p>
<p><i>We hope to not contact them, but need their details just in case.</i></p>
<h2 class="mintitle">Terms & Conditions</h2>
<p><ul>
<li>I am, or will be over 18 years of age on Thursday 8 June 2017.</li>
<li>You will be responsible for the health and safety of the general public, yourself and others around you and must co-operate with festival organisers and supervisors at all times.</li>
<li>All volunteers must ensure that they are never, under any circumstances, alone with any person under the age of 18.</li>
<li>The festival organisers reserve the right to refuse volunteer applications and without explanation.</li>
<li>The festival organisers accept no liability for lost, damaged or stolen property.</li>
<li>All information specified on this form is treated as strictly confidential and will be held securely.</li>
</ul><strong>By signing below, you agree to our Terms & Conditions
<br />and confirm that details provided are correct.</strong>
<br /><input type="text" name="esig" size="31" maxlength="150" placeholder="E-Signature *" />
<br /><input type="submit" value="Send my Application Form"/>
</form>
</p>

<p>* Required field</p>

<p>We will inform you several weeks before the festival which job we would like you to do, and ask you to confirm as soon as possible. We will try to respect your wishes.</p>


</div>
<?php include("files/footer.php"); ?>
</body>
</html>
