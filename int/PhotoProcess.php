<?php
  include_once("fest.php");
  include_once("DanceLib.php");
  include_once("MusicLib.php");
  include_once("TradeLib.php");
  include_once("ProgLib.php");

$Who = $Pcat = 0;
$Shapes = ['Landscape','Square','Portrait','Banner','Free Form'];
$Shapes_aspect = ['4/3','1/1','3/4','7/2','NaN'];
$Shape = 0;

$Photo_Data = [
    'Perf'=> ['Data'=>'Get_Side',
              'Field' => 'Photo', 
              'FinalLoc' => "images/Sides/", 
              'Put_Data' => 'Put_Side', 
              'ListFn' => '', 
              'ListData' => '', 
              'Allow' => 1, // TODO What should this be?  Access('Staff','Dance'),
             ],

    'Side'=> ['Data'=>'Get_Side',
              'Field' => 'Photo', 
              'FinalLoc' => "images/Sides/", 
              'Put_Data' => 'Put_Side', 
              'ListFn' => 'Perf_Name_List', 
              'ListData' => 'IsASide', 
              'Allow' => Access('Staff','Dance'),
             ],
    'Act'=>  ['Data'=>'Get_Side',
              'Field' => 'Photo', 
              'FinalLoc' => "images/Sides/", 
              'Put_Data' => 'Put_Side', 
              'ListFn' => 'Perf_Name_List', 
              'ListData' => 'IsAnAct', 
              'Allow' => Access('Staff','Music'),
             ],
    'Comics'=> ['Data'=>'Get_Side',
              'Field' => 'Photo', 
              'FinalLoc' => "images/Sides/", 
              'Put_Data' => 'Put_Side', 
              'ListFn' => 'Perf_Name_List', 
              'ListData' => 'IsFunny', 
              'Allow' => Access('Staff','Comedy'),
             ],
    'Family'=> ['Data'=>'Get_Side',
              'Field' => 'Photo', 
              'FinalLoc' => "images/Sides/", 
              'Put_Data' => 'Put_Side', 
              'ListFn' => 'Perf_Name_List', 
              'ListData' => 'IsFamily', 
              'Allow' => Access('Staff','Family'),
             ],
    'Other'=> ['Data'=>'Get_Side',
              'Field' => 'Photo', 
              'FinalLoc' => "images/Sides/", 
              'Put_Data' => 'Put_Side', 
              'ListFn' => 'Perf_Name_List', 
              'ListData' => 'IsOther', 
              'Allow' => Access('Staff','Other'),
             ],
    'Trade'=> ['Data'=>'Get_Trader',
              'Field' => 'Photo', 
              'FinalLoc' => "images/Trade/", 
              'Put_Data' => 'Put_Trader', 
              'ListFn' => 'Get_All_Traders', 
              'ListData' => '0', 
              'Allow' => Access('Staff','Trade'),
             ],
    'Sponsors'=> ['Data'=>'Get_Sponsor',
              'Field' => 'Photo', 
              'FinalLoc' => "images/Sponsors/", 
              'Put_Data' => 'Put_Sponsor', 
              'ListFn' => 'Get_Sponsor_Names', 
              'ListData' => '', 
              'Allow' => Access('Staff','Sponsors'),
             ],
    'Venues'=> ['Data'=>'Get_Venue',
              'Field' => ['Photo','Photo2'], 
              'FinalLoc' => "images/Venues/",
              'Put_Data' => 'Put_Venue', 
              'ListFn' => 'Perf_Name_List', 
              'ListData' => '', 
              'Allow' => Access('Staff','Venues'),
             ],
    'Events'=> ['Data'=>'Get_Event',
              'Field' => 'Photo', 
              'FinalLoc' => "images/Events/", 
              'Put_Data' => 'Put_Event', 
              'ListFn' => 'Perf_Name_List', // TODO This years only
              'ListData' => '', 
              'Allow' => Access('Staff','Venues'),
             ],
    ];



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

Photo Access - Go through each type of photo - for access list
  Perfs - ISa, Trade, Venue (2), Sponsors, Events
Show Photo
Photo Edits
  Crop, Zoom, Rotate
  New image - drag/drop, Select file, URL, cut/paste
  
Archive stack
Archive Thumbs




*/

// Photo temp store is
// Temp/$cat.$id.data, Temp/$cat.$id.img.sfx $cat.$id.origimg.sfx
// On save delete files


global $Cats,$CatLists,$Mode,$id,$PM_Data,$PD;
$Cats = [];
$CatList = [];
$Mode = 0;

