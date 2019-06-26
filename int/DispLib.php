<?php

// Displaying utilities for public site

function formatminimax(&$side,$link,$mnat=2,$sdisp=1) {
  global $YEAR,$PerfTypes;
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
  $Imp = $side['Importance'];
  if ($side['DiffImportance']) {
    $imp = 0;
    foreach($PerfTypes as $pt=>$pd) if ($side[$pd[0]] && $imp < $side[$pd[2] . 'Importance'] ) $imp = $side[$pd[2] . 'Importance'];
  }
  
  $mnmx = ($Imp >= $mnat?'maxi':'mini');
  $id = AlphaNumeric($side['SN']);
  echo "<div class=$mnmx" . "_$fmt id=$id>";
  echo "<a href=/int/$link?id=" . $side['SideId'] . "&Y=$YEAR>";
  if ($mnmx != 'maxi' && $side['Photo']) echo "<div class=mnmximgwrap><img class=mnmximg src='" . $side['Photo'] ."'></div>";
  echo "<div class=mnmxttl style='font-size:" . (24+$Imp*3) . "px'>" . $side['SN'] . "</div>";
  if ($mnmx == 'maxi' && $side['Photo']) echo "<div class=mnmximgwrap><img class=mnmximg src='" . $side['Photo'] ."'></div>";
  echo "</a>";
  if ($sdisp) echo "<div class=mnmxtxt>" . $side['Description'] . "</div>";
  echo "</div></div>\n";
}

function formatLineups(&$perfs,$link,&$Sizes,$sdisp=1) {
// Link, if (text) Title, pic, text else Pic Title
// If size = small then fit 5, else fit 3 - if fit change br
// Float boxes with min of X and Max of Y
  global $YEAR;
  $LastSize = -1;
  
  foreach ($perfs as $perf) {
    $Imp = $perf['EffectiveImportance'];
    $Id = $perf['SideId'];
    if ($Sizes[$Imp] != $LastSize) {
      if ($LastSize >=0) echo "</div><br clear=all>";
      $LastSize = $Sizes[$Imp];
      echo "<div class=LineupFit" . $LastSize . "Wrapper>";
    }
    echo "<div class='LineupFit$LastSize LineUpBase' onmouseover=AddLineUpHighlight($Id) onmouseout=RemoveLineUpHighlight($Id) id=LineUp$Id>";
    echo "<a href=/int/$link?sidenum=$Id&Y=$YEAR>";
    $Photo = $perf['Photo'];
    if (!$Photo) $Photo = '/images/icons/user2.png';
    if ($sdisp) {
      echo "<div class=LineUpFitTitle style='font-size:" . (18+$Imp) . "px'>" . $perf['SN'] . "</div>";
      echo "<img src=$Photo>";
      echo "<div class=LineUptxt>" . $perf['Description'] . "</div>";
       
    } else {
      echo "<img src=$Photo>";
      echo "<br><div class=LineUpFitTitle style='font-size:" . (18+$Imp*3) . "px'>" . $perf['SN'] . "</div></a>";
    }
    echo "</div>";
  }
  echo "</div><br clear=all>";
}

