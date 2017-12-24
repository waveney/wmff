<?php
  include_once("fest.php");

  A_Check('Staff','Photos');

  dostaffhead("Manage Photos",'<script src="/js/cropper.js"></script><link  href="/css/cropper.css" rel="stylesheet">');

/* Edit images for Sides, Traders, Sponsors
   If not stored appropriately, store in right place afterwards
   If was in store, and there is NOT an .orig file save original as .orig

   Allow croping to square or landscape, Zoom

   Will use cropit jquery plugin to do most of the manipulation

   Select what, and format wanted

   Edit

   Save
*/
  include_once("DanceLib.php");
  include_once("MusicLib.php");
  include_once("TradeLib.php");

  $Shapes = array('Landscape','Square','Portrait','Banner','Free Form');
  $aspect = array('4/3','1/1','3/4','7/2','NaN');
  $Shape = 0;
  if (isset($_POST['SHAPE'])) $Shape = $_POST['SHAPE'];
  $PhotoCats = array('Sides','Acts','Others','Traders','Sponsors');

  $Lists = array('Sides'=> Select_Come(),'Acts'=>Select_Act_Come(),'Others'=>Select_Other_Come(),'Traders'=>Get_Traders_Coming(),'Sponsors'=>Get_Sponsor_Names());

/*
  echo "<script language=Javascript>jQuery(function(\$) {";
  echo "\$('#PhotoTarget').Jcrop({";
  echo "aspectRatio: " . $aspect[$Shape];
  echo "});});</script>\n";

<?php
  echo "aspectRatio: " . $aspect[$Shape];
?>
*/

?>
<script language=Javascript>
  $(function () {
    $('#image').cropper({
<?php
  echo "aspectRatio: " . $aspect[$Shape];
?>
    });
  });
</script>
<?php
  
  function Select_Photos() {
    global $Shapes,$Shape,$PhotoCats,$Lists;
    echo "<h2>Select Photo to modify</h2><p>\n";
    echo "<form method=post action=PhotoManage.php>";
    echo fm_radio("Target shape",$Shapes,$_POST,'SHAPE','',0) . "<p>";
    echo fm_radio("Photo For",$PhotoCats,$_POST,'PCAT','onclick=PCatSel(event)',0);
    $i=0;
    foreach($Lists as $cat=>$dog) {
      echo "<span id=MPC_$i " . ($cat == 'Sides'?'':'hidden') . "> : " . fm_select($dog,$_POST,"WHO$i") . "</span>";
      $i++;
    }
    echo "<input type=submit name=Edit value=Edit><p>\n";
    echo "</form>\n";
  }

  function Edit_Photo() {
    global $Shapes,$Shape, $Lists,$PhotoCats;
    $Pcat = $_POST['PCAT'];
    if (!isset($_POST["WHO$Pcat"])) return;
    $Who = $_POST["WHO$Pcat"];
    
    switch ($Pcat) {
    case 0: // Sides
    case 1: // Acts
    case 2: // Others
      $Side = Get_Side($Who);
      $Name = $Side['Name'];
      $PhotoURL = $Side['Photo'];
      break;
    case 3: // Trader
      $Trad = Get_Trader($Who);
      $Name = $Trad['Name'];
      $PhotoURL = $Trad['Photo'];
      break;
    case 4: // Sponsor
      $Spon = Get_Sponsor($Who);
      $Name = $Spon['Name'];
      $PhotoURL = $Spon['Image'];
      break;
    }
    
    echo "<h2>Image to Manage</h2>\n";
    echo "<form method=post action=PhotoManage.php enctype='multipart/form-data' >";
    echo fm_hidden('PCAT',$Pcat) . fm_hidden("WHO$Pcat",$Who);
    echo "Type: " . $PhotoCats[$Pcat] . "<br>";
    echo "For: $Name<br>";
    echo "Shape: " . $Shapes[$Shape] . "<p>";
    if ($PhotoURL) {
      echo "<div><img src=$PhotoURL id=image><p></div>";
    } else {
      echo "No Image currently<p>";
    }

    echo "Select Photo file to upload:";
    echo "<input type=file $ADDALL name=PhotoForm id=PhotoForm onchange=document.getElementById('PhotoButton').click()>";
    echo "<input hidden type=submit name=Action value=Upload id=PhotoButton>";
    

    

  }
  
  if (isset($_POST['Edit'])) {
    Edit_Photo();
  } 

  Select_Photos();

  dotail();
?>
