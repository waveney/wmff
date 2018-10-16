<?php

// Displaying utilities for public site

function formatminimax(&$side,$link,$mnat=2) {
  global $YEAR;
  echo "<div class=mnfloatleft>";
  if ($side['Photo']) {
    $wi = $side['ImageWidth'];
    $ht = $side['ImageHeight'];
    if ($wi > ($ht * 1.1)) {
      $fmt  = ($wi > ($ht * 2))?'b':'l';
    } else {
      $fmt  = ($ht > ($wi * 1.1))?'p':'s';
    }
  } else {
    $fmt = 't';
  } // fmt t=txt, l=ls, p=pt, s=sq, b=ban
  $mnmx = ($side['Importance'] >= $mnat?'maxi':'mini');
  $id = AlphaNumeric($side['SN']);
  echo "<div class=$mnmx" . "_$fmt id=$id>";
  echo "<a href=/int/$link?sidenum=" . $side['SideId'] . "&Y=$YEAR>";
  if ($mnmx != 'maxi' && $side['Photo']) echo "<div class=mnmximgwrap><img class=mnmximg src='" . $side['Photo'] ."'></div>";
  echo "<div class=mnmxttl style='font-size:" . (27+$side['Importance']*3) . "px'>" . $side['SN'] . "</div>";
  if ($mnmx == 'maxi' && $side['Photo']) echo "<div class=mnmximgwrap><img class=mnmximg src='" . $side['Photo'] ."'></div>";
  echo "</a><div class=mnmxtxt>" . $side['Description'] . "</div>";
  echo "</div></div>\n";
}

// Check ET to see if imps should be found
function Get_Imps(&$e,&$imps,$clear=1,$all=0) {
  global $Event_Types_Full,$YEAR;
  $ETs = Get_Event_Types(1);
  $ets = $ETs[$e['Type']]['State']; 
  $useimp = ($Event_Types_Full[$e['Type']]['UseImp'] && ($e['BigEvent']==0));
  if ($clear) $imps = array();
  for($i=1;$i<5;$i++) {
    if (isset($e["Side$i"])) { if ($ee = $e["Side$i"])  { 
        $si = Get_Side($ee);
        if ($si) {
          $s = array_merge($si, munge_array(Get_SideYear($ee,$YEAR))); 
          if ($s && ($all || (( $s['Coming'] == 2) && ($ets >1 || ($ets==1 && Access('Participant','Side',$s)))))) $imps[$useimp?$s['Importance']:0][] = $s; }; }; };
    if (isset($e["Act$i"]))  { if ($ee = $e["Act$i"])   { 
        $si = Get_Side($ee);
        if ($si) {
          $s = array_merge($si, munge_array(Get_ActYear($ee,$YEAR))); 
          if ($s && ($all || (( $s['YearState'] >= 2) && ($ets >1 || ($ets==1 && Access('Participant','Act',$s)))))) $imps[$useimp?$s['Importance']:0][] = $s; }; }; };
    if (isset($e["Other$i"])){ if ($ee = $e["Other$i"]) { 
        $si = Get_Side($ee);
        if ($si) {
          $s = array_merge($si, munge_array(Get_ActYear($ee,$YEAR))); 
          if ($s && ($all || (( $s['YearState'] >= 2) && ($ets >1 || ($ets==1 && Access('Participant','Other',$s)))))) $imps[$useimp?$s['Importance']:0][] = $s; }; }; };
  }
}

function ImpCount($imps) {
  $c = 0;
  foreach ($imps as $imp) foreach($imp as $s) $c++;
  return $c;
}

