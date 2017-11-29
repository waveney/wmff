<?php
  include_once("int/fest.php");
  A_Check('Committee','OldAdmin');

$getupload = $_GET['upload'];
$confirm = "confirm";

if($getupload === 'confirm')
{
	include("upload.php");
die('');
}

$getfile = $_GET['uploadfile'];
$confirm = "confirm";

if($getfile === $confirm)
{
include ("admin/upload-file.php");
die('');
}

$getaddnews = $_GET['addnews'];
$confirm = "confirm";

if($getaddnews === $confirm)
{
include ("admin/addnews.php");
}

$geteditnews = $_GET['editnews'];
$confirm = "confirm";

if($geteditnews === $confirm)
{
include ("admin/editnews.php");
}

$getaddevent = $_GET['addevent'];
$confirm = "confirm";

if($getaddevent === 'confirm')
{
include ("addevent.php");
}

$geteditevent = $_GET['editevent'];
$confirm = "confirm";

if($geteditevent === $confirm)
{
include ("admin/editevent.php");
}

$getaddshow = $_GET['addshow'];
$confirm = "confirm";

if($getaddshow === $confirm)
{
include ("admin/addshow.php");
}

$geteditshow = $_GET['editshow'];
$confirm = "confirm";

if($geteditshow === $confirm)
{
include ("admin/editshow.php");
}

$getaddtech = $_GET['addtech'];
$confirm = "confirm";

if($getaddtech === $confirm)
{
include ("admin/addtech.php");
}

$getedittech = $_GET['edittech'];
$confirm = "confirm";