function Cache_Image($src) {
  global $PM_Data,$PD,$Cat,$id;
  
  if ($src[0] == '/') $src = ltrim($src,'/');
  $src = preg_replace('/\?.*/','',$src);
 
  $sfx = strtolower(pathinfo($src,PATHINFO_EXTENSION));
  $new = "Temp/$Cat.$id.$sfx";
  
  copy($src,"Temp/$Cat.$id.$sfx");
  return $new;
}

function Save_PM_data() {
  global $PM_Data,$Cat,$id,$Mode;
  file_put_contents("Temp/$Cat.$id.data",json_encode($PM_Data));
}

function Load_PM_Data() {
  global $PM_Data,$Cat,$id,$Mode,$Photo_Data,$PD,$ImgNum;

  $PD = $Photo_Data[$Cat];
  $ArcLoc = $PD['FinalLoc'];
  $ArcLoc = preg_replace('/images\//','ArchiveImages/',$ArcLoc);
  $PD['Arc'] = $ArcLoc;
  $PD['DataRec'] = $PD['Data']($id);
  $pfx = "Temp/$Cat.$id";

  if (file_exists("$pfx.data")) {
    $PM_Data = json_decode(file_get_contents("$pfx.data"),TRUE);
//var_dump($PM_Data);
    $Mode = $PM_Data['Mode'];
    $ImgNum = $PM_Data['ImgNum'];
  } else {
    exec("rm $pfx.*",$ans); // Remove old files
    $PM_Data = ['Cat'=>$Cat,'id'=>$id,'Mode'=>$Mode,'ImgNum'=>$ImgNum];
    
    if ($ImgNum) {
      if (is_array($PD['Field'])) {
        $PM_Data['Field'] = $PD['Field'][$ImgNum];
      } else {
        $PM_Data['Field'] = $PD['Field'];
      }
    } else if (is_array($PD['Field'])) {
        $PM_Data['Field'] = $PD['Field'][0];
    } else {
      $PM_Data['Field'] = $PD['Field'];
    }
    
    $PM_Data['Image'] = $PM_Data['OrigImage'] = $PD['DataRec'][$PM_Data['Field']];
    if ($PM_Data['Image']) $PM_Data['Image'] = Cache_Image($PM_Data['Image']);
    $PM_Data['rand'] = time();
    
    $hist = glob(($ImgNum?("$ArcLoc$id" . "I$ImgNum.*"):"$ArcLoc$id.*")); 
    if ($hist) {
      foreach ($hist as $i=>$h) {
        $sfx = strtolower(pathinfo($h,PATHINFO_EXTENSION));
        $res = link($h,"$pfx.thumb.$i.$sfx");
        $PM_Data['Thumbs'][$i] = $sfx;
//        var_dump($res);
      }
    }
  }
}

function Save_Orig_Img() {

}

function Save_PM_Img($CurLoc) {
  
}

function Photos_AccessMany($id) { // Multi Images
  global $Cats,$CatList,$USER,$Pcat;
  $Mode = 0;

  switch ($USER['AccessLevel']) {
  case $Access_Type['Participant'] : 
    if ($USER['Subtype'] == 'Perf') {
      if ($id != $USERID) Error_Page("Not accessable to you");
      $Pcat = 0;
      break;
    } elseif ($USER['Subtype'] == 'Trade') {
      if ($id != $USERID) Error_Page("Not accessable to you");
      $Pcat = 5;
      break;
    } else Error_Page("Not accessable to you");

  case $Access_Type['Upload'] :
  case $Access_Type['Steward'] :
    Error_Page("Not accessable to you");

  case $Access_Type['Internal'] : 
  case $Access_Type['SysAdmin'] : 
    $Mode = 1; // Drop through
  case $Access_Type['Staff'] :
  case $Access_Type['Committee'] :
    $capmatch = 0;
    $Side = Get_Side($snum);
    foreach ($FestTypes as $p=>$d) if ($Side[$d[0]] && $USER[$d[2]]) $capmatch = 1;
    if (!$capmatch) fm_addall('disabled readonly');    
    break;

    break;
  }  


}

function Photo_Access1($Cat,$id) { // Single image
  global $Mode,$Pcat,$Who,$PData,$Photo_Data,$id,$ImgNum;
  
}