function Gallery($id,$embed=0) {
  include_once("ImageLib.php");
  $PS = (isset($_GET['S']) ? $_GET['S'] : 50);

  if (is_numeric($id)) {
    $Gal = db_get('Galleries',"id='$id'");
  } else {
    $Gal = db_get('Galleries',"SN='$id'");
  }
  if (!$Gal) Error_Page("Gallery $id does not exist");

  $name = $Gal['SN'];
  if (!$embed) dohead($name, '/files/gallery.css');

  echo "<h2 class=maintitle>$name</h2><p>";
  echo "Click on any slide to start a Slide Show with that slide.<p>\n";

  if ($Gal['Credits']) {
    echo '<h2 class="subtitle">Credits</h2>';
    echo "Photos by: " . $Gal['Credits'] . "<p>";
  }


  $Imgs = Get_Gallery_Photos($Gal['id']);
  $ImgCount = count($Imgs);

  if ($ImgCount > $PS) {
    $Page = (isset($_GET['p']) ? $_GET['p'] : 1);
    $lastP = ceil($ImgCount/$PS);
    if ($Page > $lastP) $Page = $lastP;
    echo "<div class=gallerypage>Page : ";
    $bl = "<a href=ShowGallery.php?g=$id";
    if ($PS != 50) $bl .= "&S=$PS";
    $bl .= "&p=";
    echo $bl . "1>First</a> ";
    if ($Page > 1) echo $bl . ($Page-1) . ">Prev</a> ";
    for ($p = 1; $p <= $lastP; $p++) { 
      if ($p == $Page) {
        echo "$p ";
      } else {
        echo $bl . $p . ">$p</a> ";
      }
    }
    if ($Page != $lastP) echo $bl . ($Page+1) . ">Next</a> ";
    echo $bl . $lastP . ">Last</a></div><p>";
    $first = ($Page-1)*$PS;
    $last = $first+$PS;
  } else {
    $first = 0;
    $last = $PS;
  }

  echo '<div id=galleryflex>';


  $count = 0;
  if ($Imgs) {
    foreach ($Imgs as $img) {
      if ($count >= $first && $count < $last) {
      
        echo "<div class=galleryarticle><a href=/int/SlideShow.php?g=$id&s=$count><img class=galleryarticleimg src=\"" . $img['File'] . "\"></a>";
        if ($img['Caption']) echo "<div class=gallerycaption> " . $img['Caption'] . "</div>";
        echo "</div>\n";
      }
      $count++;
    }
  } else {
    echo "<h2 class=Err>Sorry that Gallery is empty</h2>\n";
  }

  if ($Gal['Credits']) {
//    echo '</div><h2 class="subtitle">Credits</h2>';
    echo "<p></div>Photos by: " . $Gal['Credits'] . "<p>";
  }

  if (!$embed) dotail();
}