if($getedittech === $confirm)
{
include ("admin/edittech.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>WimborneFolk.co.uk | Admin Home</title>
<?php include("files/header.php"); ?>
</head>
<body>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&appId=320647184654064&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

    <script>
      $(function() {
        $(".rslides").responsiveSlides();
      });
    </script>

<?php
// include("files/sessions/notstaff.php"); 
?>

<a href="/" rel="bookmark"><h1>WimborneFolk.co.uk | Admin Home</h1></a>
<div class="navigation"><?php include("files/navigation.php"); ?></div>
<div class="content">

<h2 class="maintitle">Admin Home</h2>

<p>Manage your website content.</p>
	
	<p><a href="live?view=confirm"><strong>Live & Loud Applications</strong></a>
		<br /><a href="trade"><strong>Trade Applications</strong></a>
		<br /><a href="vols?view=confirm"><strong>Volunteer Applications</strong></a>
		<br /><a href="buskersbash?view=confirm"><strong>Buskers Bash Applications</strong></a></p>

<div id="flex">

<div class="adminarticle">
<h2 class="adminarticlettl">Upload Image</h2>
<?php
$getimage = strip_tags($_GET['image']);
if(!empty($getimage))
{
echo "<img class=\"articleimg\" src=\"/images/$getimage\" />";
}
?>
<p class="adminarticletxt">Upload an image to use on the website.
<br /><br />The standard size for images used are 800 x 536 pixels under 100kb to allow for fast loading, images need to be edited to this size before upload as the system does not resize them.
<br /><br />An image preview will appear here after upload and a link will automatically be placed in new article/event forms below.
<form method="post" action="?upload=confirm" enctype="multipart/form-data">
<table cellpadding="1" cellspacing="1" style="margin-left:10px; max-width:300px;">
<?php
$getimage = strip_tags($_GET['image']);
if(!empty($getimage))
{
echo "<tr><td style=\"background-color:inherit;\"><input readonly type=\"text\" name=\"imagepreview\" size=\"30\" value=\"$getimage\" /></td></tr>";
}
?>
<tr>
<td style="background-color:inherit;"><input type="file" name="file" size="30" id="file" /> <input type="submit" value="Upload Image" /></td>
</tr>
</table>
</form>
</p>
</div>

<div class="adminarticle">
<h2 class="adminarticlettl">Upload Tech Spec File</h2>
<?php
$gettech = strip_tags($_GET['tech']);
if(!empty($gettech))
{
echo "<p class=\"adminarticletxt\"><a href=\"files/tech/$gettech\" /><strong>$gettech</strong></a></p>";
}
?>
<p class="adminarticletxt">Upload a tech spec.
<br /><br />Your successful upload will appear here.
<form method="post" action="?uploadfile=confirm" enctype="multipart/form-data">
<table cellpadding="1" cellspacing="1" style="margin-left:10px; max-width:300px;">
<?php
$gettech = strip_tags($_GET['tech']);
if(!empty($gettech))
{
echo "<tr><td style=\"background-color:inherit;\"><input readonly type=\"text\" name=\"imagepreview\" size=\"30\" value=\"$gettech\" /></td></tr>";
}
?>
<tr>
<td style="background-color:inherit;"><input type="file" name="file" size="30" id="file" /> <input type="submit" value="Upload Tech Spec" /></td>
</tr>
</table>
</form>
</p>
</div>

</div>

<a name="news"></a>
<h2 class="subtitle">Add/Edit News</h2>

<div id="flex">


<div class="adminarticle">
<h2 class="adminarticlettl">Add News Article</h2>
<p class="adminarticletxt">Add a news article to the website. This will instantly appear on the homepage, news page and other pages across the website.
<form method="post" action="?addnews=confirm#news">
<table cellpadding="1" cellspacing="1" style="margin-left:10px; max-width:300px;">
<tr>
<td style="background-color:inherit;"><input type="text" name="title" size="30" placeholder="Title" /></td>
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
<td style="background-color:inherit;"><input type="text" name="caption" size="30" placeholder="Image Caption" /></td>
</tr>
<tr>
<td style="background-color:inherit;"><textarea name="content" rows="5" cols="24" onKeyDown="limitText(this.form.content,this.form.countdown,7500);" 
onKeyUp="limitText(this.form.content,this.form.countdown,7500);" placeholder="Article Content"></textarea>
<p>You have <input readonly type="text" name="countdown" size="3" value="7500"> characters remaining!</p></td>
</tr>
<tr>
<td style="background-color:inherit;">
<select name="display">
<option value="true">Display: True</option>
<option value="false">Display: False</option>
</select></td>
</tr>
<tr>
<td style="background-color:inherit;"><input type="submit" value="Add News Article" /></td>
</tr>
</table>
</form>
</p>
</div>

<div class="adminarticle">
<h2 class="adminarticlettl">Edit News Article</h2>
<p class="adminarticletxt">Edit one of our existing news articles on the website, whether it's published (displayed) or not.
<table cellpadding="1" cellspacing="1" style="margin-left:10px; max-width:300px;">


<?php include("admin/news.php"); ?>

</table>
</p>
</div>

</div>

<a name="event"></a>
<h2 class="subtitle">Add/Edit Event Information</h2>

<div id="flex">


<div class="adminarticle">
<h2 class="adminarticlettl">Add Event</h2>
<p class="adminarticletxt">Add the details of an act appearing at WMFF, which will be displayed around the website.
<form method="post" action="?addevent=confirm&event=<?php
$con = mysql_connect();
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("wmff", $con);

$result = mysql_query("SELECT * FROM wmffevent");
$num_rows = mysql_num_rows($result);

while($row = mysql_fetch_array($result))
  {
  $nextnumber = $num_rows+1;
  }

  echo "$nextnumber";
?>#event">
<table cellpadding="1" cellspacing="1" style="margin-left:10px; max-width:300px;">

<?php include("admin/createevent.php"); ?>

</table>
</form>
</p>
</div>

<div class="adminarticle">
<h2 class="adminarticlettl">Add Show</h2>
<p class="adminarticletxt">After adding the event details, add the show information of when, where and price.
<table cellpadding="1" cellspacing="1" style="margin-left:10px; max-width:300px;">

<?php include("admin/show.php"); ?>

</table>
</p>
</div>

<div class="adminarticle">
<h2 class="adminarticlettl">Add Tech Spec</h2>
<p class="adminarticletxt">Add a tech spec file for the selected band.
<table cellpadding="1" cellspacing="1" style="margin-left:10px; max-width:300px;">

<?php include("admin/tech.php"); ?>

</table>
</p>
</div>

<div class="adminarticle">
<h2 class="adminarticlettl">Edit Event Details</h2>
<p class="adminarticletxt">Select an event that you would like to update, click continue and then update the event details.
<table cellpadding="1" cellspacing="1" style="margin-left:10px; max-width:300px;">


<?php include("admin/event.php"); ?>


</table>
</p>
</div>

</div>

</div>
<?php include("files/footer.php"); ?>
</body>
</html>