function Show_Thumbs() { // Show Thumbnails of saved images (if any) Click to Select
  global $PM_Data,$PD,$Cat,$id,$ImgNum;

//var_dump($PM_Data);
  $pfx = "Temp/$Cat.$id";
  if (isset($PM_Data['Thumbs'])) {
    echo "<div class=PM_Photo_Thumbs>Older / Original images - click to Select<br>";
    foreach ($PM_Data['Thumbs'] as $i=>$f) {
//    var_dump($i,$f);
//      echo "<form method=post>" . fm_hidden('Cat',$PM_Data['Cat']) . fm_hidden('Id',$PM_DATA['id']) . fm_hidden('ImgNum',$PM_DATA['ImgNum']) . 
//           fm_hidden('Action','Select') . fm_hidden('Image','$f') . 
//           "<input type=submit src="$Arc/$f" class=PH_Photo_Thumb></form>";
      echo "<button type=submit name=ACTION value='Select-$i'><img src='$pfx.thumb.$i.$f' class=PH_Photo_Thumb></button>";
//      echo "<input type=image value='$i' name=ACTION src='$pfx.thumb.$i.$f' class=PH_Photo_Thumb>";
    } 
    echo "</div>";
  } elseif (file_exists($PM_Data['OrigImage'])) {
    $f = $PM_Data['OrigImage'];
    echo "<div class=PM_Photo_Thumbs>Older / Original images - click to Select ZZ<br>";
    echo "<input type=image name=ACTION value='Select-$f' src='$f' class=PH_Photo_Thumb>";   
    echo "</div>";   
  } 
}

function Show_Other_Images() {
  global $PD,$PM_Data;

}

function Photo_Help($Area='',$Right=0) {

  echo "<span " . ($Right?' class=floatright':'') . " id=largeredsubmit onclick=($('.HelpDiv').toggle()) >Click to toggle Photo Help</span>";
  echo "<div class=HelpDiv hidden>";

  echo "Help to be written";

  echo "</div>\n";
}


function Photo_Show() {
  global $PM_Data,$Shapes,$PD,$Shape,$Shapes_aspect,$Mode,$Cat,$id;

  dostaffhead("Photo Management",['/js/cropper.js',"/css/cropper.css","js/dropzone.js","css/dropzone.css","js/Uploads.js"]);
  
  Photo_Help(); 
 
  echo "<p><h2>Photo Management for - " . $PD['DataRec']['SN'] . "</h2>";
  echo "<form id=cropform method=post action=PhotoProcess enctype='multipart/form-data' >";
  echo fm_hidden('Cat',$PM_Data['Cat']);
  echo fm_hidden('id',$PM_Data['id']);
  echo fm_hidden('ImgNum',$PM_Data['ImgNum']);
  echo fm_hidden('aspectRatio',$Shapes_aspect[$Shape]);
  echo "Shape: " . $Shapes[$Shape] . ($Mode?"<p>":" - If you really really want it cropped differently, ask<p>");
//          echo "<div><img src=$PhotoURL id=image style='max-height:500; max-width:600;'><p></div>";
  echo "<div class=PM_ImageDiv><img id=image src=" . $PM_Data['Image'] . "?" . $PM_Data['rand'] . " class=PM_Image style='max-height:500; max-width:600;'></div>";
  echo "<div id=croptool>";
  echo "</div><div id=cropresult></div>";

  echo "<div class=PM_Tools>";
  
// Mode =1 Save, Crop, Save and Crop Mode = 0 Save and Crop, Crop as well?

   if ($Mode) {
     echo "<input type=submit name=ACTION value='Crop' id=crop_button>";
     echo "<input type=submit name=ACTION value='Save'>";   
   } else {
     echo "<input type=submit name=ACTION value='Crop and Save' id=crop_button>";  
   }

    echo "<input type=submit name=ACTION value='Rotate'>";
    if ($PM_Data['Mode']) {  
      echo fm_select($Shapes,$_POST,'Shape') . " " . "<input type=submit name=ACTION value='Set Crop Shape'>";
      // Add Selection tools if allowed
    }
  echo "</div>";
  Show_Other_Images();
  Show_Thumbs();
  echo fm_DragonDrop(1, 'Image',$Cat,$id,$PD['DataRec'],$Mode);
 /*
  echo <<<XXXX
<script language=Javascript defer>
var CC;
$(document).ready(function() {
  if (!$('#aspectRatio')) return;
  CC = ($('#image').cropper({ 
        aspectRatio: $('#aspectRatio').val(),
        viewMode:1,
        autoCropArea:1,
  }));

  document.getElementById('crop_button').addEventListener('click', function(){

    var DD = $('#image').cropper('getCroppedCanvas');

    DD.toBlob(function (blob) {
      var form = document.getElementById('cropform');
      var formData = new FormData(form);

      var fred = formData.append('croppedImage', blob,'croppedImage');

      $.ajax('/int/PhotoManage', {
          method: "POST",
          data: formData,
          processData: false,
          contentType: false, 
          success: function (resp) { 
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
XXXX;*/
}

