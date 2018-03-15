<?php
  include_once("fest.php");
  include_once("DanceLib.php");
  include_once("MusicLib.php");
  include_once("TradeLib.php");
  include_once("ProgLib.php");

function ImgData() {
  $Pcat = $_POST['PCAT'];
  $Who = (isset($_POST['WHO']) && strlen($_POST['WHO']) ? $_POST['WHO'] : $_POST["WHO$Pcat"]);

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
  $ArcLoc = $FinalLoc;
  $ArcLoc = preg_replace('/images/','ArchiveImages',$ArcLoc);
  $curfil = $Data[$Field];
  $curfil = preg_replace('/\?.*/','',$curfil);
  $suffix = strtolower(pathinfo($curfil,PATHINFO_EXTENSION));
  return array(
	'Pcat'=>$Pcat,
	'Who'=>$Who,
	'Data'=>$Data,
	'Field'=>$Field,
	'FinalLoc'=>$FinalLoc,
	'ArcLoc'=>$ArcLoc,
	'Put'=>$Put_Data,
	'Suf'=>$suffix
	);
}

function Upload_Image() {
  include_once("ImageLib.php"); 
  $dat = &ImgData();
  if (file_exists($dat['FinalLoc'] . "." . $dat['Suf'])) {
    $FinalLoc = $dat['FinalLoc'];
    $ArcLoc = $dat['ArcLoc'];
    Archive_Stack($ArcLoc . "." . $dat['Suf']);
    copy($FinalLoc . "." . $dat['Suf'],$ArcLoc . "." . $dat['Suf']); 
  }

  $target_dir = dirname($dat['FinalLoc']);
  if (!file_exists($target_dir)) mkdir($target_dir,0775,true);
  $suffix = strtolower(pathinfo($_FILES["PhotoForm"]["name"],PATHINFO_EXTENSION));
  $target_file = $dat['FinalLoc'] . ".$suffix";
  $uploadOk = 1;
  // Check if image file is a actual image or fake image
  $check = getimagesize($_FILES["PhotoForm"]["tmp_name"]);
  if ($check == false) {
    return "<div class=Err>File is not an image</div>";
    $uploadOk = 0;
  } else {
    if ($check[0] > 800 || $check[1] > 536) { // Need to resize
      $move = Image_Convert($_FILES["PhotoForm"]["tmp_name"],800,536, $target_file);
    } else {
      $move = move_uploaded_file($_FILES["PhotoForm"]["tmp_name"], $target_file);
    }

    if ($move) {
      $stuff = getimagesize($target_file);
      if ($stuff) {
        $dat['Data']['ImageWidth'] = $stuff[0];
        $dat['Data']['ImageHeight'] = $stuff[1];
      } 
      $pos = &$dat['Data'][$dat['Field']];
      if (isset($pos) && $pos == ("/" . $target_file)) {
        $pos = $_POST[$dat['Field']] = "/" . $target_file . "?" . rand();
        $dat['Put']($dat['Data']);
        return "The image has been replaced by ". basename( $_FILES["PhotoForm"]["name"]) ;
      } else {
        $pos = $_POST[$dat['Field']] = "/" . $target_file . "?" . rand();
        if ($dat['Put']($dat['Data'])) {
          return "The file ". basename( $_FILES["PhotoForm"]["name"]). " has been uploaded.";
        } else {
          return "<div class=Err>File uploaded but database did not update... " . $db->error . "</div>";
        }
      }
    } else {
      return "<div class=Err>Sorry, there was an error uploading your file.</div>";
    }
  }
  return 0;
}

