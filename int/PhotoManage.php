<?php
  include_once("fest.php");
  include_once("DanceLib.php");
  include_once("MusicLib.php");
  include_once("TradeLib.php");
  include_once("ProgLib.php");

if (isset($_FILES['croppedImage'])) {
  $Pcat = $_POST['PCAT'];
  $Who = $_POST['WHO'];
  $PhotoBefore = $_POST['PhotoURL'];

  switch ($Pcat) {
    case 0: // Sides
    case 1: // Acts
    case 2: // Others
      $Data = Get_Side($Who);
      $Field = 'Photo';
      $FinalLoc = "images/Sides/" . $Who;
      $Put_Data = 'Put_Side';
      break;
    case 3: // Trader
      $Data = Get_Trader($Who);
      $Field = 'Photo';
      $FinalLoc = "images/Traders/" . $Who;
      $Put_Data = 'Put_Trader';
      break;
    case 4: // Sponsor
      $Data = Get_Sponsor($Who);
      $Field = 'Image';
      $FinalLoc = "images/Sponsors/" . $Who;
      $Put_Data = 'Put_Sponsor';
      break;
    case 5: // Venue
      $Data = Get_Venue($Who);
      $Field = 'Image';
      $FinalLoc = "images/Venues/" . $Who;
      $Put_Data = 'Put_Venue';
    }

  $Cursfx = pathinfo($PhotoBefore,PATHINFO_EXTENSION );
  $Loc = "$FinalLoc.$Cursfx";
  $dir = dirname($Loc);
  if (!file_exists($dir)) mkdir($dir,0777,true);
  if (move_uploaded_file($_FILES["croppedImage"]["tmp_name"], $Loc)) {
    $Data[$Field] = $Loc;
    $Put_Data($Data);
    echo "Success";
  } else {
    echo "Failure to move file";
  }
} else {

  A_Check('Staff','Photos');

  dostaffhead("Manage Photos",'/js/cropper.js',"/css/cropper.css");


/* Edit images for Sides, Traders, Sponsors
   If not stored appropriately, store in right place afterwards
   If was in store, and there is NOT an .orig file save original as .orig

   Allow croping to square or landscape, Zoom

   Will use cropit jquery plugin to do most of the manipulation

   Select what, and format wanted

   Edit

   Save
*/

  $Shapes = array('Landscape','Square','Portrait','Banner','Free Form');
  $aspect = array('4/3','1/1','3/4','7/2','NaN');
  $Shape = 0;
  if (isset($_POST['SHAPE'])) $Shape = $_POST['SHAPE'];
  $PhotoCats = array('Sides','Acts','Others','Traders','Sponsors','Venues');

  $Lists = array(
	'Sides'=> Select_Come(),
	'Acts'=>Select_Act_Come(),
	'Others'=>Select_Other_Come(),
	'Traders'=>Get_Traders_Coming(),
	'Sponsors'=>Get_Sponsor_Names(),
	'Venues'=>Get_Venues(0),
	);


?>
<script language=Javascript defer>
  var CC;
  $(document).ready(function() {
    CC = ($('#image').cropper({ 
<?php echo "aspectRatio: " . $aspect[$Shape] . ',' ?>
        viewMode:1,
        autoCropArea:1,
    }));

    document.getElementById('crop_button').addEventListener('click', function(){

      var DD = $('#image').cropper('getCroppedCanvas');

      DD.toBlob(function (blob) {
	var form = document.getElementById('cropform');
        var formData = new FormData(form);

        var fred = formData.append('croppedImage', blob,'croppedImage');

	debugger;
        $.ajax('/int/PhotoManage.php', {
          method: "POST",
          data: formData,
          processData: false,
          contentType: false, 
          success: function (resp) { console.log(resp); document.getElementById('Feedback').innerHTML = resp; },
          error: function (resp) { console.log(resp); document.getElementById('Feedback').innerHTML = resp; },
          });
        });
      });
    })

</script>
<?php
  
  function Select_Photos() {
    global $Shapes,$Shape,$PhotoCats,$Lists;
    echo "<h2>Select Photo to modify</h2><p>\n";
    echo "<form method=post action=PhotoManage.php>";
    echo fm_radio("Target shape",$Shapes,$_POST,'SHAPE','',0) . "<p>";
    echo fm_radio("Photo For",$PhotoCats,$_POST,'PCAT','onclick=PCatSel(event)',0);
    $mouse = 0;
    if (isset($_POST['PCAT'])) $mouse = $_POST['PCAT'];
    $i=0;
    foreach($Lists as $cat=>$dog) {
      echo "<span id=MPC_$i " . ($cat == $PhotoCats[$mouse]?'':'hidden') . "> : " . fm_select($dog,$_POST,"WHO$i") . "</span>";
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
      $FinalLoc = "images/Sides/" . $Who;
      $ArcLoc = "ArchiveImages/Sides/" . $Who;
      break;
    case 3: // Trader
      $Trad = Get_Trader($Who);
      $Name = $Trad['Name'];
      $PhotoURL = $Trad['Photo'];
      $FinalLoc = "images/Traders/" . $Who;
      $ArcLoc = "ArchiveImages/Traders/" . $Who;
      break;
    case 4: // Sponsor
      $Spon = Get_Sponsor($Who);
      $Name = $Spon['Name'];
      $PhotoURL = $Spon['Image'];
      $FinalLoc = "images/Sponsors/" . $Who;
      $ArcLoc = "ArchiveImages/Sponsors/" . $Who;
      break;
    case 5: // Venue
      $Ven = Get_Venue($Who);
      $Name = $Ven['Name'];
      $PhotoURL = $Ven['Image'];
      $FinalLoc = "images/Venues/" . $Who;
      $ArcLoc = "ArchiveImages/Venues/" . $Who;
      break;
    }

    $suffix = strtolower(pathinfo($PhotoURL,PATHINFO_EXTENSION));
    $FinalLoc .= ".$suffix";
    $ExtLoc = "/" . $FinalLoc;
    
    if ($PhotoURL) {
      if ($PhotoURL != $ExtLoc) {
	if (preg_match('/^\/(.*)/',$PhotoURL,$mtch)) {
	  $img = file_get_contents($mtch[1]);
	} else {
          $img = file_get_contents($PhotoURL);
	};

        if ($img) {
          $ArcD = dirname($ArcLoc);
          $ArcLoc .= ".$suffix";
          if (!file_exists($ArcD)) mkdir($ArcD,0777,true);
  	  if (!file_exists($ArcLoc)) {
	    file_put_contents($ArcLoc,$img);
	  }
          $done = file_put_contents("../$FinalLoc",$img);
          $PhotoURL = $ExtLoc;
        } else {
          $PhotoURL = "1";  
        }
      }
    }

    echo "<h2>Image to Manage</h2>\n";
    echo "<form id=cropform method=post action=PhotoManage.php enctype='multipart/form-data' >";
    echo fm_hidden('PCAT',$Pcat) . fm_hidden("WHO",$Who);
    echo "Type: " . $PhotoCats[$Pcat] . "<br>";
    echo "For: $Name<br>";
    echo "Shape: " . $Shapes[$Shape] . "<p>";
    if ($PhotoURL) {
      if ($PhotoURL != "1") {
	echo fm_hidden("PhotoURL",$PhotoURL);
        echo "<div><img src=$PhotoURL id=image style='max-height:500; max-width:600;'><p></div>";
	echo "<center><div id=crop_button value=Crop>Crop</div><div id=Feedback></div></center><p>\n";
	echo "<div class=floatright><input type=submit class=smallsubmit value='Show Original'></div>\n";
      } else {
        echo "The Photo URL can't be read<P>";
      }
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
}
/* TODO
  Make crop update image shown
  get original - conditional
  upload
d  rescale for large
d  Venues
d  make it remember pcat/who correctly 
d  mkdir


*/
?>