/*
function ShowArticle($a,$mxat=0) {
  if (!isset($a['Scale'])) $s['Scale'] = 1;
  echo "<div class=mnfloatleft>";
  $host = "https://" . $_SERVER{'HTTP_HOST'};
  if ($a['Image']) {
    $wi = $a['ImageWidth'];
    $ht = $a['ImageHeight'];
    if ($wi > ($ht * 1.1)) {
      $fmt  = ($wi > ($ht * 2))?'b':'l';
    } else {
      $fmt  = ($ht > ($wi * 1.1))?'p':'s';
    }
  } else {
    $fmt = 't';
  } // fmt t=txt, l=ls, p=pt, s=sq, b=ban
  $mnmx = ($side['Importance'] >= $mnat?'maxi':'mini');
  $id = AlphaNumeric($a['SN']);
  echo "<div class=$mnmx" . "_$fmt id=$id>";
  echo "<a href=/int/$link?sidenum=" . $side['SideId'] . ">";
  if ($mnmx != 'maxi' && $a['Image']) echo "<div class=mnmximgwrap><img class=mnmximg src='" . $a['Image'] ."'></div>";
  echo "<div class=mnmxttl style='font-size:" . (27+$side['Importance']*3) . "px'>" . $a['SN'] . "</div>";
  if ($mnmx == 'maxi' && $a['Image']) echo "<div class=mnmximgwrap><img class=mnmximg src='" . $a['Image'] ."'></div>";
  echo "</a><div class=mnmxtxt>" . $a['Text'] . "</div>";
  echo "</div></div>\n";
}

function ShowArticles() {
  global $db,$SHOWYEAR,$Coming_Type;
  // Specials data gathering - DANCE
    $ans = $db->query("SELECT count(*) AS Total FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$SHOWYEAR AND y.Coming=" . $Coming_Type['Y']);
    $Dsc = 0;
//    if ($ans) $Dsc= ($ans->fetch_assoc())['Total'];

    $ans = $db->query("SELECT s.Photo,s.SideId FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$SHOWYEAR AND s.Photo!='' AND y.Coming=" . 
                    $Coming_Type['Y'] . " ORDER BY RAND() LIMIT 1");
    if ($ans) {
      $DMany = $ans->fetch_assoc();
      $DPhoto = $DMany['Photo'];
    } else {
      $DPhoto = "/images/Hobos-Morris-2016.jpg";
    }
    $ans = $db->query("SELECT s.* FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$SHOWYEAR AND s.Photo!='' AND y.Coming=" . 
                            $Coming_Type['Y'] . " AND s.Importance!=0 ORDER BY RAND() LIMIT 2");
    if (!$ans) {
      $ans = $db->query("SELECT s.* FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$SHOWYEAR AND s.Photo!='' AND y.Coming=" . 
                        $Coming_Type['Y'] . " ORDER BY RAND() LIMIT 2");
    }
    if ($ans) {
      $Dstuff = $ans->fetch_assoc();
      if ($Dstuff['SideId'] == $p['SideId']) $Dstuff = $ans->fetch_assoc();
    }

    // Music stuff
    $ans = $db->query("SELECT count(*) AS Total FROM Sides s, ActYear y WHERE s.SideId=y.SideId AND y.Year=$SHOWYEAR AND y.YearState>0 ");
    $Msc = 0;
//    if ($ans) $Msc= ($ans->fetch_assoc())['Total'];

    $ans = $db->query("SELECT s.Photo,s.SideId FROM Sides s, ActYear y WHERE s.IsAnAct=1 AND s.SideId=y.SideId AND y.Year=$SHOWYEAR AND s.Photo!='' AND y.YearState>0 " . 
                        " ORDER BY RAND() LIMIT 1");
    if ($ans) {
      $MMany = $ans->fetch_assoc();
      $MPhoto = $MMany['Photo'];
    } else {
      $MPhoto = "/images/Hobos-Morris-2016.jpg";
    }

    $ans = $db->query("SELECT s.* FROM Sides s, ActYear y WHERE s.IsAnAct=1 AND s.SideId=y.SideId AND y.Year=$SHOWYEAR AND s.Photo!='' AND y.YearState>0 " . 
                        " AND s.Importance!=0 ORDER BY RAND() LIMIT 2");
    if (!$ans) {
      $ans = $db->query("SELECT s.* FROM Sides s, ActYear y WHERE s.IsAnAct=1 AND s.SideId=y.SideId AND y.Year=$SHOWYEAR AND s.Photo!='' AND y.YearState>0 " . 
                        " ORDER BY RAND() LIMIT 2");
    }
    if ($ans) {
      $Mstuff = $ans->fetch_assoc();
      if ($Mstuff['SideId'] == $p['SideId']) $Mstuff = $ans->fetch_assoc();
    }

  // Main Code
  include_once("NewsLib.php");
  $Arts = Get_All_Articles();
//  echo "<div class=FlexContain>";
  $Imps = array();
  foreach($Arts as $a) $Imps[$a['Importance']][] = $a;

  $ks = array_keys($Imps);
  sort($ks);        
  foreach ( array_reverse($ks) as $imp) {
    shuffle($Imps[$imp]);
    foreach ($Imps[$imp] as $a) {
      if (substr($a['SN'],0,1) == '@') { // Special
        switch ($a['SN']) {
        case '@Dance_Imp':
          ShowArticle(array(
                'SN'=>$Dstuff['SN'], 
                'Link'=>('int/ShowDance.php?sidenum=' . $Dstuff['SideId']),
                'Text'=>$Dstuff['Description'],
                'Image'=>$Dstuff['Photo'],
                'ImageWidth'=>$Dstuff['ImageWidth'],
                'ImageHeight'=>$Dstuff['ImageHeight'],
                'Importance'=>$imp
                ));
          break;
        case '@Dance_Many':
          ShowArticle(array(
                'SN'=>"Dancing in $SHOWYEAR",
                'Link'=>"/LineUpDance.php",
                'Text'=>"$Dsc Dance teams have already confirmed for $SHOWYEAR.  Many of your favourite teams and some new faces.\n",
                'Image'=>$DPhoto,
                'ImageWidth'=>$DMany['ImageWidth'],
                'ImageHeight'=>$DMany['ImageHeight'],
                'Importance'=>$imp
                ));
          break;
        case '@Music_Imp':
          ShowArticle(array(
                'SN'=>$Mstuff['SN'], 
                'Link'=>('int/ShowMusic.php?sidenum=' . $Mstuff['SideId']),
                'Text'=>$Mstuff['Description'],
                'Image'=>$Mstuff['Photo'],
                'ImageWidth'=>$Mstuff['ImageWidth'],
                'ImageHeight'=>$Mstuff['ImageHeight'],
                'Importance'=>$imp
                ));
          break;
        case '@Music_Many':
          ShowArticle(array(
                'SN'=>"Music in $SHOWYEAR",
                'Link'=>"/LineUpMusic.php",
                'Text'=>"$Msc Music acts have already confirmed for $SHOWYEAR.\n",
                'Image'=>$MPhoto,
                'ImageWidth'=>$MMany['ImageWidth'],
                'ImageHeight'=>$MMany['ImageHeight'],
                'Importance'=>$imp
                ));
          break;
        case '@Other_Imp':
          break;
        case '@Other_Many':
          break;
        case '@Select' :
          break;
        }
      } else {
        ShowArticle($a);
      }
    }
  }
  echo "</div><div class=content style='clear:both;'>";
}
*/

