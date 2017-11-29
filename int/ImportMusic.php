<?php
  include_once("fest.php");
  A_Check('SysAdmin');
?>

<html>
<head>
<title>WMFF Staff | Music Import</title>
<?php include("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
<?php include_once("MusicLib.php"); ?>
</head>
<body>

<?php
  global $YEAR,$db;
  include("files/navigation.php");
  
function Descript($stuff) {
  $Ans = preg_split("/\./",$stuff,2);
  if ($Ans) return $Ans;
  return array($stuff,'');
}

  if (!isset($_POST['Import'])) {
    echo '<div class="content"><h2>Import Music Acts</h2>';
    echo '<form method=post action="ImportMusic.php">';
    echo "Test Only: <input type=checkbox name=TestFull checked><br>";
    echo "<input type=submit name=Import value=Import><br></form>\n";
  } else {
    $TestOnly = $_POST['TestFull'];

    if ($TestOnly) {
      $coln = 0;
      echo "<table id=indextable border class=smalltext>\n";
      echo "<thead><tr>";
      $heads = array('Name','Website','Photo','Description','Blurb','Twitter','Instagram','Video','Facebook');
      foreach($heads as $h) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>$h</a>\n";
      echo "</thead><tbody>\n";
    };

    $Sc=0; $Sy=0;
    $res = $db->query("SELECT * FROM wmffevent WHERE cat='music'");

    while($stuff = $res->fetch_assoc()) {
//echo "<p>";var_dump($stuff);
      list($desc,$blurb) = Descript($stuff['description']);
      if ($new = preg_replace("/^ ?<br \/>\n<br \/>\n/","",$blurb)) $blurb=$new;
      if ($new = preg_replace("/^ ?<br \/>\r\n<br \/>\r\n/","",$blurb)) $blurb=$new;
      $sideentry = array(
	'Name'=>$stuff['title'],
	'IsAnAct'=>1,
	'Website'=>$stuff['link1'] . ($stuff['link2']?(" " . $stuff['link2']):""),
	'Photo'=>"/images/" . $stuff['img'],
	'Twitter'=>$stuff['twitter'],
	'Description'=>$desc,
	'Blurb'=>$blurb,
	'Instagram'=>$stuff['instagram'],
	'Facebook'=>$stuff['facebook'],
	'Video'=>$stuff['youtube'],
        'AccessKey'=>(rand_string(40)),
	'Pre2017'=>'Previous Wimborne');
      if ($TestOnly) {
	echo "<tr><td>" . $sideentry['Name'] ;
	echo "<td>" . $sideentry['Website'] . "<td>" . $sideentry['Photo']. "<td>" . $sideentry['Description'];
        echo "<td>" . $sideentry['Blurb']. "<td>" . $sideentry['Twitter'];
	echo "<td>" . $sideentry['Instagram'] . "<td>" . $sideentry['Video'] ."<td>" . $sideentry['Facebook'];
      } else {
        $snum = Insert_db('Sides',$sideentry);
	if (!$snum) {
	  echo "Side " . $stuff['Name'] . " failed to insert.<br>";
        }
      }
    } 
    if ($TestOnly) echo "</tbody></table>";
    echo "All done...";
    if (!$TestOnly) echo " $Sc Sides records<br>";
  }
  
  dotail();
?>
