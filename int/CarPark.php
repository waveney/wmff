
<?php
  include_once("fest.php");
  A_Check('Committee','Dance');
  
  dominimalhead("Car Park Address Labels");

/* 
  Select Sides to print
  display - no back links
*/

  $flds = "s.*, y.Invite, y.Coming";
  $SideQ = $db->query("SELECT s.*, y.* FROM Sides AS s LEFT JOIN SideYear as y ON s.SideId=y.SideId AND y.year=$YEAR ORDER BY SN");
  $tot_perf = 0;

  echo "<h2>Car Parking needed</h2>\n";
  echo "<table border><tr><th>Side<th>Saturday<th>Sunday\n";
  while ($side = $SideQ->fetch_assoc()) {
    if ($side['Performers'] > 0) $tot_perf += $side['Performers'];
    if ($side['CarPark']) {
      echo "<tr><td>" . SName($side) . "<td>";
      if ($side['Sat']) echo $side['CarPark'];
      if ($side['Sun']) echo "<td>" . $side['CarPark'];
    }
  }
  echo "</table>\n";
  echo "Total Performers: " . $tot_perf;
  echo "</body></html>\n";
?>
