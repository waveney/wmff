<?php
  include_once("int/fest.php");
  A_Check('Committee','OldAdmin');

if ($_FILES['file']['size'] > 604800 )
{
    include ("files/top.php");
    echo "<h2 class=\"maintitle\">File Too Large</h2><p>Please reduce the size of the file to under 600KB.</p>";
    include ("files/bottom.php");
    die('');
}


$blacklist = array(".php", ".phtml", ".php3", ".php4", ".js", ".shtml", ".pl" ,".py");
foreach ($blacklist as $file)
{
if(preg_match("/$file\$/i", $_FILES['userfile']['name']))
{
    include ("files/top.php");
    echo "<h2 class=\"maintitle\">Invalid File Type</h2><p>The file you selected doesn't look like an image to us! Please only use: .jpg, .png and .gif</p>";
    include ("files/bottom.php");
exit;
}
}


  {
  if ($_FILES["file"]["error"] > 0)
    {
    include ("files/top.php");
    echo "<h2 class=\"maintitle\">Failed Upload</h2><p>Return Code: " . $_FILES["file"]["error"] . "</p>";
    include ("files/bottom.php");
    die('');
    }
  else
    {

    if (file_exists("/images/" . $_FILES["file"]["name"]))
      {
      include ("files/top.php");
      echo "<h2 class=\"maintitle\">Failed Upload</h2><p>" . $_FILES["file"]["name"] . " already exists!</p>";
      include ("files/bottom.php");
      die('');
      }

    else
      {

      move_uploaded_file($_FILES["file"]["tmp_name"],
      "files/tech/" . $_FILES["file"]["name"]);
      $loadpage = "<html>
      <head>
      <script type=\"text/javascript\">
      <!--
      function delayer(){
          window.location = \"?tech=" . $_FILES["file"]["name"] . "\"
      }
      //-->
      </script>
      </head>
      <body onLoad=\"setTimeout('delayer()', 0000)\">
      </body>
      </html>
      ";
      echo "$loadpage";
      }
    }
  }
$filename = $_FILES["file"]["name"];
?>
