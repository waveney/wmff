<?php
  include_once("int/fest.php");
  A_Check('Committee','OldAdmin');

$server = "localhost:3306";
$uname = "jmnetwor_secure";
$pword = "D#6ilm0re";
$con = mysql_connect();//"$server","$uname","$pword");
$getid = $_GET['id'];
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("wmff", $con);

$result = mysql_query("SELECT * FROM wmfftrade ORDER BY applydate ASC");

if(empty($getid))
{
echo "<form method=\"get\" action=\"?id=$getid#news\"><tr><td style=\"background-color:inherit;\"><select name=\"id\">";
  while($row = mysql_fetch_array($result))
  {
  $gettitle = $row['title'];
  $gettitle = substr($gettitle,0,25);
  $gettitle = substr($gettitle, 0, strrpos($gettitle, ' '));
  echo "<option value=\"".$row['id']."\">".$row['id']." &raquo; $gettitle...</option>";
  }
echo "</select></td></tr><tr><td style=\"background-color:inherit;\"><input type=\"submit\" value=\"Continue\" /></td></tr></form>";
}

$newresult = mysql_query("SELECT * FROM wmff WHERE id='$getid'");

if(!empty($getid))
{

echo "<form method=\"post\" action=\"?editnews=confirm#news\">";
  while($row = mysql_fetch_array($newresult))
  {
  $getcontent = $row['content'];
  $getcontent = str_replace('\\','',$getcontent);
  $postcontent = str_replace('<br />','',$getcontent); 
  echo "<tr>
<td style=\"background-color:inherit;\"><input type=\"text\" name=\"id\" value=\"$getid\" hidden /><input type=\"text\" name=\"title\" size=\"30\" placeholder=\"Title\" value=\"".$row['title']."\"/></td>
</tr>
<tr>
<td style=\"background-color:inherit;\"><input type=\"text\" name=\"imagecopy\" size=\"30\" value=\"".$row['image']."\" placeholder=\"Image URL\" /></td>
</tr>
<tr>
<td style=\"background-color:inherit;\"><input type=\"text\" name=\"caption\" size=\"30\" value=\"".$row['caption']."\" placeholder=\"Caption\" /></td>
</tr>
<tr>
<td style=\"background-color:inherit;\"><textarea name=\"content\" rows=\"5\" cols=\"24\" onKeyDown=\"limitText(this.form.content,this.form.countdown,7500);\" 
onKeyUp=\"limitText(this.form.content,this.form.countdown,7500);\" placeholder=\"Article Content\">$postcontent</textarea>
<p>You have <input readonly type=\"text\" name=\"countdown\" size=\"3\" value=\"7500\"> characters remaining!</p></td>
</tr>
<tr>
<td style=\"background-color:inherit;\"><input type=\"text\" name=\"author\" size=\"30\" value=\"".$row['author']."\" placeholder=\"Author\" /></td>
</tr>
<tr>
<td style=\"background-color:inherit;\"><input type=\"text\" name=\"articledate\" size=\"30\" value=\"".$row['articledate']."\" placeholder=\"YYYY-MM-DD HH:MM:SS\" /></td>
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
<td style=\"background-color:inherit;\"><input type=\"submit\" value=\"Edit News Article\" /></td>
</tr>";
  }
  echo "</form>";
}

mysql_close($con);
?>
