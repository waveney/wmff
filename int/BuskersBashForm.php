<?php
  include("fest.php");

  dostaffhead("Buskers Bash Application", "/js/Participants.js");

  include_once("SignupLib.php");
  global $USER,$USERID,$db,$THISYEAR;

  /* In the longer term this will be based on participants, but I want to do this quickly for 2018 so it is stand alone for now */

  if (isset($_POST['submit'])) {
    if (strlen($_POST['Name']) < 2) { echo "<p class=Err>Please give your band's name\n"; $err=1; };
    if (strlen($_POST['Example'])< 12) { echo "<p class=Err>Please give a link to an example of you performing - Youtube or equivalent\n"; $err=1; };
    if (strlen($_POST['Contact']) < 6) { echo "<p class=Err>Please give the contact name\n"; $err=1; };
    Clean_Email($_POST{'Email'});
    if (strlen($_POST['Email']) < 6) { echo "<p class=Err>Please give the contacts Email\n"; $err=1; };
    if (strlen($_POST['Phone']) < 6) { echo "<p class=Err>Please give the contacts Phone number\n"; $err=1; };
//    if (strlen($_POST['Address']) < 20) { echo "<p class=Err>Please give the contacts Address\n"; $err=1; };
    if (!$_POST['TickBox']) { echo "<p class=Err>Please Tick the Box to show you have read the guidelines\n"; $err=1; };
    if (!$err) {
//      echo "<P>VALID...<P>";
      $_POST['AccessCode'] = rand_string(40);
      $_POST['Year'] = $THISYEAR;
      $_POST['Activity'] = 4;
      $id = Insert_db_post('SignUp',$bb);
    
      Email_BB_Signup($bb,'BB_Application',$bb['Email']);
      Email_BB_Signup($bb,'BB_karen','festikaz@hotmail.co.uk');
      
      echo "<h2 class=bigtitle>Thankyou for submitting your application</h2>";
      dotail();
      exit();
    }
  }

  
  echo "<h2 class=subtitle>Buskers Bash 2018 Application Form</h2>\n";
  echo "<form method=post action=BuskersBashForm.php>";
  echo "<table border>\n";
  echo "<tr>" . fm_text1("Band/Group/Act Name",$_POST,'Name',2);
  echo "<tr><td colspan=4>Main Contact:\n";
  echo "<tr>" . fm_text('Name',$_POST,'Contact');
  echo "<tr>" . fm_text('Email',$_POST,'Email');
  echo "<tr>" . fm_text('Phone',$_POST,'Phone');
//  echo "<tr>" . fm_text('Address',$_POST,'Address',4);
//  echo "<tr>" . fm_text('Postcode',$_POST,'PostCode');
  echo "<tr>" . fm_text('Example of you playing - YouTube or equivalent',$_POST,'Example');
  echo "<tr><td>" . fm_checkbox("I have read the guidelines",$_POST,'TickBox');
  echo "</table><p>";
  echo "<input type=submit name=submit value='Submit Application' onclick=$('#Patience').show()><p>\n";   
  echo "<h2 hidden class=Err id=Patience>This takes a few moments, please be patient</h2>"

?>
<h2 class=subtitle>Guildlines</h2>
Please register at the Info Point on The Square between 10.30 - 11.30<p>

<ul>
<li>The busking programme will run from 11.30 - 5.00 (approx), with each busker
having a 20 min performance time at various locations around town.

<li>Each busker will have an ID badge, to be worn at all times. You will also be
responsible for a festival collecting bucket.

<li>Please return your badge &amp; festival bucket after your final performance.

<li>Voting for the buskers will be by tokens available at the Info Point from 11.30, &amp;
from Stewards at each busking location, &amp; around town. Tokens cost &pound;1 for 10.

<li>Stewards will exchange your bucket for an empty one at each location, or you can
return it to the Info Point yourself. Counting of tokens will take place throughout the
day by a Festival Committee member.

<li>You can also put out your own cash collection container in an equal position to that of
the festival. The public can then choose where to donate - hopefully both!

<li>You can only use the festival approved times &amp; performance areas, as indicated by
our 'Busk Stop' signs.



<li>All performance areas are completely acoustic as there is no power available for you
to use. You may bring your own battery powered amp if you wish, but volume must
be kept at a low level &amp; with a consideration to members of the public.

<li>This is not an open invitation for any busker to just turn up &amp; play. Unapproved
performers will be removed.

<li>Please remember that this is a family friendly event, &amp; as such your performances
must be suitable for all ages.

<li>Following the end of busking programme, the five buskers with the most votes will be
invited to perform on the stage in The Square. Approx time 5.15 - 7.0.

<li>Judges appointed by the festival will decide the winners, but audience participation is
incouraged!

<li>The overall winner will receive a prize of &pound;100 &amp; an invitation to be part of the
Buskers Programme at Wimborne Minster Folk Festival in June. The runner-up will
receive a prize of &pound;50

<li>Thank you for joining us, &amp; helping to make Buskers Bash an ongoing success.
</ul>

We hope that you enjoy your day with us in Wimborne.
<?php

  dotail();

?>
