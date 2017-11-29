<?php
  include_once("fest.php");
  A_Check('SysAdmin');
?>

<html>
<head>
<title>WMFF Staff | Dance CSV Import</title>
<?php include("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
<?php include_once("DanceLib.php"); ?>
</head>
<body>

<?php
  global $YEAR,$Invite_States,$Coming_States;
  
  function MungeInvite($t) {
    if (!$t) return 0;
    if (preg_match('/Yes/',$t)) return 1;
    if (preg_match('/YES/',$t)) return 2;
    if (preg_match('/No/i',$t)) return 3;
    if (preg_match('/Dead/i',$t)) return 3;
    if (preg_match('/prob/i',$t)) return 4;
    if (preg_match('/poss/i',$t)) return 5;
    if (preg_match('/\?/',$t)) return 5;
    if (preg_match('/Request/i',$t)) return 1;
    if (preg_match('/y/i',$t)) return 1;
    if (preg_match('/nrv/i',$t)) return 3;
    return $t . "XXXXXXXXXXXXXXXXXXXXXXXXXX";
  }      

  function MungeComing($t,$a) {
    if (!$t) return 0;
    if (preg_match('/^Y/i',$t)) return 2;
    if (preg_match('/^No/i',$t)) return 3;
    if (preg_match('/^N /i',$t)) return 3;
    if (preg_match('/^N$/i',$t)) return 3;
    if (preg_match('/^R /i',$t)) return ($a?1:0);
    if (preg_match('/Unlikely/i',$t)) return 3;
    if (preg_match('/Prob/i',$t)) return 4;
    if (preg_match('/Poss/i',$t)) return 5;
    if (preg_match('/clash/i',$t)) return 6;
    if (preg_match('/2018/i',$t)) return 7;
    if (preg_match('/next/i',$t)) return 7;
    if (preg_match('/check/i',$t)) return ($a?1:0);
    if (preg_match('/with/i',$t)) return ($a?1:0);
    return $t . "YYYYYYYYYYYYYYYYYYYYYY";
  }      

  function MungeInvited($t,$u) {
    if (!$t) return '';
    if (preg_match('/^R /i',$u)) return "$t $u";
    if (preg_match('/check/i',$u)) return "$t $u";
    return $t;
  }

  function Brack_Rem($stuf,&$brack) {
    if (preg_match('/(.*)\s?(\(.*\))/',$stuf,$br)) {
      $brack = $br[2];
      return $br[1];
    }
    return $stuf;
  }

// $Coming_States = array('','Recieved','Yes','No','Probably','Possibly','No clash','No, invite next year');
// $Invite_States = array('','Yes','YES','No','Prob','Poss','No Way');

  include("files/navigation.php");

  if (!isset($_FILES['CSVfile'])) {
    echo '<div class="content"><h2>Import Dance Sides</h2>';
    echo '<form method=post action="ImportDance.php" enctype="multipart/form-data">';
    echo "<input type=file name=CSVfile><br>";
    echo "Test Only: <input type=checkbox name=TestFull checked><br>";
    echo "<input type=submit name=Import value=Import><br></form>\n";
  } else {
    $TestOnly = $_POST['TestFull'];
    $F = fopen($_FILES["CSVfile"]["tmp_name"],"r");
    $headers = fgetcsv($F);
    foreach($headers as $i=>$d) $hindx[$d] = $i;

    if ($TestOnly) {
      $coln = 0;
      echo "<table id=indextable border class=smalltext>\n";
      echo "<thead><tr>";
      $heads = array('Name','Type','Alive','Bag','email','phone','mob','PA','Website','Photo','Description','prev','Snote','Invite','Invited','Come','Sat','Sun','Notes');
      foreach($heads as $h) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>$h</a>\n";
      echo "</thead><tbody>\n";
    };
    $Sc=0; $Sy=0;
    while (($bts = fgetcsv($F)) !== FALSE) {
      $stuff=array();
      $brack='';
      foreach ($headers as $i=>$d) $stuff[$d] = $bts[$i];
      $sideentry = array(
	'Name'=>$stuff['Name'],
	'Type'=>Brack_Rem($stuff['Dance Style'],$brack),
	'SideStatus'=>(preg_match('/Dead/i',$stuff['Invite'])?1:0),
	'Contact'=>$stuff['Bagman'],
	'Email'=>$stuff['Email address'],
	'Phone'=>$stuff['Landline'],
	'Mobile'=>$stuff['Mobile Phone'],
	'StagePA'=>$stuff['Tech'],
	'Website'=>$stuff['Web site'],
	'Photo'=>$stuff['Photo'],
	'Description'=>$stuff['Description'],
        'AccessKey'=>(rand_string(40)),
	'Notes'=>$brack,
	'Pre2017'=>$stuff['Previous Wimborne']);
      $sideyearent = array_merge(array(
	'Invite'=>MungeInvite($stuff['Invite']),
	'Invited'=>MungeInvited($stuff['Date invited'],$stuff['Yes or No']),
	'Coming'=>MungeComing($stuff['Yes or No'],$stuff['Invitation arrived']),
	'Arrive'=>$stuff['Arrive'],
	'Depart'=>$stuff['Depart'],
	'Sat'=>($stuff['Sat'] == 'y'?1:0),
	'Sun'=>($stuff['Sun'] == 'y'?1:0),
	'PrivNotes'=>($stuff['Workshops'].$stuff['useful info'].$stuff['special requests']),
	), Default_SY());
//var_dump("<tr><td>",$stuff);
      if ($stuff['Name']) {
        if ($TestOnly) {
	  echo "<tr><td>" . $sideentry['Name'] . "<td>" . $sideentry['Type'] . "<td>" . $sideentry['SideStatus']. "<td>" . $sideentry['Contact'];
	  echo "<td>" . $sideentry['Email'] . "<td>" . $sideentry['Phone'] . "<td>" . $sideentry['Mobile'];
	  echo "<td>" . $sideentry['StagePA'] . "<td>" . $sideentry['Website'] . "<td>" . $sideentry['Photo']. "<td>" . $sideentry['Description'];
          echo "<td>" . $sideentry['Pre2017']. "<td>" . $sideentry['Notes'];
	  echo "<td>" . $sideyearent['Invite'] . "<td>" . $sideyearent['Invited'] . "<td>" . $sideyearent['Coming'];
	  echo "<td>" . $sideyearent['Sat'] . "<td>" . $sideyearent['Sun'] . "<td>" . $sideyearent['PrivNotes'];
        } else {
	  $snum = Insert_db('Sides',$sideentry);
	  if ($snum) {
	    $Sc++;
	    if ($sideyearent['Invite'] == 1 || $sideyearent['Invite'] == 2 || $sideyearent['Coming'] > 0 ) {
	      $sideyearent['SideId'] = $snum;
	      $sny = Insert_db('SideYear',$sideyearent);
	      if ($sny == 0) {
		echo "Side " . $stuff['Name'] . " failed to setup year info.<br>";
	      } else {
		$Sy++;
	      }
	    }
	  } else {
	    echo "Side " . $stuff['Name'] . " failed to insert.<br>";
	  }
        }
      }
    } 
    if ($TestOnly) echo "</tbody></table>";
    echo "All done...";
    if (!$TestOnly) echo " $Sc Sides $Sy Side/Year records<br>";
  }
  
?>

</div>

<?php include("files/footer.php"); ?>
</body>
</html>
