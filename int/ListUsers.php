<?php
  include_once("fest.php");
  
  if (isset($_REQUEST['FULL'])) {
    $Full = 1;
    A_Check('Committee','Users');
  } else {
    $Full = 0;
    A_Check('Staff');
  }

  dostaffhead("List Festival Users");
  include_once("DocLib.php");
  include_once("UserLib.php");

  $Users = Get_AllUsers(2);

  echo "<button class='floatright FullD' onclick=\"($('.FullD').toggle())\">All Users</button><button class='floatright FullD' hidden onclick=\"($('.FullD').toggle())\">Curent Users</button> ";

  $coln = 0;
  if ($Full) {
    echo "Click on the Name or User Id to edit.  Click on column to sort by column.<p>\n";
    echo "Note the first 10 are reserved for internal workings (only the first three are currently used).  ";
    echo "System is for ownership of the document root directory, and nobody for the owner of files and directories ";
    echo "that were created by people no longer on the system.<p>";
  }
  echo "<div class=tablecont><table id=indextable border>\n";
  echo "<thead><tr>";

  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>User Id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
//  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Abrev</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Login</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Email</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Phone</a>\n";  
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Fest Email</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Access Level</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Roll</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Rel Order</a>\n";
  if (feature('ShowContactPhotos')) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Image</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Public</a>\n";
  if ($Full) {
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Last Access</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Test User</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Show</a>\n";
    foreach ($Sections as $sec) 
      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>$sec</a>\n";
    echo "</thead><tbody>";
  }

  foreach ($Users as $usr) {
    if ($Full == 0 && $usr['NoTasks']) continue;
    echo "<tr" . (($usr['UserId']<11 || $usr['AccessLevel'] == 0)?" class=FullD hidden" : "" ) . ">";
    echo "<td>" . $usr['UserId'] . "<td>";
    echo  (($Full || Access('SysAdmin') || $USER['AccessLevel'] >= $usr['AccessLevel'])? ("<a href=AddUser?usernum=" . $usr['UserId'] . ">" . $usr['SN'] . "</a>") : $usr['SN']);
//    echo "<td>" . $usr['Abrev'];
    echo "<td>" . $usr['Login'] . "<td>" . $usr['Email'] . "<td>" . $usr['Phone'] . "<td>" . $usr['WMFFemail'] . "<td>" . $Access_Levels[$usr['AccessLevel']];
    echo "<td>" . $usr['Roll'] . "<td>" . $usr['RelOrder'] ;
    if ($usr['Image']) echo "<td><img src='" . $usr['Image'] . "' width=50>";
    if (feature('ShowContactPhotos')) echo "<td>" . $User_Public_Vis[$usr['Contacts']];
  
    if ($Full) { 
      echo "<td>";
      if ($usr['LastAccess']) echo date('d/m/y H:i:s',$usr['LastAccess']);
      echo "<td>";
      if ($usr['NoTasks']) echo "Y";   
      echo "<td>";
      if ($usr['Contacts']) echo "Y";
      foreach ($Sections as $sec) {
        echo "<td>" . $Area_Levels[$usr[$sec]];
      }
    }
  }
  echo "</tbody></table></div>\n";
  
  if ($Full) echo "<h2><a href=AddUser>Add User</a></a>";

  dotail();
?>

