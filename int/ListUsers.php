<?php
  include_once("fest.php");
  A_Check('Committee','Users');
?>

<html>
<head>
<title>WMFF Staff | List Users</title>
<?php include("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
</head>
<body>
<?php include("files/navigation.php"); ?>
<div class="content"><h2>List Users</h2>

<?php
  include("DocLib.php");
  include("UserLib.php");
  $AllU = Get_AllUsers();
  date_default_timezone_set('GMT');

  $qry = "SELECT * FROM FestUsers ORDER BY UserId";
  $res = $db->query($qry);

  $coln = 0;
  echo "Click on the Name or User Id to edit.  Click on column to sort by column.<p>\n";
  echo "Note the first 10 are reserved for internal workings (only the first two are currently used).  ";
  echo "System is for ownership of the document root directory, and nobody for the owner of files and directories ";
  echo "that were created by people no longer on the system.<p>";
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";

  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>User Id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Login</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Email</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>WMFF Email</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Access Level</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Roll</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Image</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Public</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Last Access</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Show</a>\n";
  foreach ($Sections as $sec) 
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>$sec</a>\n";
  echo "</thead><tbody>";

  while($usr = $res->fetch_assoc()) {
    echo "<tr><td>" . $usr['UserId'] . "<td><a href=AddUser.php?usernum=" . $usr['UserId'] . ">" . $usr['Name'] . "</a>";
    echo "<td>" . $usr['Login'] . "<td>" . $usr['Email'] . "<td>" . $usr['WMFFemail'] . "<td>" . $Access_Levels[$usr['AccessLevel']];
    echo "<td>" . $usr['Roll'] . "<td>";
    if ($usr['Image']) echo "<img src='" . $usr['Image'] . "' width=50>";
    echo "<td>" . ($usr['Contacts']?'Y':'N');
    echo "<td>";
    if ($usr['LastAccess']) echo date('d/m/y H:i:s',$usr['LastAccess']);
    echo "<td>";
    if ($usr['Contacts']) echo "Y";
    foreach ($Sections as $sec) {
      echo "<td>" . $Area_Levels[$usr[$sec]];
    }
  }
  echo "</tbody></table>\n";
  
  echo "<h2><a href=AddUser.php>Add User</a></a>";
?>
  
</div>
<?php include("files/footer.php"); ?>
</body>
</html>
