<tr>
<td style="background-color:inherit;"><input type="text" name="title" size="30" placeholder="Title *" /></td>
</tr>
<tr>
<td style="background-color:inherit;">
<select name="cat">
<option value="ceilidh">Category: Ceilidh</option>
<option value="comedy">Category: Comedy</option>
<option value="dance">Category: Dance</option>
<option value="family">Category: Family</option>
<option value="music">Category: Music</option>
<option value="session">Category: Session</option>
<option value="workshop">Category: Workshop</option>
</select></td>
</tr>
<tr>
<td style="background-color:inherit;">
<select name="app">
<option value="false">Appalachian: False</option>
<option value="true">Appalachian: True</option>
</select></td>
</tr>
<tr>
<td style="background-color:inherit;"><textarea name="content" rows="5" cols="24" onKeyDown="limitText(this.form.content,this.form.countdown,7500);" 
onKeyUp="limitText(this.form.content,this.form.countdown,7500);" placeholder="Event Content"></textarea>
<p>You have <input readonly type="text" name="countdown" size="3" value="7500"> characters remaining!</p></td>
</tr>
<?php
$getimage = strip_tags($_GET['image']);
if(!empty($getimage))
{
echo "<tr><td style=\"background-color:inherit;\"><input type=\"text\" name=\"imagecopy\" size=\"30\" value=\"$getimage\" /></td></tr>";
}

else
{
echo "<tr><td style=\"background-color:inherit;\"><input type=\"text\" name=\"imagecopy\" size=\"30\" placeholder=\"Image URL\" /></td></tr>";
}
?>
<tr>
<td style="background-color:inherit;"><input type="text" name="link1" size="30" placeholder="Link 1" /></td>
</tr>
<tr>
<td style="background-color:inherit;"><input type="text" name="link2" size="30" placeholder="Link 2" /></td>
</tr>
<tr>
<td style="background-color:inherit;"><input type="text" name="facebook" size="30" placeholder="Facebook URL" /></td>
</tr>
<tr>
<td style="background-color:inherit;"><input type="text" name="twitter" size="30" placeholder="Twitter Handle" /></td>
</tr>
<tr>
<td style="background-color:inherit;"><input type="text" name="instagram" size="30" placeholder="Instagram Handle" /></td>
</tr>
<tr>
<td style="background-color:inherit;"><input type="text" name="youtube" size="30" placeholder="YouTube Video Code" /></td>
</tr>
<tr>
<td style="background-color:inherit;">
<select name="headline">
<option value="false">Spotlight: False</option>
<option value="true">Spotlight: True</option>
</select></td>
</tr>
<tr>
<td style="background-color:inherit;">
<select name="display">
<option value="true">Display: True</option>
<option value="false">Display: False</option>
</select></td>
</tr>
<tr>
<td style="background-color:inherit;"><input type="submit" value="Add Event Details" /></td>
</tr>