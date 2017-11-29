<?php
$con = mysql_connect();//"$server","$uname","$pword");

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

$findid = strip_tags($_GET['event']);
$blank = "";
  
mysql_select_db("wmff", $con);

$eventresult = mysql_query("SELECT * FROM wmffevent
WHERE id='$findid'");

while($row = mysql_fetch_array($eventresult))
  {
  $getid = $row['id'];
  $display = $row['display'];
  }
$loadpage = "<html>
<head>
<script type=\"text/javascript\">
<!--
function delayer(){
    window.location = \"/\"
}
//-->
</script>
</head>
<body onLoad=\"setTimeout('delayer()', 0000)\">
</body>
</html>
";

if(empty($findid))
{
echo "$loadpage";
die('');
}

if(empty($getid))
{
echo "$loadpage";
die('');
}

if($display === 'false')
{
echo "$loadpage";
die('');
}

mysql_close($con);
?><!DOCTYPE html>
<html lang="en">
<head>
<?php
$server = "localhost:3306";
$uname = "jmnetwor_secure";
$pword = "D#6ilm0re";
$con = mysql_connect();//"$server","$uname","$pword");
$findid = strip_tags($_GET['event']);
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
  
mysql_select_db("wmff", $con);

$result = mysql_query("SELECT * FROM wmffevent
WHERE id='$findid'");

while($row = mysql_fetch_array($result))
  {
  $gettitle = $row['title'];
  $posttitle = str_replace('\\','',$gettitle);
  echo "<title>$posttitle at Wimborne Minster Folk Festival 2017</title>
";
  }

mysql_close($con);
?>
<?php include("files/header.php"); ?>
</head>
<body>

<?php include("files/facebook.php"); ?>

    <script>
      $(function() {
        $(".rslides").responsiveSlides();
      });
    </script>

<?php
$con = mysql_connect();//"$server","$uname","$pword");
$findid = strip_tags($_GET['event']);
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
  
mysql_select_db("wmff", $con);

$result = mysql_query("SELECT * FROM wmffevent
WHERE id='$findid'");

while($row = mysql_fetch_array($result))
  {
  $gettitle = $row['title'];
  $posttitle = str_replace('\\','',$gettitle);
  echo "<a href=\"/\" rel=\"bookmark\"><h1>$posttitle at Wimborne Minster Folk Festival 2017</h1></a>
";
  }
mysql_close($con);
?>
<div class="navigation"><?php include("files/navigation.php"); ?></div>
<div class="content">




<?php
$con = mysql_connect();//"$server","$uname","$pword");
$findid = strip_tags($_GET['event']);
$user = $_COOKIE['jmnuser'];
$loadpage = "<html>
<head>
<script type=\"text/javascript\">
<!--
function delayer(){
    window.location = \"/\"
}
//-->
</script>
</head>
<body onLoad=\"setTimeout('delayer()', 0000)\">
</body>
</html>
";
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("wmff", $con);

$eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$findid' AND display='true'");

while($row = mysql_fetch_array($eventresult))
  {
  $id = $row['id'];
  $title = $row['title'];
  $cat = $row['cat'];
  $app = $row['app'];
  $description = $row['description'];
  $img = $row['img'];
  $link1 = $row['link1'];
  $link2 = $row['link2'];
  $facebook = $row['facebook'];
  $twitter = $row['twitter'];
  $instagram = $row['instagram'];
  $youtube = $row['youtube'];
  $headline = $row['headline'];
  $display = $row['display'];
  $user = $row['user'];
  $user = ucwords($getuser);

  $link1 = str_replace('https://','',$link1); 
  $link1 = str_replace('http://','',$link1); 
  $link2 = str_replace('https://','',$link2); 
  $link2 = str_replace('http://','',$link2); 
  $facebook = str_replace('https://','',$facebook); 
  $facebook = str_replace('http://','',$facebook); 
  $twitter = str_replace('@','',$twitter); 
  $instagram = str_replace('@','',$instagram); 

  $title = str_replace('\\','',$title); 
  $description = str_replace('\\','',$description);
  $img = str_replace('\\','',$img);
  $user = str_replace('\'','',$user);
  $user = str_replace('\\','',$user);

      if(!empty ($link1))
      {
      $link1 = "<a href=\"http://$link1\" rel=\"tag\" target=\"_blank\" style=\"color:#FFFFFF;\">$link1</a>";
      }

      if(!empty ($link2))
      {
      $link2 = " <strong>|</strong> <a href=\"http://$link2\" rel=\"tag\" target=\"_blank\" style=\"color:#FFFFFF;\">$link2</a>";
      }

      if(!empty ($facebook))
      {
      $facebook = "<a href=\"http://$facebook\" rel=\"tag\" target=\"_blank\"><img src=\"/images/Facebook.png\" alt=\"Facebook\" style=\"margin-top:5px;\" /></a>";
      }

      if(!empty ($twitter))
      {
      $twitter = "<a href=\"http://twitter.com/$twitter\" rel=\"tag\" target=\"_blank\"><img src=\"/images/Twitter.png\" alt=\"Twitter\" style=\"margin-top:5px;\" /></a>";
      }

      if(!empty ($instagram))
      {
      $instagram = "<a href=\"http://instagram.com/$instagram\" rel=\"tag\" target=\"_blank\"><img src=\"/images/Instagram.png\" alt=\"Instagram\" style=\"margin-top:5px;\" /></a>";
      }

      if(!empty ($youtube))
      {
      $youtube = "<div id=\"flex\"><div class=\"article\" style=\"border-bottom:0;\"><div class=\"youtube\"><iframe width=\"560\" height=\"315\" src=\"https://www.youtube-nocookie.com/embed/$youtube?rel=0&amp;controls=0&amp;showinfo=0\" frameborder=\"0\" allowfullscreen></iframe></div></div></div>";
      }

      if($app === 'true')
      {
      $appa = " | Appalachian";
      }

   echo "<div class=\"biodiv\">
<img src=\"/images/$img\" alt=\"$title at Wimborne Minster Folk Festival\" class=\"bioimg\" />
<br />
$facebook $twitter $instagram
<p style=\"min-width:100%;\">$link1$link2</p>
</div>

<h2 class=\"subtitle\">$title$appa</h2>
<p style=\"margin-top:0;\">";


  if($id === $findid)
    {
    $showresult = mysql_query("SELECT * FROM wmffshow WHERE eventid='$findid' ORDER BY date ASC");

    while($row = mysql_fetch_array($showresult))
     {
     $getid = $row['eventid'];
     $date = $row['date'];
     $location = $row['location'];
     $price = $row['price'];
     $otdprice = $row['otdprice'];
     $otdlink = $row['otdlink'];

        if($price > '0.00')
          {$price = "<a href=\"/tickets\" rel=\"bookmark\"><strong>&#163;$price</strong></a> <span style=\"font-size:10px;\">Day Pass</span>";}
        if($price === '0.00')
          {$price = "Free";}
		
		if($otdlink == 'yes')
	      {
			if($otdprice > '0.00')
		    {$otdprice = " / <a href=\"/tickets\" rel=\"bookmark\"><strong>&#163;$otdprice</strong></a>  <span style=\"font-size:10px;\">Ticket</span>";}
	      }	  
	  
	    if($otdlink == 'no')
	      {
		    if($otdprice > '0.00')
		    {$otdprice = " / <strong>&#163;$otdprice</strong> <span style=\"font-size:10px;\">On The Door*</span>";
			$otdmessage = "<p style=\"max-width:50%;\"><span style=\"font-size:10px; color:#333333;\">* The price listed as '<strong>On The Door</strong>' is a single cash payment for this concert only which will be payable on the door, a ticket will <strong><u>NOT</u></strong> be issued. Entry will be subject to venue capacity and ticket holders will have priority.</span></p>";}
	      }
	  
	  	if($otdprice == '0.00')
		  {$otdprice = "";}

     if($date !== '0000-00-00 00:00:00')
       {
	date_default_timezone_set('GMT');

       $date = date('g:ia D j F', strtotime($date));
       $date = str_replace(':00','',$date); 
       $postdate = "<strong>$date</strong> at <strong>$location</strong> | $price$otdprice<br />";
       }
     echo "$postdate";
    }


echo "</p>
<p>$description</p>

<div class=\"socialbutton\">
<a href=\"https://twitter.com/share\" class=\"twitter-share-button\"{count} data-via=\"WimborneFolk\" data-hashtags=\"WimborneFolk\">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
</div>
<div class=\"socialbutton\">
<div class=\"fb-share-button\" data-type=\"button_count\"></div>
</div>

$youtube

$otdmessage";

  }


  }



if(empty($title))
{
echo "$loadpage";
die('');
}

mysql_close($con);
?>

<h2 class="mintitle"><a class="button" href="/tickets" rel="bookmark" style="color:#ffffff; font-family:SHONAR,verdana,sans-serif; font-weight:bold; border-radius:15px; -moz-border-radius:15px; padding-top:5px;">Buy Weekend/Day Passes</a></h2>

</div>
<?php include("files/footer.php"); ?>
</body>
</html>