function Photo_Upload() {
  global $PD,$PM_Data,$Who,$Pcat,$db,$Cat,$id;
  $PM_Data['rand']++;
  if (isset($_FILES["Upload"])) {
    include_once("ImageLib.php"); 
    $suffix = strtolower(pathinfo($_FILES["Upload"]["name"],PATHINFO_EXTENSION));
    $pfx = "Temp/$Cat.$id";
    $target_file = "$pfx.$suffix";
    $uploadOk = 1;
  // Check if image file is a actual image or fake image
//var_dump($_FILES);
    $check = getimagesize($_FILES["Upload"]["tmp_name"]);
    if ($check == false) {
      echo "<div class=Err>File is not an image</div>";
      $uploadOk = 0;
    } elseif ($check[0] > 800 || $check[1] > 536) { // Need to resize
      $move = Image_Convert($_FILES["Upload"]["tmp_name"],800,536, $target_file);
    } else {
      $move = move_uploaded_file($_FILES["Upload"]["tmp_name"], $target_file);
    }

    $PM_Data['Image'] = $target_file;
  } elseif (isset($_FILES["croppedImage"])) {
    $move = move_uploaded_file($_FILES["croppedImage"]["tmp_name"], $PM_Data['Image']);
  }
}

function Photo_Rotate() {
  global $PD,$PM_Data,$Who,$Pcat,$db;
  $PM_Data['rand']++;

  $image = imagecreatefromstring(file_get_contents($PM_Data['Image']));
  $newimage = imagerotate($image,90,0);
  $suf = strtolower(pathinfo($PM_Data['Image'],PATHINFO_EXTENSION));
  switch ($suf) {
  case 'jpeg':
  case 'jpg':
    imagejpeg($newimage,$PM_Data['Image']);
    break;
  case 'png':
    imagepng($newimage,$PM_Data['Image']);
    break;
  }
}

function Photo_Select($i) { // 0 = New Img, 1=Cur Img (if any), 2... stacked images
  global $PM_Data, $Photo_Data,$PD, $Cat,$id, $Mode;
  $pfx = "Temp/$Cat.$id";
  $f = $PM_Data['Thumbs'][$i];
  copy("$pfx.thumb.$i.$f","$pfx.$f");
  $PM_Data['Image'] = "$pfx.$f";
  $PM_Data['rand']++;
}


// Think about gallery??
function Photo_Actions($Action) {
  switch ($Action) {
  

  
  case 'Upload': // Main input new image -> copy to temp file initially
    Photo_Upload();
    return;
  
  case 'Rotate': 
    Photo_Rotate();
    return;
  
  case 'Shape': // Landscape | others | default always Landscape, no options given to non fest
  
  case 'Crop and Save': // Crop to current image and save
  
  case 'Crop':
  
  case 'Save':
  
  case 'Show': // Reshow, no actual change
  
  case 'Zoom': // Reshow, no actual change
  
  case 'Revert': // To original image
  
  case (preg_match('/Select-(.*)/',$Action,$mtch)?true:false): // Thumbs -> Copy to temp file 
    Photo_Select($mtch[1]);
    return;
    
  default:
  }
}

//************************************************************************************ Start Here
$id = 0;
$Cat = '';

if (isset($_REQUEST['id'])) {
  $id = $_REQUEST['id'];
} elseif (isset($_REQUEST['Id'])) {
  $id = $_REQUEST['Id'];
} 

if (isset($_REQUEST['Cat'])) $Cat = $_REQUEST['Cat'];

if ($Cat) {
  Photo_Access1($Cat,$id);
} else {
  Photo_AccessMany($id);
}

//var_dump($_REQUEST);
Load_PM_Data();
if (isset($_REQUEST['ACTION'])) {
  Photo_Actions($_REQUEST['ACTION']);
} elseif ($_FILES) {
  Photo_Upload();
}

Photo_Show();

Save_PM_data();

dotail();