function Expand_Special(&$Art) {
  static $Dstuff,$DMany,$DPhoto,$Dsc,$Mstuff,$MMany,$MPhoto,$Msc;
  global $db,$SHOWYEAR,$Coming_Type;
  if (!$Dstuff) {
  // Specials data gathering - DANCE
    $ans = $db->query("SELECT count(*) AS Total FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$SHOWYEAR AND y.Coming=" . $Coming_Type['Y']);
    $Dsc = 0;
    if ($ans) $Dsc= ($ans->fetch_assoc())['Total'];
    $ans = $db->query("SELECT s.Photo,s.SideId,s.ImageHeight,s.ImageWidth FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$SHOWYEAR AND s.Photo!='' AND y.Coming=" . 
                    $Coming_Type['Y'] . " ORDER BY RAND() LIMIT 1");
    if ($ans) {
      $DMany = $ans->fetch_assoc();
      $DPhoto = $DMany['Photo'];
    } else $DPhoto = "/images/Hobos-Morris-2016.jpg";
    $ans = $db->query("SELECT s.* FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$SHOWYEAR AND s.Photo!='' AND y.Coming=" . 
                            $Coming_Type['Y'] . " AND s.Importance!=0 ORDER BY RAND() LIMIT 2");
    if (!$ans) $ans = $db->query("SELECT s.* FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$SHOWYEAR AND s.Photo!='' AND y.Coming=" . 
                        $Coming_Type['Y'] . " ORDER BY RAND() LIMIT 2");
    if ($ans) {
      $Dstuff = $ans->fetch_assoc();
      if ($Dstuff['SideId'] == $DMany['SideId']) $Dstuff = $ans->fetch_assoc();
    }

    // Music stuff
    $ans = $db->query("SELECT count(*) AS Total FROM Sides s, ActYear y WHERE s.SideId=y.SideId AND y.Year=$SHOWYEAR AND y.YearState>0 ");
    $Msc = 0;
    if ($ans) $Msc= ($ans->fetch_assoc())['Total'];
    $ans = $db->query("SELECT s.Photo,s.SideId,s.ImageHeight,s.ImageWidth FROM Sides s, ActYear y WHERE s.IsAnAct=1 AND s.SideId=y.SideId AND y.Year=$SHOWYEAR AND " . 
                        "s.Photo!='' AND y.YearState>0 ORDER BY RAND() LIMIT 1");
    if ($ans) {
      $MMany = $ans->fetch_assoc();
      $MPhoto = $MMany['Photo'];
    } else $MPhoto = "/images/Hobos-Morris-2016.jpg";

    $ans = $db->query("SELECT s.* FROM Sides s, ActYear y WHERE s.IsAnAct=1 AND s.SideId=y.SideId AND y.Year=$SHOWYEAR AND s.Photo!='' AND y.YearState>0 " . 
                        " AND s.Importance!=0 ORDER BY RAND() LIMIT 2");
    if (!$ans) $ans = $db->query("SELECT s.* FROM Sides s, ActYear y WHERE s.IsAnAct=1 AND s.SideId=y.SideId AND y.Year=$SHOWYEAR AND s.Photo!='' AND y.YearState>0 " . 
                        " ORDER BY RAND() LIMIT 2");
    if ($ans) {
      $Mstuff = $ans->fetch_assoc();
      if ($Mstuff['SideId'] == $MMany['SideId']) $Mstuff = $ans->fetch_assoc();
    }
    // No Other yet
  }
  

  switch ($Art['SN']) {
  case '@Dance_Imp':
    $Art['SN'] = $Dstuff['SN'];
    $Art['Link'] = ('int/ShowDance.php?sidenum=' . $Dstuff['SideId']);
    $Art['Text'] = $Dstuff['Description'];
    $Art['Image'] = $Dstuff['Photo'];
    $Art['ImageWidth'] = (isset($Dstuff['ImageWidth'])?$Dstuff['ImageWidth']:100);
    $Art['ImageHeight'] = (isset($Dstuff['ImageHeight'])?$Dstuff['ImageHeight']:100);
    break;

  case '@Dance_Many':
    $Art['SN'] = "Dancing in $SHOWYEAR";
    $Art['Link'] = "/LineUpDance.php";
    $Art['Text'] = "$Dsc Dance teams have already confirmed for $SHOWYEAR.  Many of your favourite teams and some new faces.\n";
    $Art['Image'] = $DPhoto;
    $Art['ImageWidth'] = (isset($DMany['ImageWidth'])?$DMany['ImageWidth']:100);
    $Art['ImageHeight'] = (isset($DMany['ImageHeight'])?$DMany['ImageHeight']:100);
    break;

  case '@Music_Imp':
    $Art['SN'] = $Mstuff['SN'];
    $Art['Link'] = ('int/ShowMusic.php?sidenum=' . $Mstuff['SideId']);
    $Art['Text'] = $Mstuff['Description'];
    $Art['Image'] = $Mstuff['Photo'];
    $Art['ImageWidth'] = (isset($Mstuff['ImageWidth'])?$Mstuff['ImageWidth']:100);
    $Art['ImageHeight'] = (isset($Mstuff['ImageHeight'])?$Mstuff['ImageHeight']:100);
    break;

  case '@Music_Many':
    $Art['SN'] = "Music in $SHOWYEAR";
    $Art['Link'] = "/LineUpMusic.php";
    $Art['Text'] = "$Msc Music acts have already confirmed for $SHOWYEAR.\n";
    $Art['Image'] = $MPhoto;
    $Art['ImageWidth'] = (isset($MMany['ImageWidth'])?$MMany['ImageWidth']:100);
    $Art['ImageHeight'] = (isset($MMany['ImageHeight'])?$MMany['ImageHeight']:100);
    break;
    
  default:
    
  }
}

