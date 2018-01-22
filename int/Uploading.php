<?php // Generic Uploading code

// THIS CODE IS HORRIBLE NEEDS TO BE MUCH IMPROVED
// Action at a distance, hidden globals, spagetti code...

function Upload_Init($Dir='') {
  global $YEAR,$Side,$Sidey,$Put,$Puty,$snum;

//var_dump($_POST);

// NEED TO MAKE THIS WORK FOR TRADE

  if ($Dir == '' || $Dir == 'Side' || $Dir == 'Act' || $Dir == 'Other' || $Dir == 'Sides' || $Dir == 'Acts' || $Dir == 'Others') {
    $snum = $_POST{'Id'};
    $Side = Get_Side($snum);
    $Put = "Put_Side";
    $type = ($Side['IsASide'] ? 'Side' : $Side['IsAnAct'] ? 'Act' : 'Other');
    switch ($type) {
    case 'Side':
      $Sidey = Get_SideYear($snum);
      $Puty = 'Put_SideYear';
      break;

    case 'Act':
      $Sidey = Get_SideYear($snum);
      $Puty = 'Put_ActYear';
      break;

    case 'Other':
      $Sidey = Get_SideYear($snum);
      $Puty = 'Put_ActYear';
      break;
    }
  } else if ($Dir == 'Trade') {
    $snum = $_POST{'Tid'};
    $type = 'Trade';
    $Side = Get_Trader($snum);
    $Sidey = Get_Trade_Year($snum,$YEAR);
    $Puty = 'Put_Trade_Year';
    $Put = 'Put_Trader';
  } else if ($Dir == 'News') {
    $snum = $_POST{'id'};
    $type = 'News';
    $Side = Get_News($snum);
    $Put = 'Put_News';
  } else {
    $snum = 0;
    $type = 'Upload';
    $Side = 0;
    $Put = 0;
  }

//  A_Check('Participant',$type,$snum);
}

/*
  if existing file.sfx
    rename existing file as temp.sfx
    if move to new file delete temp - replaced message
    else rename temp back to old file.sfx - error uploading
  else if !move to new file error uploading
    else if database update then uploaded
    else file up, no  db
 */

function Upload_Insurance($Dir='Sides') {
  global $YEAR,$Side,$Sidey,$Put,$Puty,$snum;

  Upload_Init($Dir);

  $target_dir = "Insurance/$YEAR/$Dir/";
  umask(0);
  if (!file_exists($target_dir)) mkdir($target_dir,0775,true);
  $suffix = pathinfo($_FILES["InsuranceForm"]["name"],PATHINFO_EXTENSION);
  $target_file = $target_dir . "$snum.$suffix";

//var_dump($_FILES,$target_file);
  if ($Sidey['Insurance']) {
    $files = glob($target_dir . $snum . ".*");
    $Current = $files[0];
    rename($Current, "$Current.old");
    if (move_uploaded_file($_FILES["InsuranceForm"]["tmp_name"], $target_file)) {
      unlink("$Current.old");
      return "The Insurance file has been replaced by " . basename( $_FILES["InsuranceForm"]["name"]);
    } else {
      rename("$Current.old",$Current);
      return "<div class=Err>Sorry, there was an error uploading your Insurance file.</div>";
    }
  } else {
    if (move_uploaded_file($_FILES["InsuranceForm"]["tmp_name"], $target_file)) {
      $Sidey['Insurance'] = 1;
      $_POST['Insurance'] = 1;
      if ($Puty($Sidey)) {
        return "The Insurance file ". basename( $_FILES["InsuranceForm"]["name"]). " has been uploaded.";
      } else {
        return "<div class=Err>File uploaded but database did not update... " . $db->error . "</div>";
      }
    } else {
      return "<div class=Err>Sorry, there was an error uploading your Insurance file.</div>";
    }
  }
  return 0; 
}


function Upload_Image($Dir='Sides',$fld) {
  global $YEAR,$Side,$Sidey,$Put,$Puty,$snum;

  Upload_Init($Dir);
  include_once("ImageLib.php"); 

  $target_dir = "images/$Dir";
  umask(0);
  if (!file_exists($target_dir)) mkdir($target_dir,0775,true);
  $suffix = strtolower(pathinfo($_FILES["PhotoForm"]["name"],PATHINFO_EXTENSION));
  if ($snum) {
    $target_file = "$target_dir/$snum.$suffix";
  } else {
    $target_file = "$target_dir/" . basename($_FILES["PhotoForm"]["name"]);
  }
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
        $Side['ImageWidth'] = $stuff[0];
        $Side['ImageHeight'] = $stuff[1];
      } 
      if ($Side) {
        if (isset($Side[$fld]) && $Side[$fld] == "/" . $target_file) {
          return "The photo has been replaced by ". basename( $_FILES["PhotoForm"]["name"]) ;
        } else {
          $Side[$fld] = $_POST[$fld] = "/" . $target_file;
          if ($Put($Side)) {
            return "The file ". basename( $_FILES["PhotoForm"]["name"]). " has been uploaded.";
          } else {
            return "<div class=Err>File uploaded but database did not update... " . $db->error . "</div>";
          }
	}
      }
    } else {
      return "<div class=Err>Sorry, there was an error uploading your file.</div>";
    }
  }
  return 0;
}

function Upload_Photo($Dir='Sides') {
  return Upload_Image($Dir,'Photo');
}

function Upload_PASpec($Dir='') {
  global $YEAR,$Side,$Sidey,$Put,$Puty,$snum;

  Upload_Init($Dir);

  $target_dir = "PAspecs/$Dir";
  umask(0);
  if (!file_exists($target_dir)) mkdir($target_dir,0775,true);
  $suffix = pathinfo($_FILES["PASpec"]["name"],PATHINFO_EXTENSION);
  $target_file = $target_dir . "$snum.$suffix";

  $files = glob("$target_dir/$snum.*");
  $Current = $files[0];
  $Cursfx = pathinfo($Current,PATHINFO_EXTENSION );

  if (file_exists("$target_dir/$snum.$Cursfx")) {
    rename($Current, "$Current.old");
    if (move_uploaded_file($_FILES["PASpec"]["tmp_name"], $target_file)) {
      unlink("$Current.old");
      return "The PA Specification file has been replaced by " . basename( $_FILES["PASpec"]["name"]);
    } else {
      rename("$Current.old",$Current);
      return "<div class=Err>Sorry, there was an error uploading your PA Specification file.</div>";
    }
  } else {
//var_dump($target_file, $_FILES);
    if (move_uploaded_file($_FILES["PASpec"]["tmp_name"], $target_file)) {
      $Side['StagePA'] = '@@FILE@@';
      if ($Put($Side)) {
        return "The PA Specification ". basename( $_FILES["PASpec"]["name"]). " has been uploaded.";
      } else {
        return "<div class=Err>File uploaded but database did not update... " . $db->error . "</div>";
      }
    } else {
      return "<div class=Err>Sorry, there was an error uploading your PA Specification file.</div>";
    }
  }
  return 0; 
}
?>
