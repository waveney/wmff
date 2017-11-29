<!DOCTYPE html>
<html lang="en">
<head>
<title>Dance Schedule 2016</title>
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

<a href="/" rel="bookmark"><h1>Dance Schedule 2016</h1></a>
<div class="navigation"><?php include("files/navigation.php"); ?></div>
<div class="content">

<h2 class="maintitle">Dance Schedule 2016</h2>
<img src="/images/Mobile-GIF-V3.gif" style="float:left; margin-right:15px; margin-bottom:10px; max-width:100px;" />

<p>The full dance programme schedule in an easy to use online format, which will also work with mobile devices on the festival weekend.</p>

<p>Rotate your Mobile Device for optimum viewing.</p>

<table cellspacing="5" cellpadding="5" style="background-color:#FFFFFF; border-color:#FFFFFF;">
<tr>
<th colspan="9" style="background-color:#59B404;">Saturday 11 June 2016</th>
</tr>
<tr>
<td rowspan="2" style="background-color:#FBFD9B; text-align:center;"><strong>Time</strong></td>
<td colspan="3" style="background-color:#CAFD9B; text-align:center;"><strong>Appalachian</strong></td>
<td colspan="5" style="background-color:#9BCAFD; text-align:center;"><strong>Morris</strong></td>
</tr>
<tr>
<td style="background-color:#CAFD9B; text-align:center;"><strong>Cornmarket</strong></td>
<td style="background-color:#CAFD9B; text-align:center;"><strong>Model Town</strong></td>
<td style="background-color:#CAFD9B; text-align:center;"><strong>British Legion</strong></td>
<td style="background-color:#9BCAFD; text-align:center;"><strong>The Square</strong></td>
<td style="background-color:#9BCAFD; text-align:center;"><strong>Cook Row</strong></td>
<td style="background-color:#9BCAFD; text-align:center;"><strong>Salamander</strong></td>
<td style="background-color:#9BCAFD; text-align:center;"><strong>Green Man</strong></td>
<td style="background-color:#9BCAFD; text-align:center;"><strong>East Borough</strong></td>
</tr>

<?php
$server = "localhost:3306";
$uname = "jmnetwor_secure";
$pword = "D#6ilm0re";
$con = mysql_connect();//"$server","$uname","$pword");
if (!$con)
{die('Could not connect: ' . mysql_error());}
mysql_select_db("wmff", $con);

$cornmarket = "Cornmarket";
$modeltown = "Model Town";
$legion = "British Legion";
$square = "The Square";
$cookrow = "Cook Row";
$salamander = "Salamander";
$greenman = "Green Man";
$eastborough = "East Borough";


// REPEAT HERE

$showtime = "2016-06-11 10:00:00";
date_default_timezone_set('GMT');

$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-11 10:30:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-11 11:00:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-11 11:30:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-11 12:00:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-11 12:30:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-11 13:00:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-11 13:30:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-11 14:00:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-11 14:30:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-11 15:00:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-11 15:30:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-11 16:00:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-11 16:30:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE

mysql_close($con);
?>

</table>


<table cellspacing="5" cellpadding="5" style="background-color:#FFFFFF; border-color:#FFFFFF;">
<tr>
<th colspan="9" style="background-color:#50A3FB;">Sunday 12 June 2016</th>
</tr>
<tr>
<td rowspan="2" style="background-color:#FBFD9B; text-align:center;"><strong>Time</strong></td>
<td colspan="3" style="background-color:#CAFD9B; text-align:center;"><strong>Appalachian</strong></td>
<td colspan="5" style="background-color:#9BCAFD; text-align:center;"><strong>Morris</strong></td>
</tr>
<tr>
<td style="background-color:#CAFD9B; text-align:center;"><strong>Cornmarket</strong></td>
<td style="background-color:#CAFD9B; text-align:center;"><strong>Model Town</strong></td>
<td style="background-color:#CAFD9B; text-align:center;"><strong>British Legion</strong></td>
<td style="background-color:#9BCAFD; text-align:center;"><strong>The Square</strong></td>
<td style="background-color:#9BCAFD; text-align:center;"><strong>Cook Row</strong></td>
<td style="background-color:#9BCAFD; text-align:center;"><strong>Salamander</strong></td>
<td style="background-color:#9BCAFD; text-align:center;"><strong>Green Man</strong></td>
<td style="background-color:#9BCAFD; text-align:center;"><strong>East Borough</strong></td>
</tr>

<?php
$server = "localhost:3306";
$uname = "jmnetwor_secure";
$pword = "D#6ilm0re";
$con = mysql_connect();//"$server","$uname","$pword");
if (!$con)
{die('Could not connect: ' . mysql_error());}
mysql_select_db("wmff", $con);

$cornmarket = "Cornmarket";
$modeltown = "Model Town";
$legion = "British Legion";
$square = "The Square";
$cookrow = "Cook Row";
$salamander = "Salamander";
$greenman = "Green Man";
$eastborough = "East Borough";


// REPEAT HERE

$showtime = "2016-06-12 10:00:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-12 10:30:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-12 11:00:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-12 11:30:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-12 12:00:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-12 12:30:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-12 13:00:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-12 13:30:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-12 14:00:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-12 14:30:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-12 15:00:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-12 15:30:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-12 16:00:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE
// REPEAT HERE

$showtime = "2016-06-12 16:30:00";
$time = date('g:ia', strtotime($showtime));
$time = str_replace(':00','',$time); 
echo "<tr><td style=\"background-color:#FBFD9B; text-align:right;\">$time</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cornmarket%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$modeltown%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#CAFD9B;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$legion%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$square%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$cookrow%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$salamander%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$greenman%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td><td style=\"background-color:#9BCAFD;\">";
$showresult = mysql_query("SELECT * FROM wmffshow WHERE date LIKE '$showtime' AND location LIKE '%$eastborough%' ORDER BY date ASC");
while($row = mysql_fetch_array($showresult))
  {
  $getid = $row['eventid'];
  if($datecode === $today)
     {
     $eventresult = mysql_query("SELECT * FROM wmffevent WHERE id='$getid' AND cat='dance' AND display='true'");
      while($row = mysql_fetch_array($eventresult))
        {
        $title = $row['title'];
        $title = str_replace('\\','',$title); 
        echo "<span style=\"font-size:10px;\">&bull;</span> <a href=\"/?event=$getid\" rel=\"bookmark\">$title</a><br />";
        }
     }
  }
echo "</td></tr>";

// STOP HERE

mysql_close($con);
?>

</table>

<p>Information correct at time of publishing; line-up and pricing subject to change. See website or information points (Allendale Centre or The Square) for updates.</p>

</div>
<?php include("files/footer.php"); ?>
</body>
</html>
