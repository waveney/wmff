<?php
  include_once("fest.php");
  A_Check('Committee');
  
  // TODO THIS IS TOTALLY DUFF!!!!! TODO TODO 
?>

<html>
<head>
<title>WMFF Staff | Music Summary</title>
<?php include_once("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
<?php include_once("DanceLib.php"); ?>
</head>
<body>
<?php include_once("files/navigation.php"); ?>
<?php
  echo "<div class=content><h2>Music Summary $YEAR</h2>\n";

  $Types = Get_Dance_Types(0);
  $Category = array(
                'Invited'=>"y.Invited<>''", 
                'Coming'=>("y.Coming=" . $Coming_Type['Y']),
                'Possibly'=>( "y.Coming=" . $Coming_Type['P'] ),
                'Not Coming'=>( "( y.Coming=" . $Coming_Type['N'] . " OR y.Coming=" . $Coming_Type['NY'] . " )"),
                'Outstanding'=>( "y.Invited<>'' AND ( y.Coming=0 OR y.Coming=" . $Coming_Type['R'] . ") " ),
                'Blank'=>"",
                'Coming on Sat'=>("y.Coming=" . $Coming_Type['Y'] . " AND y.Sat=1 "),
                'Coming on Sun'=>("y.Coming=" . $Coming_Type['Y'] . " AND y.Sun=1 ")
                ); 

  echo "<table border><tr><th>Category<th>Total";
  foreach ($Types as $typ) echo "<th>$typ";
  echo "<th>Other</tr>\n";


  foreach ($Category as $cat=>$srch) {
    if ($cat == 'Blank') { echo "<tr height=15>"; continue; }
    $qtxt = "SELECT y.SideId FROM SideYear y WHERE y.Year=$YEAR AND $srch";
    $qry = $db->query($qtxt);
    $catcount = $qry->num_rows;
    echo "<tr><td>$cat<td>$catcount";
    $runtotal=0;
    foreach($Types as $typ) {
      $lctyp = strtolower($typ);
      $qtxt = "SELECT y.SideId, s.Type FROM SideYear y, Sides s WHERE y.SideId=s.SideId AND y.Year=$YEAR AND $srch " .
                "AND LOWER(s.Type) LIKE '%$lctyp%'";
      $qry = $db->query($qtxt);
      $tcount = $qry->num_rows;
      //echo "<td>(($qtxt)) $tcount";
      echo "<td>$tcount";
      $runtotal += $tcount;
    }
    echo "<td>" . ($catcount - $runtotal) . "</tr>\n";
  }
  echo "</table>\n";

?>
  
</div>

<?php include_once("files/footer.php"); ?>
</body>
</html>

