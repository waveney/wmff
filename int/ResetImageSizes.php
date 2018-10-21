<?php
  include_once("fest.php");

  A_Check('SysAdmin');
  
  include_once("DanceLib.php");
  include_once("TradeLib.php");
  include_once("ImageLib.php");
  dostaffhead("Update image info for all participants");
  echo "This may be extended to other image categories later.<p>";

  global $db;
  
  if ($_REQUEST['TRADE']) {
    $ans = $db->query("SELECT * FROM Trade");
    while ($trad = $ans->fetch_assoc()) {
      $Photo = $trad['Photo'];
      $id = $trad['Tid'];
      if ($Photo) {
        if (preg_match('/^\s*https?:\/\//i',$Photo)) {
          preg_match('/\.(jpg|jpeg|gif|png)/i',$Photo,$mtch);
          if ($mtch) {
            $sfx = strtolower($mtch[1]);
            $loc = "/images/Trade/$id.$sfx"; 
            $res = Localise_Image($Photo,$trad, $loc);
          } else {
            $sfx = Find_Hidden_Image_Type($Photo);
            if ($sfx) {
              $loc = "/images/Trade/$id.$sfx"; 
              $res = Localise_Image($Photo,$trad,$loc);
            }
          }        
          Put_Trader($trad);
          echo "Cached " . $id . " " . $trad['SN'] . "<br>\n";
          continue;
        } else if (preg_match('/^\/(.*)\?.*/',$Photo,$mtch)) {
          $stuff = getimagesize($mtch[1]);
        } else if (preg_match('/^\/(.*)/',$Photo,$mtch)) {
          $stuff = getimagesize($mtch[1]);
        } else {
          $stuff = getimagesize($Photo);
        }
        if ($stuff) {
          $wi = $stuff[0];
          $ht = $stuff[1];
          $trad['ImageHeight'] = $ht;
          $trad['ImageWidth'] = $wi;
          Put_Trader($trad);
          echo "Done " . $id  . " " . $trad['SN']. "<br>\n";
        } else {
          echo "Not Done " . $id . " " . $trad['SN']. "<br>\n";
        }
      }
    }
  
  } else {
    $ans = $db->query("SELECT * FROM Sides");
    while ($side = $ans->fetch_assoc()) {
      $Photo = $side['Photo'];
      $id = $side['SideId'];
      if ($Photo) {
        if (preg_match('/^\s*https?:\/\//i',$Photo)) {
          preg_match('/\.(jpg|jpeg|gif|png)/i',$Photo,$mtch);
          if ($mtch) {
            $sfx = strtolower($mtch[1]);
            $loc = "/images/Sides/$id.$sfx"; 
            $res = Localise_Image($Photo,$side, $loc);
          } else {
            $sfx = Find_Hidden_Image_Type($Photo);
            if ($sfx) {
              $loc = "/images/Sides/$id.$sfx"; 
              $res = Localise_Image($Photo,$side,$loc);
            }
          }        
          Put_Side($side);
          echo "Cached " . $id . " " . $side['SN'] . "<br>\n";
          continue;
        } else if (preg_match('/^\/(.*)\?.*/',$Photo,$mtch)) {
          $stuff = getimagesize($mtch[1]);
        } else if (preg_match('/^\/(.*)/',$Photo,$mtch)) {
          $stuff = getimagesize($mtch[1]);
        } else {
          $stuff = getimagesize($Photo);
        }
        if ($stuff) {
          $wi = $stuff[0];
          $ht = $stuff[1];
          $side['ImageHeight'] = $ht;
          $side['ImageWidth'] = $wi;
          Put_Side($side);
          echo "Done " . $id  . " " . $side['SN']. "<br>\n";
        } else {
          echo "Not Done " . $id . " " . $side['SN']. "<br>\n";
        }
      }
    }
  }

  dotail();
?>

