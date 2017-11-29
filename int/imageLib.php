/**
* Resize an image and keep the proportions
* @author Allison Beckwith <allison@planetargon.com>
* @param string $filename
* @param integer $max_width
* @param integer $max_height
* @return image
*/
function resizeImage($filename, $max_width, $max_height)
{
    list($orig_width, $orig_height) = getimagesize($filename);

    $width = $orig_width;
    $height = $orig_height;

    # taller
    if ($height > $max_height) {
        $width = ($max_height / $height) * $width;
        $height = $max_height;
    }

    # wider
    if ($width > $max_width) {
        $height = ($max_width / $width) * $height;
        $width = $max_width;
    }

    $image_p = imagecreatetruecolor($width, $height);
// rjp modified here
    switch(pathinfo($filename,PATHINFO_EXTENSION)) {
    case 'jpg' :
    case 'jpeg' :
      $image = imagecreatefromjpeg($filename);
      break;
    case 'gif' :
      $image = imagecreatefromgif($filename);
      break;
    case 'bmp' :
      $image = imagecreatefrombmp($filename);
      break;
    case 'png' :
      $image = imagecreatefrompng($filename);
      break;
    default:
      $image = imagecreatetruecolor($width, $height);
    } 
// end mod

    imagecopyresampled($image_p, $image, 0, 0, 0, 0, 
                                     $width, $height, $orig_width, $orig_height);

    return $image_p;
}

// Will need saving afterwards see imagepng

