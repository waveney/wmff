<?php // Image manipulation

function Image_Convert($src,$Twidth,$Theight,$target) {
  $suffix = pathinfo($target,PATHINFO_EXTENSION);

  list($Swidth,$Sheight,$type) = getimagesize($src);

  if ($Swidth <= $Twidth && $Sheight <= $Theight) return move_uploaded_file($src, $target);

  if ($Swidth > $Twidth) {
    $Nwidth = $Twidth;
    $Nheight = (int)($Sheight * $Twidth/$Swidth);
    if ($Nheight > $Theight) {
      $Nheight = $Theight;
      $Nwidth = (int)($Swidth * $Theight/$Sheight);
    }
  } else if ($Sheight > $Theight) {
    $Nheight = $Theight;
    $Nwidth = (int)($Swidth * $Theight/$Sheight);
  }
    
  $tmp = imagecreatetruecolor($Nwidth, $Nheight);
  $whiteBackground = imagecolorallocate($tmp, 255, 255, 255); 
  imagefill($tmp,0,0,$whiteBackground); // fill the background with white

  switch ($suffix) {
    case 'jpg':
    case 'jpeg':
      $img = imagecreatefromjpeg($src);
      imagecopyresampled( $tmp, $img, 0, 0, 0, 0,$Nwidth,$Nheight, $Swidth, $Sheight );
      return imagejpeg($tmp,$target);

    case 'png':
      $img = imagecreatefrompng($src);
      imagecopyresampled( $tmp, $img, 0, 0, 0, 0,$Nwidth,$Nheight, $Swidth, $Sheight );
      return imagepng($tmp,$target);

    case 'gif':
      $img = imagecreatefromgif($src);
      imagecopyresampled( $tmp, $img, 0, 0, 0, 0,$Nwidth,$Nheight, $Swidth, $Sheight );
      return imagegif($tmp,$target);
  }
 
} 

function Image_Validate($img) {
  $file = file_get_contents($img);
  if ($file === false) return "Could not get the Photo";

//  file_put_contents("Store/testfile",$file);

  if (!preg_match('/\.([^.]*)$/',$img,$sfx)) return "Unknown Image type for Photo";

  $first8 = substr($file,0,8);

  switch (strtolower($sfx[1])) {

  case 'gif':
    if (preg_match('/^GIF8[79]a/',$first8)) return;
    return "Photo does not look like a gif";

  case 'jpeg':
  case 'jpg':
   if (preg_match('/^\xff\xd8\xff/',$first8)) return;
   return "Photo does not look like a jpeg";

  case 'png':
   if (preg_match('/^\x89PNG\x0d\x0a\x1a\x0a/',$first8)) return;
   return "Photo does not look like a png";

  default:
    return "Unknown Image type for Photo";
  }
}

function Get_Gallery_Names() {
  global $db;
  $res=$db->query("SELECT * FROM Galleries");
  if ($res) {
    while ($g = $res->fetch_assoc()) $ans[$g['id']] = $g;
    return $ans;
  }
}

function Get_Gallery_Name($id) {
  global $db;
  $res=$db->query("SELECT * FROM Galleries WHERE id=$id");
  if ($res) return $res->fetch_assoc();
}

function Put_Gallery_Name(&$now) {
  $Cur = Get_Gallery_Name($now['id']);
  Update_db('Galleries',$Cur,$now);
}

function Get_Gallery_Photos($id) {
  global $db;
  $res=$db->query("SELECT * FROM GallPhotos WHERE Galid=$id ORDER BY RelOrder");
  if ($res) {
    while ($g = $res->fetch_assoc()) $ans[$g['id']] = $g;
    return $ans;
  }
}

function Get_Gallery_Photo($id) {
  global $db;
  $res=$db->query("SELECT * FROM GallPhotos WHERE id=$id");
  if ($res) return $res->fetch_assoc();
}

function Put_Gallery_Photo(&$now) {
  $Cur = Get_Gallery_Photo($now['id']);
  Update_db('GallPhotos',$Cur,$now);
}

?>
