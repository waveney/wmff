<?php
  include_once("int/fest.php");
  A_Check('Committee','OldAdmin');

$server = "localhost:3306";
$uname = "jmnetwor_secure";
$pword = "D#6ilm0re";
$con = mysql_connect();//"$server","$uname","$pword");
$getid = $_GET['event'];
$techid = $_GET['tech'];
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("wmff", $con);

$result = mysql_query("SELECT * FROM wmffevent ORDER BY title ASC");

if(empty($getid))
{
echo "<form method=\"get\" action=\"?event=$getid#event\"><tr><td style=\"background-color:inherit;\"><select name=\"event\">";
  while($row = mysql_fetch_array($result))
  {
  $gettitle = $row['title']." ";
  $gettitle = substr($gettitle,0,25);
  $gettitle = substr($gettitle, 0, strrpos($gettitle, ' '));
  echo "<option value=\"".$row['id']."\">$gettitle</option>";
  }
echo "</select></td></tr><tr><td style=\"background-color:inherit;\"><input type=\"submit\" value=\"Continue\" /></td></tr></form>";
}

$eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid'");

if(!empty($getid))
{

  while($row = mysql_fetch_array($eventresult))
  {
  $eventid = $row['id'];
  $eventtitle = $row['title'];

  echo "<tr><td style=\"background-color:inherit;\"><input type=\"text\" name=\"title\" size=\"30\" placeholder=\"Title *\" value=\"$eventtitle\" disabled/></td></tr>";

$techresult = mysql_query("SELECT * FROM wmfftech WHERE eventid='$getid'");

  while($row = mysql_fetch_array($techresult))
  {
  $id = $row['id'];
  $filename = $row['filename'];
  if(!empty($filename))
  {
  $postfile = "$filename | <a href=\"?event=$getid&tech=$id#event\" rel=\"bookmark\">Edit</a><br /><br />";
  }

  echo "<tr><td style=\"background-color:inherit;\">$postfile</td></tr>";

  }

    if(!empty($techid))
      {
      $techeditresult = mysql_query("SELECT * FROM wmfftech WHERE id='$techid'");
      while($row = mysql_fetch_array($techeditresult))
      {
      $findeventid = $row['eventid'];
      $filename = $row['filename'];

echo "<form method=\"post\" action=\"?edittech=confirm#event\"><tr><td style=\"background-color:inherit;\"><input type=\"text\" name=\"techid\" value=\"$techid\" hidden /><input type=\"text\" name=\"editfilename\" size=\"30\" placeholder=\"Filename\" value=\"$filename\"/></td></tr>
<tr><td style=\"background-color:inherit;\"><input type=\"submit\" value=\"Edit Tech Specs\" /></td></tr></form>";
      }
      }


echo "<form method=\"post\" action=\"?addtech=confirm#event\">
<tr><td style=\"background-color:inherit;\"><input type=\"text\" name=\"eventid\" value=\"$eventid\" hidden /><h2 class=\"mintitle\">Add Tech Spec</h2></td></tr>
<tr><td style=\"background-color:inherit;\"><input type=\"text\" name=\"filename\" size=\"30\" placeholder=\"Filename\" /></td></tr>
<tr><td style=\"background-color:inherit;\"><input type=\"submit\" value=\"Add Tech Spec\" /></td></tr></form>";

  }

}

mysql_close($con);
?>