/* 
Upload/Change Photo button -> PhotoMange (with restrictions if Perf)
On upload downconvert to 800x536 as first step if needed
Select File /Drag drop -> Show new phot and crop lines
Crop and Save -> Back to Perf
[ Rotate, Change crop format (if staff) ] 
if !Staff display message it is possible to have a picture that does not conform to the standard crop, but there has to be a very good reason.
[Staff] Thumbs of older images - click to examine

Need a record of what is being done - large complex hidden value or Temp file(s) based on userid

ON start get data about type of image store
  
Common Thumbs, history needed

Archive format :
  ArchiveImages/Cat/dddddIdd.dd.sfx  idImg.hist.sfx

******************************************************************* OLD CODE
*/


function ImgData() {
  global $Who,$Pcat;
  $Pcat = $_REQUEST['PCAT'];
  $Who = (isset($_REQUEST['WHO']) && strlen($_REQUEST['WHO']) ? $_REQUEST['WHO'] : $_REQUEST["WHO$Pcat"]);

  switch ($Pcat) {
    case 0: // Sides
    case 1: // Acts
    case 2: // Comics
    case 3: // Family
    case 4: // Other
      $Data = Get_Side($Who);
      $Field = 'Photo';
      $FinalLoc = "images/Sides/" . $Who;
      $Put_Data = 'Put_Side';
      break;
    case 5: // Trader
      $Data = Get_Trader($Who);
      $Field = 'Photo';
      $FinalLoc = "images/Trade/" . $Who;
      $Put_Data = 'Put_Trader';
      break;
    case 6: // Sponsor
      $Data = Get_Sponsor($Who);
      $Field = 'Image';
      $FinalLoc = "images/Sponsors/" . $Who;
      $Put_Data = 'Put_Sponsor';
      break;
    case 7: // Venue
      $Data = Get_Venue($Who);
      $Field = 'Image';
      $FinalLoc = "images/Venues/" . $Who;
      $Put_Data = 'Put_Venue';
      break;
    case 8: // Venue2
      $Data = Get_Venue($Who);
      $Field = 'Image2';
      $FinalLoc = "images/Venues/" . $Who . "I2";
      $Put_Data = 'Put_Venue';
      break;
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

function Change_Rand(&$dat) {
//echo "In Change Rand -";
//var_dump($dat);
  if(isset($dat['Data'][$dat['Field']])) {
    $dat['Data'][$dat['Field']].= rand(0,9);
  } else {
    $dat['Data'][$dat['Field']] = rand(0,9);
  }
  $dat['Put']($dat['Data']);
}

function Upload_Image() {
  global $Who,$Pcat,$db;
  include_once("ImageLib.php"); 
  $dat = ImgData();
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
//var_dump($_FILES);
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

    if ($move) { // TODO this bit not in yet
      $stuff = getimagesize($target_file);
      if ($stuff) {
        $dat['Data']['ImageWidth'] = $stuff[0];
        $dat['Data']['ImageHeight'] = $stuff[1];
      } 
      $pos = &$dat['Data'][$dat['Field']];
      if (isset($pos) && $pos == ("/" . $target_file)) {
        $pos = $_REQUEST[$dat['Field']] = "/" . $target_file . "?" . rand();
        $dat['Put']($dat['Data']);
        return "The image has been replaced by ". basename( $_FILES["PhotoForm"]["name"]) ;
      } else {
        $pos = $_REQUEST[$dat['Field']] = "/" . $target_file . "?" . rand();
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
  $dat = ImgData();
  $PhotoBefore = $_REQUEST['PhotoURL'];

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

  dostaffhead("Manage Photos",['/js/cropper.js',"/css/cropper.css",'/js/Uploads.js']);


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
$Shapes_aspect = array('4/3','1/1','3/4','7/2','NaN');
$Shape = 0;
  if (isset($_REQUEST['SHAPE'])) { 
    $Shape = $_REQUEST['SHAPE'];
  } else {
    $_REQUEST['SHAPE'] = $Shape;
  }
  $PhotoCats = array('Sides','Acts','Comics','Family','Other','Traders','Sponsors','Venues','Venue2');

  $Lists = array(
        'Sides'=> Perf_Name_List('IsASide'),
        'Acts'=>Perf_Name_List('IsAnAct'),
        'Comics'=>Perf_Name_List('IsFunny'),
        'Family'=>Perf_Name_List('IsFamily'),
        'Other'=>Perf_Name_List('IsOther'),

        'Traders'=>Get_All_Traders(0),
        'Sponsors'=>Get_Sponsor_Names(),
        'Venues'=>Get_Venues(0),
        'Venue2'=>Get_Venues(0),
        );

  $AccessNeeded = [
        'Sides'=>Access('Staff','Dance'),
        'Acts'=>Access('Staff','Music'),
        'Comics'=>Access('Staff','Comedy'),
        'Family'=>Access('Staff','Family'),
        'Other'=>Access('Staff','Other'),

        'Traders'=>Access('Staff','Trade'),
        'Sponsors'=>Access('Staff','Sponsors'),
        'Venues'=>Access('Staff','Venues'),
        'Venue2'=>Access('Staff','Venues'),
        ];
  
  function Archive_Stack($loc) {
//echo "Arc Stack called $loc<br>";
    if (!file_exists($loc)) return;
    $hist = 1;
    while (file_exists("$loc.$hist")) $hist++;
    rename($loc,"$loc.$hist");
//echo "Arc $loc renamed $loc.$hist<br>";
  }
  

  function Select_Photos() {
    global $Who,$Pcat;
    global $Shapes,$Shape,$PhotoCats,$Lists,$AccessNeeded;
    $mouse = 0;
    if (isset($_REQUEST['PCAT'])) {
      $mouse = $_REQUEST['PCAT'];
    } else {
      $_REQUEST['PCAT']=0;
    }
    
    $j = 0;
    foreach ($AccessNeeded as $i=>$showit) {
      if (!$showit) $PhotoCats[$j] = '';
      $j++;
    }
    echo "<h2>Select Photo to modify</h2><p>\n";
    echo "<form method=post action=PhotoManage>";
    echo fm_radio("Target shape",$Shapes,$_REQUEST,'SHAPE','',0) . "<p>";
    echo fm_radio("Photo For",$PhotoCats,$_REQUEST,'PCAT','onclick=PCatSel(event)',0);
    $i=0;
    foreach($Lists as $cat=>$dog) {
      if ($AccessNeeded[$cat]) echo "<span id=MPC_$i " . ($cat == $PhotoCats[$mouse]?'':'hidden') . "> : " . fm_select($dog,$_REQUEST,"WHO$i") . "</span>";
      $i++;
    }
    echo "<input type=submit name=Edit value=Edit><p>\n";
    echo "</form>\n";
  }

  function Edit_Photo($type='Current') {
    global $Who,$Pcat;
    global $Shapes,$Shape, $Lists,$PhotoCats;
//var_dump($_REQUEST);
    $dat = ImgData();

//var_dump($dat); echo "<p>";
    $Name = $dat['Data']['SN'];
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
          $url = preg_replace('/\?.*/','',$mtch[1]);
//var_dump($url);
          $img = file_get_contents($url);
        } else {
          $url = preg_replace('/\?.*/','',$PhotoURL);
//var_dump($url);
          $img = file_get_contents($url);
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
    echo "<form id=cropform method=post action=PhotoManage enctype='multipart/form-data' >";
    echo fm_hidden('PCAT',$Pcat) . fm_hidden("WHO",$Who);
    echo "Type: " . $PhotoCats[$dat['Pcat']] . "<br>";
    echo "For: $Name<br>";
    echo "Shape: " . $Shapes[$Shape] . "<p>";
    echo fm_hidden('aspectRatio',$aspect[$Shape]);
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
  global $Who,$Pcat;
  $dat = ImgData();
  $suf = $dat['Suf'];
  if (file_exists($dat['FinalLoc'] . ".$suf")) {
    $FinalLoc = $dat['FinalLoc'] . ".$suf";
    $ArcLoc = $dat['ArcLoc'] . ".$suf";
    Archive_Stack($ArcLoc);
    copy($FinalLoc,$ArcLoc);
//echo "Should have archived<br>";
  }
  $dat['Data'][$dat['Field']] = $_REQUEST['NewImage']; // Fetch and store image - consider stacking orig image
  $dat['Put']($dat['Data']);
}


// var_dump($_REQUEST);
  if (isset($_REQUEST['Edit']) || isset($_REQUEST['Current'])) {
    if (isset($_REQUEST['WHO'])) unset($_REQUEST['WHO']);
    Edit_Photo('Current');
  } else if (isset($_REQUEST['Original'])) {
    Edit_Photo('Original');
  } else if (isset($_REQUEST['Action'])) {
    if ($_REQUEST['Action'] == 'Upload') Upload_Image();
    if ($_REQUEST['Action'] == 'Change') New_Image();
    if ($_REQUEST['Action'] == 'Rotate') Rotate_Image();
    Edit_Photo('Current');
  }

  Select_Photos();

  dotail();
}
