<?php
  include_once("fest.php");
  A_Check('Staff');

  dostaffhead("Dance Summary");
  include_once("DanceLib.php");
  echo "<h2>Dance Summary $YEAR</h2>\n";

  $Types = Get_Dance_Types(1);
  $Category = array(
                'Invited'=>"y.Invited<>''", 
                'Coming'=>("y.Coming=" . $Coming_Type['Y']),
                'Possibly'=>( "y.Coming=" . $Coming_Type['P']),
                'Not Coming'=>( "( y.Coming=" . $Coming_Type['N'] . " OR y.Coming=" . $Coming_Type['NY'] . " )"),
                'Recieved'=>( "y.Coming=" . $Coming_Type['R']),
                'No Reply'=>( "y.Invited<>'' AND y.Coming=0" ),
                'Bl'=>"Blank",
                'Coming on Sat'=>("y.Coming=" . $Coming_Type['Y'] . " AND y.Sat=1 "),
                'Coming on Sun'=>("y.Coming=" . $Coming_Type['Y'] . " AND y.Sun=1 "),
                'Bla'=>"Blank",
                'Fri Evening'=>("y.Coming=" . $Coming_Type['Y'] . " AND y.FriEve=1 "),
                'Sat Evening'=>("y.Coming=" . $Coming_Type['Y'] . " AND y.SatEve=1 "),
                ); 

  echo "<div class=tablecont><table border><tr><th>Category<th>Total";
  foreach ($Types as $typ) echo "<th style='background:" . $typ['Colour'] . ";'>" . $typ['SN'];
  echo "<th>Other</tr>\n";


  foreach ($Category as $cat=>$srch) {
    if ($srch == 'Blank') { echo "<tr height=15>"; continue; }
    $qtxt = "SELECT y.SideId FROM SideYear y WHERE y.Year='$YEAR' AND y.SideId>0 AND $srch";
    $qry = $db->query($qtxt);
    $catcount = $qry->num_rows;
    echo "<tr><td>$cat<td align=right>$catcount";
    $runtotal=0;
    foreach($Types as $typ) {
      $lctyp = strtolower($typ['SN']);
      $qtxt = "SELECT y.SideId, s.Type FROM SideYear y, Sides s WHERE y.SideId=s.SideId AND y.SideId>0 AND y.Year='$YEAR' AND $srch " .
                "AND LOWER(s.Type) LIKE '%$lctyp%'";
//var_dump($qtxt);
      $qry = $db->query($qtxt);
      $tcount = $qry->num_rows;
      //echo "<td>(($qtxt)) $tcount";
      echo "<td align=right style='background:" . $typ['Colour'] . ";'>$tcount";
      $runtotal += $tcount;
    }
    echo "<td align=right>" . max(0,$catcount - $runtotal) . "</tr>\n";
  }
  echo "</table></div>\n";
  dotail();
?>

