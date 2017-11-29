    <link rel="SHORTCUT ICON" href="/images/icon.ico" />
    <meta name="description" content="Wimborne's annual folk festival takes place in the historic market town of Wimborne Minster in Dorset on the weekend of 9, 10, 11 June 2017.">
    <meta name="keywords" content="wimborne, minster, folk, festival, folk festival, dorset, folkie, fringe, paddock, morris, dance, side, music, concerts, camping, parking, trade, trading, stewards, volunteer, tickets, line up">
    <meta name="copyright" content="Copyright &copy; Wimborne Minster Folk Festival <?php date_default_timezone_set('GMT'); echo date('Y'); ?>"> 
    <meta name="Charset" content="US-ASCII">
    <meta name="Distribution" content="Global">
    <meta name="Rating" content="General">
    <meta name="Robots" content="INDEX,FOLLOW">
    <meta name="Revisit-after" content="2 Hours">
    <meta name="viewport" content="width=device-width,initial-scale=1">

<?php
$server = "localhost:3306";
$uname = "wmffdb";
$pword = "3bQp1b!6";
$con = mysql_connect();//"$server","$uname","$pword");
$eventid = strip_tags($_GET['event']);
$newsid = strip_tags($_GET['id']);
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
  
mysql_select_db("wmff", $con);

$result = mysql_query("SELECT * FROM wmffevent
WHERE id='$eventid'");

while($row = mysql_fetch_array($result))
  {
  $img = $row['img'];
  }

if(empty($img))
{
$result2 = mysql_query("SELECT * FROM wmff
WHERE id='$newsid'");

while($row = mysql_fetch_array($result2))
  {
  $img = $row['image'];
  }
}


  if(!empty($img))
    {
    echo "<meta property=\"og:image\" content=\"/images/$img\">
    <meta property=\"og:image:type\" content=\"image/jpg\">";
    }

  if(empty($img))
    {
    echo "";
    }

mysql_close($con);
?>

    <link href="/files/style.css" type="text/css" rel="stylesheet" />
    <link href="/files/dropdown.css" type="text/css" rel="stylesheet" />
    <link href="/files/responsiveslides.css" type="text/css" rel="stylesheet" />
    <link href="/files/themes.css" type="text/css" rel="stylesheet" />
    <script src="/js/jquery-1.7.2.min.js"></script>
    <script src="/js/lightbox.js"></script>
    <link href="/css/lightbox.css" rel="stylesheet" />
        <script type="text/javascript">
    function SelectAll(id)
    {
    document.getElementById(id).focus();
    document.getElementById(id).select();
    }
    </script>
    <script language="javascript" type="text/javascript">
    function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.value = limitNum - limitField.value.length;
	}
    }
    </script>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="/files/responsiveslides.min.js"></script>
    <script>
      $(function() {
      $("#slider1").responsiveSlides({
  auto: true,             // Boolean: Animate automatically, true or false
  speed: 500,            // Integer: Speed of the transition, in milliseconds
  timeout: 8000,          // Integer: Time between slide transitions, in milliseconds
  pager: false,           // Boolean: Show pager, true or false
  nav: true,             // Boolean: Show navigation, true or false
  random: false,          // Boolean: Randomize the order of the slides, true or false
  pause: false,           // Boolean: Pause on hover, true or false
  pauseControls: false,    // Boolean: Pause when hovering controls, true or false
  prevText: "",   // String: Text for the "previous" button
  nextText: "",       // String: Text for the "next" button
  maxwidth: "",           // Integer: Max-width of the slideshow, in pixels
  navContainer: "",       // Selector: Where controls should be appended to, default is after the 'ul'
  manualControls: "",     // Selector: Declare custom pager navigation
  namespace: "rslides",   // String: Change the default namespace used
  before: function(){},   // Function: Before callback
  after: function(){}     // Function: After callback
      });
      });
    </script>
    <script>
      $(function() {
      $("#slider2").responsiveSlides({
  auto: true,             // Boolean: Animate automatically, true or false
  speed: 500,            // Integer: Speed of the transition, in milliseconds
  timeout: 8000,          // Integer: Time between slide transitions, in milliseconds
  pager: false,           // Boolean: Show pager, true or false
  nav: false,             // Boolean: Show navigation, true or false
  random: false,          // Boolean: Randomize the order of the slides, true or false
  pause: false,           // Boolean: Pause on hover, true or false
  pauseControls: false,    // Boolean: Pause when hovering controls, true or false
  prevText: "Previous",   // String: Text for the "previous" button
  nextText: "Next",       // String: Text for the "next" button
  maxwidth: "150",           // Integer: Max-width of the slideshow, in pixels
  navContainer: "",       // Selector: Where controls should be appended to, default is after the 'ul'
  manualControls: "",     // Selector: Declare custom pager navigation
  namespace: "rslides",   // String: Change the default namespace used
  before: function(){},   // Function: Before callback
  after: function(){}     // Function: After callback
      });
      });
    </script>