function Show_Articles_For($page='',$future=0) {
  if ($future == 0 && !Feature('UseArticles')) return;
  
  $Arts = Get_All_Articles(0,$page,$future);
//  var_dump($Arts);
  echo "<div id=ShowArt></div><p>";
  echo "<div id=OrigArt hidden>";
  foreach ($Arts as $i=>$Art) {
    $fmt = (isset($Art['Format'])?$Art['Format']:0);
    echo "<div id=Art$i data-format=$fmt class=\"Art ArtFormat$fmt\" ";
    
    if (substr($Art['SN'],0,1) == '@') { // Special
      Expand_Special($Art);  // Will Update content of Art
    }
    if (!$Art['Text'] && !$Art['Image'] && (!$Art['SN'] || $Art['HideTitle'])) {
      echo "hidden ></div>";
      continue; // No content...
    }
    echo ">";
    switch ($fmt) {
    case 0: // Large Image
    default:
      if ($Art['Link']) echo "<a href='" . $Art['Link'] . "'>";
      if (!$Art['HideTitle']) echo "<div class=\"ArtTitleL\" id=\"ArtTitle$i\">" . $Art['SN'] . "</div>";
      if ($Art['Image']) echo "<img id=\"ArtImg$i\" class=\"ArtImageL\" src=" . $Art['Image'] . " data-height=" . $Art['ImageHeight'] . " data-width=" . $Art['ImageWidth'] .">";
      if ($Art['Link']) echo "</a>";
      echo "<br><span class=\"ArtTextL\" id=\"ArtText$i\">" . $Art['Text'];
      break;
          
    case 1: // Small Image (to left of title and text)
      if ($Art['Link']) echo "<a href='" . $Art['Link'] . "'>";
      if ($Art['Image']) echo "<img id=\"ArtImg$i\" class=\"ArtImageS\" src=" . $Art['Image'] . " data-height=" . $Art['ImageHeight'] . " data-width=" . $Art['ImageWidth'] . ">";
      if (!$Art['HideTitle']) echo "<div class=\"ArtTitleS\" id=\"ArtTitle$i\">" . $Art['SN'] . "</div>";
      if ($Art['Link']) echo "</a>";
      echo "<span class=\"ArtTextS\" id=\"ArtText$i\">" . $Art['Text'];
      break;
          
    case 2: // Text Only
      if ($Art['Link']) echo "<a href='" . $Art['Link'] . "'>";
      if (!$Art['HideTitle']) echo "<div class=\"ArtTitleT\" id=\"ArtTitle$i\">" . $Art['SN'] . "</div>";
      if ($Art['Link']) echo "</a>";
      echo "<span class=\"ArtTextT\" id=\"ArtText$i\">" . $Art['Text'];
      break;
      
    case 3: // Banner Image
      if ($Art['Link']) echo "<a href='" . $Art['Link'] . "'>";
      if (!$Art['HideTitle']) echo "<div class=\"ArtTitleBI\" id=\"ArtTitle$i\">" . $Art['SN'] . "</div>";
      if ($Art['Image']) echo "<img id=\"ArtImg$i\" class=\"ArtImageBI\" src=" . $Art['Image'] . " data-height=" . $Art['ImageHeight'] . " data-width=" . $Art['ImageWidth'] .">";
      if ($Art['Link']) echo "</a>";
      echo "<span class=\"ArtTextBI\" id=\"ArtText$i\">" . $Art['Text'];
      break;
              
    case 4: // Banner Text
      if ($Art['Link']) echo "<a href='" . $Art['Link'] . "'>";
      if (!$Art['HideTitle']) echo "<div class=\"ArtTitleBT\" id=\"ArtTitle$i\">" . $Art['SN'] . "</div>";
      if ($Art['Link']) echo "</a>";
      echo "<span class=\"ArtTextBT\" id=\"ArtText$i\">" . $Art['Text'];
      break;
      
    case 5: // Fixed Image large box has ratio of 550:500
      if ($Art['Link']) echo "<a href='" . $Art['Link'] . "'>";
      if (!$Art['HideTitle']) echo "<div class=\"ArtTitleF\" id=\"ArtTitle$i\">" . $Art['SN'] . "</div>";
      if ($Art['Image']) echo "<img class=\"ArtImageF\" id=\"ArtImg$i\" src=" . $Art['Image'] . " data-height=" . $Art['ImageHeight'] . " data-width=" . $Art['ImageWidth'] .">";
      if ($Art['Link']) echo "</a>";
      echo "<br><span class=\"ArtTextF\" id=\"ArtText$i\">" . $Art['Text'];
      break;
    }
    echo "</span></div><br clear=all>\n";          
  }
  echo "</div>";
  echo "\n";
}



