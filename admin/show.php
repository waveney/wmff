<?php
  include_once("int/fest.php");
  A_Check('Committee','OldAdmin');

$server = "localhost:3306";
$uname = "jmnetwor_secure";
$pword = "D#6ilm0re";
$con = mysql_connect();//"$server","$uname","$pword");
$getid = $_GET['event'];
$showid = $_GET['show'];
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

$showresult = mysql_query("SELECT * FROM wmffshow WHERE eventid='$getid' ORDER BY date ASC");

  while($row = mysql_fetch_array($showresult))
  {
  $id = $row['id'];
  $date = $row['date'];
  $location = $row['location'];
  $price = $row['price'];
  $otdprice = $row['otdprice'];
  $otdlink = $row['otdlink'];

        if($price > '0.00')
          {$price = "<a href=\"/tickets\" rel=\"bookmark\"><strong>&#163;$price</strong></a>";}
        if($price === '0.00')
          {$price = "Free";}
	  
	  if($otdlink == 'yes')
	  {
		  if($otdprice > '0.00')
		  {
			  $otdprice = " / <a href=\"/tickets\" rel=\"bookmark\"><strong>&#163;$otdprice</strong></a> OTD";
		  }
	  }	  
	  
	  if($otdlink == 'no')
	  {
		  if($otdprice > '0.00')
		  {
			  $otdprice = " / <strong>&#163;$otdprice</strong> OTD";
		  }
	  }
	  
	  	if($otdprice == '0.00')
		  {
			  $otdprice = "";
		  }
	  

     if($date !== '0000-00-00 00:00:00')
       {
	date_default_timezone_set('GMT');

       $date = date('g:ia l j F', strtotime($date));
       $date = str_replace(':00','',$date); 
       $postdate = "<strong>$date</strong> at <strong>$location</strong> | $price$otdprice | <a href=\"?event=$getid&show=$id#event\" rel=\"bookmark\">Edit</a><br /><br />";
       }
  echo "<tr><td style=\"background-color:inherit;\">$postdate</td></tr>";

  }

    if(!empty($showid))
      {
      $showeditresult = mysql_query("SELECT * FROM wmffshow WHERE id='$showid'");
      while($row = mysql_fetch_array($showeditresult))
      {
      $findeventid = $row['eventid'];
      $date = $row['date'];
      $location = $row['location'];
      $price = $row['price'];
      $otdprice = $row['otdprice'];
      $otdlink = $row['otdlink'];
		  
		  if($otdlink === 'no')
		  {
			 $otdlink = "<input type=\"checkbox\" name=\"editotdlink\" value=\"yes\" />";
		  }
		  
		  if($otdlink === 'yes')
		  {
			 $otdlink = "<input type=\"checkbox\" name=\"editotdlink\" value=\"yes\" checked />";
		  }

echo "<form method=\"post\" action=\"?editshow=confirm&event=$getid#event\"><tr><td style=\"background-color:inherit;\"><input type=\"text\" name=\"showid\" value=\"$showid\" hidden /><input type=\"text\" name=\"editdate\" size=\"30\" placeholder=\"Date: YYYY-MM-DD HH:MM:SS\" value=\"$date\"/></td></tr>
<tr><td style=\"background-color:inherit;\"><input type=\"text\" name=\"editlocation\" size=\"30\" placeholder=\"Location\" value=\"$location\" /></td></tr>
<tr><td style=\"background-color:inherit;\"><input type=\"text\" name=\"editprice\" size=\"30\" placeholder=\"Price: 0.00\" value=\"$price\" /></td></tr>
<tr><td style=\"background-color:inherit;\"><input type=\"text\" name=\"editotdprice\" size=\"30\" placeholder=\"OTD Price: 0.00\" value=\"$otdprice\" /> Link:$otdlink</td></tr>
<tr><td style=\"background-color:inherit;\"><input type=\"submit\" value=\"Edit Show Details\" /></td></tr></form>";
      }
      }


echo "<form method=\"post\" action=\"?addshow=confirm&event=$getid#event\">
<tr><td style=\"background-color:inherit;\"><input type=\"text\" name=\"eventid\" value=\"$eventid\" hidden /><h2 class=\"mintitle\">Add Show</h2></td></tr>
<tr><td style=\"background-color:inherit;\"><input type=\"text\" name=\"date\" size=\"30\" placeholder=\"Date: YYYY-MM-DD HH:MM:SS\" /></td></tr>
<tr><td style=\"background-color:inherit;\"><input type=\"text\" name=\"location\" size=\"30\" placeholder=\"Location\" /></td></tr>
<tr><td style=\"background-color:inherit;\"><input type=\"text\" name=\"price\" size=\"30\" placeholder=\"Price: 0.00\" /></td></tr>
<tr><td style=\"background-color:inherit;\"><input type=\"text\" name=\"otdprice\" size=\"30\" placeholder=\"OTD Price: 0.00\" /> Link:<input type=\"checkbox\" name=\"otdlink\" value=\"yes\" /></td></tr>
<tr><td style=\"background-color:inherit;\"><input type=\"submit\" value=\"Add Show Details\" /></td></tr>
<tr><td style=\"background-color:inherit;\"><a href=\"/admin/#event\" rel=\"bookmark\">Return to Event Select</a></td></tr></form>";

  }

}

mysql_close($con);
?>
