<?php
  include_once("int/fest.php");

  dohead("Data Privacy",[],1);

  if (isset($_POST['CHECK'])) {
    include_once("int/DataCheck.php");
    $Email = $_POST['Email'];
    Data_Check_Emails($Email);
    echo "<H2 class=subtitle>Data has been checked</h2>";
    echo "If you are in the database, an email has been sent with link(s) to the data - if you don't see one check your Spam trap<p>";
  } else {
    echo "<h2 class=subtitle>Data in our Database</h2>\n";
    echo "This site does not need any cookies, unless you are a staff member logging in.<p>";
    echo "The festival has a database of sides, performers, traders and volunteers.<p>";
    echo "If you would like to check your records, enter your email address and an email will be sent back to you allowing you to view/edit any records we have.<p>";
    echo "<form method=post action=InfoData>";
    echo fm_text('Email Address',$_POST,'Email');
    echo "<input type=submit name=CHECK value=CHECK>\n";
  }
  dotail();
?>