// Check ET to see if imps should be found
function Get_Imps(&$e,&$imps,$clear=1,$all=0) {
  global $Event_Types_Full,$YEAR,$PerfTypes;
  $ETs = Get_Event_Types(1);
  $ets = $ETs[$e['Type']]['State']; 
  $useimp = ($Event_Types_Full[$e['Type']]['UseImp'] && ($e['BigEvent']==0));
  $now=time();
  if ($clear) $imps = array();
  for($i=1;$i<5;$i++) {
    if (isset($e["Side$i"]) && $e["Side$i"]) { 
      if ($ee = $e["Side$i"])  { 
        $si = Get_Side($ee);
        if ($si) {
          $y = Get_SideYear($ee,$YEAR);
          $s = array_merge($si, munge_array($y)); 
          if ($s && ($all || ((( $s['Coming'] == 2) || ($s['YearState'] >= 2)) && ($ets >1 || ($ets==1 && Access('Participant','Side',$s))) && $s['ReleaseDate'] < $now))) {
            if (!$useimp) {
              $imps[0][] = $s;
            } elseif ($s['DiffImportance']) {
              $iimp = 0;
              foreach($PerfTypes as $pt=>$pd) if ($s[$pd[0]] && $iimp < $s[$pd[2] . 'Importance']) $iimp = $s[$pd[2] . 'Importance'];
              $imps[$iimp][] = $s;
            } else {
              $imps[$s['Importance']][] = $s;
            }
          }
        } 
      }
    }
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

  if (!$Gal) {
//    echo "About to call Error_Page<p>";
    Error_Page("Gallery $id does not exist");
  }

  $name = $Gal['SN'];
  if (!$embed) {
    $Banner = 1;
    if (isset($Gal['Banner'])) $Banner = $Gal['Banner'];
    dohead($name, ['/files/gallery.css'],$Banner);
  }

  echo "<h2 class=maintitle>$name</h2><p>";
  echo "Click on any slide to start a Slide Show with that slide.<p>\n";

  if ($Gal['Credits']) {
    echo '<h2 class="subtitle">Credits</h2>';
    echo "Photos by: " . $Gal['Credits'] . "<p>";
  }


  $Imgs = Get_Gallery_Photos($Gal['id']);
  $ImgCount = count($Imgs);


  $PStr = "";
  if ($ImgCount > $PS) {
    $Page = (isset($_GET['p']) ? $_GET['p'] : 1);
    $lastP = ceil($ImgCount/$PS);
    if ($Page > $lastP) $Page = $lastP;
    $PStr .= "<div class=gallerypage>Page : ";
    $bl = "<a href=ShowGallery?g=$id";
    if ($PS != 50) $bl .= "&S=$PS";
    $bl .= "&p=";
    $PStr .= $bl . "1>First</a> ";
    if ($Page > 1) $PStr .= $bl . ($Page-1) . ">Prev</a> ";
    for ($p = 1; $p <= $lastP; $p++) { 
      if ($p == $Page) {
        $PStr .= "$p ";
      } else {
        $PStr .= $bl . $p . ">$p</a> ";
      }
    }
    if ($Page != $lastP) $PStr .= $bl . ($Page+1) . ">Next</a> ";
    $PStr .= $bl . $lastP . ">Last</a></div><p>";
    $first = ($Page-1)*$PS;
    $last = $first+$PS;
  } else {
    $first = 0;
    $last = $PS;
  }
  $PStr .= "<p>\n";
  
  echo $PStr;
  
  echo '<div id=galleryflex>';


  $count = 0;
  if ($Imgs) {
    foreach ($Imgs as $img) {
      if ($count >= $first && $count < $last) {
      
        echo "<div class=galleryarticle><a href=/int/SlideShow?g=$id&s=$count><img class=galleryarticleimg src=\"" . $img['File'] . "\"></a>";
        if ($img['Caption']) echo "<div class=gallerycaption> " . $img['Caption'] . "</div>";
        echo "</div>\n";
      }
      $count++;
    }
  } else {
    echo "<h2 class=Err>Sorry that Gallery is empty</h2>\n";
  }

  echo "</div>" . $PStr;
  
  if ($Gal['Credits']) {
    echo "<p>Photos by: " . $Gal['Credits'] . "<p>";
  }

  if (!$embed) dotail();
}

function Count_Perf_Type($type,$Year=0) {
  global $YEAR,$db,$Coming_Type;
  $now = time();
  if ($Year == 0) $Year=$YEAR;
  $ans = $db->query("SELECT count(*) AS Total FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$Year AND s.$type=1 AND ( y.Coming=" . $Coming_Type['Y'] . 
                    " OR y.YearState>2 ) AND y.ReleaseDate<$now");
  $Dsc = 0;
  if ($ans) {
    $res = $ans->fetch_assoc();
    $Dsc = $res['Total'];
  }
  return $Dsc;
}


function Expand_Special(&$Art) {
  global $db,$YEAR,$Coming_Type;
  static $Shown = [];
  $now = time();
  
  $words = explode(' ',$Art['SN']);

  switch ($words[0]) {
  case '@Dance_Imp':
    $ans = $db->query("SELECT s.* FROM Sides s, SideYear y WHERE s.IsASide=1 AND s.SideId=y.SideId AND y.Year=$YEAR AND s.Photo!='' AND y.Coming=" . $Coming_Type['Y'] .
                        " AND ((s.DiffImportance=0 AND s.Importance!=0) OR (s.DiffImportance=1 AND s.DanceImportance!=0)) AND y.ReleaseDate<$now ORDER BY RAND() LIMIT 5");
    if (!$ans) { $Art = []; return; }  
  
    while ( $Dstuff = $ans->fetch_assoc()) {
      if (in_array($Dstuff['SideId'],$Shown)) continue;
      $Shown[] = $Dstuff['SideId'];

      $Art['SN'] = $Dstuff['SN'];
      $Art['Link'] = ('/int/ShowPerf?id=' . $Dstuff['SideId']);
      $Art['Text'] = $Dstuff['Description'];
      $Art['Image'] = $Dstuff['Photo'];
      $Art['ImageWidth'] = (isset($Dstuff['ImageWidth'])?$Dstuff['ImageWidth']:100);
      $Art['ImageHeight'] = (isset($Dstuff['ImageHeight'])?$Dstuff['ImageHeight']:100);
      return;
    }
    $Art = [];
    break;

  case '@Dance_Many':
    $Art['SN'] = "Dancing in $YEAR";
    $Art['Link'] = "/LineUpDance";

    $ans = $db->query("SELECT count(*) AS Total FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$YEAR AND y.Coming=" . 
           $Coming_Type['Y'] . " AND y.ReleaseDate<$now");
    $Dsc = 0;
    if ($ans) {
      $res = $ans->fetch_assoc();
      $Dsc = $res['Total'];
    }
    
    $Art['Text'] = "$Dsc Dance team" . ($Dsc == 1?" has":"s have") . " already confirmed for $YEAR.  Many of your favourite teams and some new faces.";

    $ans = $db->query("SELECT s.Photo,s.SideId,s.ImageHeight,s.ImageWidth,s.SN FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$YEAR AND s.Photo!='' AND y.Coming=" . 
                    $Coming_Type['Y'] . " AND y.ReleaseDate<$now ORDER BY RAND() LIMIT 10");

    if (!$ans) return; 
    while ( $DMany = $ans->fetch_assoc()) {
      if (in_array($DMany['SideId'],$Shown)) continue;
      $Shown[] = $DMany['SideId'];

      $Art['Text'] .= "  Including <a href=/int/ShowPerf?id=" . $DMany['SideId'] . ">" . $DMany['SN'] . "</a>";
      $Art['Image'] = $DMany['Photo'];
      $Art['ImageWidth'] = (isset($DMany['ImageWidth'])?$DMany['ImageWidth']:100);
      $Art['ImageHeight'] = (isset($DMany['ImageHeight'])?$DMany['ImageHeight']:100);
      return;
    }
    break;

  case '@Music_Imp':  
    $ans = $db->query("SELECT s.* FROM Sides s, SideYear y WHERE s.IsAnAct=1 AND s.SideId=y.SideId AND y.Year=$YEAR AND s.Photo!='' AND y.YearState>0 " . 
                        " AND ((s.DiffImportance=0 AND s.Importance!=0) OR (s.DiffImportance=1 AND s.MusicImportance!=0)) AND y.ReleaseDate<$now ORDER BY RAND() LIMIT 5");
    if (!$ans) { echo "EEK"; $Art = []; return; }  
  
    while ( $Mstuff = $ans->fetch_assoc()) {
      if (in_array($Mstuff['SideId'],$Shown)) continue;
      $Shown[] = $Mstuff['SideId'];

      $Art['SN'] = $Mstuff['SN'];
      $Art['Link'] = ('/int/ShowPerf?id=' . $Mstuff['SideId']);
      $Art['Text'] = $Mstuff['Description'];
      $Art['Image'] = $Mstuff['Photo'];
      $Art['ImageWidth'] = (isset($Mstuff['ImageWidth'])?$Mstuff['ImageWidth']:100);
      $Art['ImageHeight'] = (isset($Mstuff['ImageHeight'])?$Mstuff['ImageHeight']:100);
      return;
    }
    $Art = [];
    break;

  case '@Music_Many':
    $Art['SN'] = "Music in $YEAR";
    $Art['Link'] = "/LineUpMusic";

    $ans = $db->query("SELECT count(*) AS Total FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$YEAR AND y.YearState>0 AND y.ReleaseDate<$now");
    $Msc = 0;
    if ($ans) {
      $res = $ans->fetch_assoc();
      $Msc = $res['Total'];
    }

    $Art['Text'] = "$Msc Music act" . ($Msc == 1?" has":"s have") . " already confirmed for $YEAR.";
    
    $ans = $db->query("SELECT s.Photo,s.SideId,s.ImageHeight,s.ImageWidth,s.SN FROM Sides s, SideYear y WHERE s.IsAnAct=1 AND s.SideId=y.SideId AND y.Year=$YEAR AND " . 
                        "s.Photo!='' AND y.YearState>0 AND y.ReleaseDate<$now ORDER BY RAND() LIMIT 10");
    if (!$ans) return;
      
    while ( $MMany = $ans->fetch_assoc()) {
      if (in_array($MMany['SideId'],$Shown)) continue;
      $Shown[] = $MMany['SideId'];

      $Art['Text'] .= "  Including <a href=/int/ShowPerf?id=" . $MMany['SideId'] . ">" . $MMany['SN'] . "</a>";
      $Art['Image'] = $MMany['Photo'];
      $Art['ImageWidth'] = (isset($MMany['ImageWidth'])?$MMany['ImageWidth']:100);
      $Art['ImageHeight'] = (isset($MMany['ImageHeight'])?$MMany['ImageHeight']:100);
      return;
    }
    break;

  case '@Perf': // Just this performer
    $id = $words[1];
    if (in_array($id,$Shown)) {
      $Art = [];
      return;
    }
    $Shown [] = $id;
    $Perf = Get_Side($id);
    $Art['SN'] = $Perf['SN'];
    $Art['Link'] = '/int/ShowPerf?id=' . $Perf['SideId'];
    $Art['Text'] = $Perf['Description'];
    $Art['Image'] = $Perf['Photo'];
    $Art['ImageWidth'] = (isset($Perf['ImageWidth'])?$Perf['ImageWidth']:100);
    $Art['ImageHeight'] = (isset($Perf['ImageHeight'])?$Perf['ImageHeight']:100);
    break;
    
  case '@Event' : // Just this Event
  
    break;
    
  default:
    
  }
}

function Show_Articles_For($page='',$future=0,$datas='400,700,20,3') {
  if ($future == 0 && !Feature('UseArticles')) return;
  include_once("DanceLib.php");
  
  $Arts = Get_All_Articles(0,$page,$future);
//  var_dump($Arts);
  echo "<div id=ShowArt data-settings='$datas'></div><p>";
  echo "<div id=OrigArt hidden>";
  foreach ($Arts as $i=>$Art) {
    $fmt = (isset($Art['Format'])?$Art['Format']:0);
    echo "<div id=Art$i data-format=$fmt class=\"Art ArtFormat$fmt\" ";
    
    if (substr($Art['SN'],0,1) == '@') { // Special
      Expand_Special($Art);  // Will Update content of Art
    }
    if (count($Art)==0 || (!$Art['Text'] && !$Art['Image'] && (!$Art['SN'] || $Art['HideTitle']))) {
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
      echo "<br><span class=\"ArtTextL\" id=\"ArtText$i\">" . $Art['Text'] . "</span>";
      break;
          
    case 1: // Small Image (to left of title and text)
      if ($Art['Link']) echo "<a href='" . $Art['Link'] . "'>";
      if ($Art['Image']) echo "<img id=\"ArtImg$i\" class=\"ArtImageS\" src=" . $Art['Image'] . " data-height=" . $Art['ImageHeight'] . " data-width=" . $Art['ImageWidth'] . ">";
      if (!$Art['HideTitle']) echo "<div class=\"ArtTitleS\" id=\"ArtTitle$i\">" . $Art['SN'] . "</div>";
      if ($Art['Link']) echo "</a>";
      echo "<span class=\"ArtTextS\" id=\"ArtText$i\">" . $Art['Text'] . "</span>";
      break;
          
    case 2: // Text Only
      if ($Art['Link']) echo "<a href='" . $Art['Link'] . "'>";
      if (!$Art['HideTitle']) echo "<div class=\"ArtTitleT\" id=\"ArtTitle$i\">" . $Art['SN'] . "</div>";
      if ($Art['Link']) echo "</a>";
      echo "<span class=\"ArtTextT\" id=\"ArtText$i\">" . $Art['Text'] . "</span>";
      break;
      
    case 3: // Banner Image
      if ($Art['Link']) echo "<a href='" . $Art['Link'] . "'>";
      if (!$Art['HideTitle']) echo "<div class=\"ArtTitleBI\" id=\"ArtTitle$i\">" . $Art['SN'] . "</div>";
      if ($Art['Image']) echo "<img id=\"ArtImg$i\" class=\"ArtImageBI\" src=" . $Art['Image'] . " data-height=" . $Art['ImageHeight'] . " data-width=" . $Art['ImageWidth'] .">";
      if ($Art['Link']) echo "</a>";
      echo "<span class=\"ArtTextBI\" id=\"ArtText$i\">" . $Art['Text'] . "</span>";
      break;
              
    case 4: // Banner Text
      if ($Art['Link']) echo "<a href='" . $Art['Link'] . "'>";
      if (!$Art['HideTitle']) echo "<div class=\"ArtTitleBT\" id=\"ArtTitle$i\">" . $Art['SN'] . "</div>";
      if ($Art['Link']) echo "</a>";
      echo "<span class=\"ArtTextBT\" id=\"ArtText$i\">" . $Art['Text'] . "</span>";
      break;
      
    case 5: // Fixed Image large box has ratio of 550:500
      if ($Art['Link']) echo "<a href='" . $Art['Link'] . "'>";
      if (!$Art['HideTitle']) echo "<div class=\"ArtTitleF\" id=\"ArtTitle$i\">" . $Art['SN'] . "</div><br>";
      if ($Art['Image']) echo "<img class=\"ArtImageF\" id=\"ArtImg$i\" src=" . $Art['Image'] . " data-height=" . $Art['ImageHeight'] . " data-width=" . $Art['ImageWidth'] .">";
      if ($Art['Link']) echo "</a><br style='height:0' clear=\"all\">";
      echo "<div class=\"ArtTextF\" id=\"ArtText$i\">" . $Art['Text'] . "</div>";
      break;
    }
    echo "</div><br clear=all>\n";          
  }
  echo "</div>";
  echo "\n";
}



