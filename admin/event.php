<?php
  include_once("int/fest.php");
  A_Check('Committee','OldAdmin');

$server = "localhost:3306";
$uname = "jmnetwor_secure";
$pword = "D#6ilm0re";
$con = mysql_connect();//"$server","$uname","$pword");
$getid = $_GET['event'];
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

echo "<form method=\"post\" action=\"?editevent=confirm#event\">";
  while($row = mysql_fetch_array($eventresult))
  {
  $getcontent = $row['description'];
  $getcontent = str_replace('\\','',$getcontent);
  $postcontent = str_replace('<br />','',$getcontent); 
  echo "<tr>
<td style=\"background-color:inherit;\"><input type=\"text\" name=\"id\" value=\"$getid\" hidden /><input type=\"text\" name=\"title\" size=\"30\" placeholder=\"Title *\" value=\"".$row['title']."\"/></td>
</tr>
<tr>
<td style=\"background-color:inherit;\">
<select name=\"cat\">
<option value=\"".$row['cat']."\">Category: ".$row['cat']."</option>
<option value=\"ceilidh\">Category: Ceilidh</option>
<option value=\"comedy\">Category: Comedy</option>
<option value=\"dance\">Category: Dance</option>
<option value=\"family\">Category: Family</option>
<option value=\"music\">Category: Music</option>
<option value=\"session\">Category: Session</option>
<option value=\"workshop\">Category: Workshop</option>
</select></td>
</tr>
<tr>
<td style=\"background-color:inherit;\">
<select name=\"app\">
<option value=\"".$row['app']."\">Appalachian: ".$row['app']."</option>
<option value=\"true\">Appalachian: True</option>
<option value=\"false\">Appalachian: False</option>
</select></td>
</tr>
<tr>
<td style=\"background-color:inherit;\"><textarea name=\"content\" rows=\"5\" cols=\"24\" onKeyDown=\"limitText(this.form.content,this.form.countdown,7500);\" 
onKeyUp=\"limitText(this.form.content,this.form.countdown,7500);\" placeholder=\"Event Content\">$postcontent</textarea>
<p>You have <input readonly type=\"text\" name=\"countdown\" size=\"3\" value=\"7500\"> characters remaining!</p></td>
</tr>
<tr>
<td style=\"background-color:inherit;\"><input type=\"text\" name=\"imagecopy\" size=\"30\" value=\"".$row['img']."\" placeholder=\"Image URL\" /></td>
</tr>
<tr>
<td style=\"background-color:inherit;\"><input type=\"text\" name=\"link1\" size=\"30\" value=\"".$row['link1']."\" placeholder=\"Link 1\" /></td>
</tr>
<tr>
<td style=\"background-color:inherit;\"><input type=\"text\" name=\"link2\" size=\"30\" value=\"".$row['link2']."\" placeholder=\"Link 2\" /></td>
</tr>
<tr>
<td style=\"background-color:inherit;\"><input type=\"text\" name=\"facebook\" size=\"30\" value=\"".$row['facebook']."\" placeholder=\"Facebook URL\" /></td>
</tr>
<tr>
<td style=\"background-color:inherit;\"><input type=\"text\" name=\"twitter\" size=\"30\" value=\"".$row['twitter']."\" placeholder=\"Twitter Handle\" /></td>
</tr>
<tr>
<td style=\"background-color:inherit;\"><input type=\"text\" name=\"instagram\" size=\"30\" value=\"".$row['instagram']."\" placeholder=\"Instagram Handle\" /></td>
</tr>
<tr>
<td style=\"background-color:inherit;\"><input type=\"text\" name=\"youtube\" size=\"30\" value=\"".$row['youtube']."\" placeholder=\"YouTube Video Code\" /></td>
</tr>
<tr>
<td style=\"background-color:inherit;\">
<select name=\"headline\">
<option value=\"".$row['headline']."\">Spotlight: ".$row['headline']."</option>
<option value=\"true\">Spotlight: True</option>
<option value=\"false\">Spotlight: False</option>
</select></td>
</tr>
<tr>
<td style=\"background-color:inherit;\">
<select name=\"display\">
<option value=\"".$row['display']."\">Display: ".$row['display']."</option>
<option value=\"true\">Display: True</option>
<option value=\"false\">Display: False</option>
</select></td>
</tr>
<tr>
<tr>
<td style=\"background-color:inherit;\"><input type=\"text\" name=\"author\" size=\"30\" value=\"".$row['user']."\" placeholder=\"Author\" /></td>
</tr>";
 }

  echo "
<tr><td style=\"background-color:inherit;\"><input type=\"submit\" value=\"Edit Event Details\" /></td></tr></form>";
}

mysql_close($con);
?>