if (isset($_FILES['croppedImage'])) {
  $dat = &ImgData();
  $PhotoBefore = $_POST['PhotoURL'];

  $Cursfx = pathinfo($PhotoBefore,PATHINFO_EXTENSION );
  $Loc = $dat['FinalLoc'] . ".$Cursfx";
  $dir = dirname($Loc);
  if (!file_exists($dir)) mkdir($dir,0777,true);
  if (move_uploaded_file($_FILES["croppedImage"]["tmp_name"], $Loc)) {
    $dat['Data'][$dat['Field']] = $Loc . "?" . rand();
    $dat['Put']($dat['Data']);
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
	'Traders'=>Get_Traders_Coming(0),
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

        $.ajax('/int/PhotoManage.php', {
          method: "POST",
          data: formData,
          processData: false,
          contentType: false, 
          success: function (resp) { 
debugger;
	    //console.log(resp); 
	    //document.getElementById('Feedback').innerHTML = resp; 
	    var src = $('#image').attr('src');
	    src += '?' + Date.now();
	    $('#croptool').hide();
	    $('#cropresult').html('<img src=' + src + '><br><h2>Image cropped and saved</h2>');
	    var finalloc = $('#FinalLoc').html();
	    $('#NewImage').html(finalloc);
	    },
          error: function (resp) { console.log(resp); document.getElementById('Feedback').innerHTML = resp; },
          });
        });
      });
    })

  $(document).ready(function() {
    $(window).keydown(function(event){
      if(event.keyCode == 13) {
        event.preventDefault();
        return false;
      }
    });
  });
</script>
<?php

  function Archive_Stack($loc) {
//echo "Arc Stack called $loc<br>";
    if (!file_exists($loc)) return;
    $hist = 1;
    while (file_exists("$loc.$hist")) $hist++;
    rename($loc,"$loc.$hist");
//echo "Arc $loc renamed $loc.$hist<br>";
  }
  

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

  function Edit_Photo($type='Current') {
    global $Shapes,$Shape, $Lists,$PhotoCats;
//var_dump($_POST);
    $dat = &ImgData();

//var_dump($dat); echo "<p>";
    $Name = $dat['Data']['SName'];
    $PhotoURL = $dat['Data'][$dat['Field']];
    $FinalLoc = $dat['FinalLoc'];
    $ArcLoc = $dat['ArcLoc'];

    $suffix = $dat['Suf'];
    $FinalLoc .= ".$suffix";
    $ExtLoc = "/" . $FinalLoc;
//var_dump($Name,$PhotoURL,$ArcLoc,$FinalLoc,$suffix);
 
    if ($type == 'Current') {
      if ($PhotoURL) {
        $ArcD = dirname($ArcLoc);
        if (!file_exists($ArcD)) mkdir($ArcD,0777,true);
  
	if (preg_match('/^\/(.*)/',$PhotoURL,$mtch)) {
	  $img = file_get_contents($mtch[1]);
	} else {
          $img = file_get_contents($PhotoURL);
	};
  
        if ($img) {
  	  if (preg_match('/https?:\/\//',$PhotoURL)) { // if external always Archive
	    Archive_Stack("$ArcLoc.$suffix");
	    file_put_contents("$ArcLoc.$suffix",$img);
	  } else if (!file_exists("$ArcLoc.$suffix")) { file_put_contents("$ArcLoc.$suffix",$img); };
          if ($PhotoURL != $ExtLoc) {
	    $done = file_put_contents("../$FinalLoc",$img);
            $PhotoURL = $ExtLoc;
	  }
        } else {
          $PhotoURL = "1";  
        }
      }
    }

    echo "<h2>Image to Manage</h2>\n";
    echo "<form id=cropform method=post action=PhotoManage.php enctype='multipart/form-data' >";
    echo fm_hidden('PCAT',$Pcat) . fm_hidden("WHO",$Who);
    echo "Type: " . $PhotoCats[$dat['Pcat']] . "<br>";
    echo "For: $Name<br>";
    echo "Shape: " . $Shapes[$Shape] . "<p>";
    echo fm_hidden('FinalLoc',$FinalLoc);
    if ($PhotoURL) {
      if ($PhotoURL != "1") {
	echo "<div id=croptool>";
	switch ($type) {
	case 'Current':
	  echo fm_hidden("PhotoURL",$PhotoURL);
          echo "<div><img src=$PhotoURL id=image style='max-height:500; max-width:600;'><p></div>";
	  echo "<div align=center><div id=crop_button value=Crop class=FakeButton>Crop and Save</div><div id=Feedback></div></div><p>\n";
	  if (file_exists("$ArcLoc.$suffix")) echo "<div class=floatright id=ShowO><input type=submit class=smallsubmit name=Original value='Show Original'></div>\n";
	  break;
	case 'Original':
	  echo fm_hidden("PhotoURL",$PhotoURL);
          echo "<div><img src='/int/$ArcLoc.$suffix' id=image style='max-height:500; max-width:600;'><p></div>";
	  echo "<div align=center><div id=crop_button value=Crop class=FakeButton>Crop and Save overwriting current</div><div id=Feedback></div></div><p>\n";
	  echo "<div class=floatright id=ShowC><input type=submit class=smallsubmit name=Current value='Show Current'></div>\n";
	  break;
	}
	echo "</div><div id=cropresult></div>";
      } else {
        echo "The Photo URL can't be read<P>";
      }
    } else {
      echo "No Image currently<p>";
    }

    echo "Select Photo file to upload:";
    echo "<input type=file name=PhotoForm id=PhotoForm onchange=document.getElementById('PhotoButton').click()>";
    echo "<input hidden type=submit name=Action value=Upload id=PhotoButton>";
    echo "&nbsp; &nbsp; &nbsp;" . fm_text('Location',$dat['Data'],$dat['Field'],1,'',"onchange=document.getElementById('NewLoc').click()",'NewImage');
    echo "<input type=submit name=Action value=Change id=NewLoc>";
    echo "<input type=submit name=Action value=Rotate>";
  }
  
