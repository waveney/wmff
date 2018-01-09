<?php
  include_once("fest.php");
  A_Check('Steward');
?>

<html>
<head>
<title>WMFF Staff | List Events</title>
<?php include("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
</head>
<body>
<?php include("files/navigation.php"); ?>
<div class="content"><h2>List Venues</h2>

<?php
  global $Surfaces;
  $yn = array('','Y');
  include_once("ProgLib.php");
  $venues = Get_Venues(1);

  $coln = 0;
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";

  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Venue Id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Short Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Full Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Notes</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Status</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Dance Order</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Music Order</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Other Order</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Dance Setup Overlap</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Dance</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Music</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Child</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Craft</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Other</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Surface 1</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Surface 2</a>\n";
  echo "</thead><tbody>";

  if ($venues) {
    foreach ($venues as $Ven) {
      $i = $Ven['VenueId'];
      echo "<tr><td>$i<td><a href=AddVenue.php?v=$i>" . $Ven['ShortName'] . "</a>";
      echo "<td><a href=AddVenue.php?v=$i>" . $Ven['Name'] . "</a>"; 
      echo "<td>" . $Ven['Notes'] . "<td>" . $Venue_Status[$Ven['Status']];
      echo "<td>" . $Ven['DanceImportance'];
      echo "<td>" . $Ven['MusicImportance'];
      echo "<td>" . $Ven['OtherImportance'];
      echo "<td>" . $yn[$Ven['SetupOverlap']];
      echo "<td>" . $yn[$Ven['Dance']];
      echo "<td>" . $yn[$Ven['Music']];
      echo "<td>" . $yn[$Ven['Child']];
      echo "<td>" . $yn[$Ven['Craft']];
      echo "<td>" . $yn[$Ven['Other']] ."\n";
      echo "<td>" . $Surfaces[$Ven['SurfaceType1']];
      echo "<td>" . $Surfaces[$Ven['SurfaceType2']] . "\n";
    }
  }
  echo "</tbody></table>\n";
  
  if (Access('Committee','Venues')) {
    echo "<h2><a href=AddVenue.php>Add Venue</a></h2>";
  }
?>
  
</div>
<?php include("files/footer.php"); ?>
</body>
</html>