function New_Image() {
  $dat = &ImgData();
  $suf = $dat['Suf'];
  if (file_exists($dat['FinalLoc'] . ".$suf")) {
    $FinalLoc = $dat['FinalLoc'] . ".$suf";
    $ArcLoc = $dat['ArcLoc'] . ".$suf";
    Archive_Stack($ArcLoc);
    copy($FinalLoc,$ArcLoc);
//echo "Should have archived<br>";
  }
  $dat['Data'][$dat['Field']] = $_POST['NewImage']; // Fetch and store image - consider stacking orig image
  $dat['Put']($dat['Data']);
}

function Rotate_Image() {
  $FinalLoc = $_POST['FinalLoc'];
  $image = imagecreatefromstring(file_get_contents($FinalLoc));
  $newimage = imagerotate($image,90,0);
  $dat = &ImgData();
  $suf = $dat['Suf'];
  switch ($suf) {
  case 'jpeg':
  case 'jpg':
    imagejpeg($newimage,$FinalLoc);
    break;
  case 'png':
    imagepng($newimage,$FinalLoc);
    break;
  }
}

// var_dump($_POST);
  if (isset($_POST['Edit']) || isset($_POST['Current'])) {
    Edit_Photo('Current');
  } else if (isset($_POST['Original'])) {
    Edit_Photo('Original');
  } else if (isset($_POST['Action'])) {
    if ($_POST['Action'] == 'Upload') Upload_Image();
    if ($_POST['Action'] == 'Change') New_Image();
    if ($_POST['Action'] == 'Rotate') Rotate_Image();
    Edit_Photo('Current');
  }

  Select_Photos();

  dotail();
}
/* TODO
d  Make crop update image shown
d  get original - conditional
d  upload
d  rescale for large
d  Venues
d  make it remember pcat/who correctly 
d  mkdir
  After crop update location
  Zoom in/out
  Rotate
  Darken/Lighten
  Archive stack, use on change , also upload, new url
  Access to stack


*/
?>
