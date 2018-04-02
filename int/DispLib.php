<?php

// Displaying utilities for public site

function formatminimax(&$side,$link,$mnat=2) {
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
  $id = AlphaNumeric($side['SName']);
  echo "<div class=$mnmx" . "_$fmt id=$id>";
  echo "<a href=/int/$link?sidenum=" . $side['SideId'] . ">";
  if ($mnmx != 'maxi' && $side['Photo']) echo "<div class=mnmximgwrap><img class=mnmximg src='" . $side['Photo'] ."'></div>";
  echo "<div class=mnmxttl style='font-size:" . (27+$side['Importance']*3) . "px'>" . $side['SName'] . "</div>";
  if ($mnmx == 'maxi' && $side['Photo']) echo "<div class=mnmximgwrap><img class=mnmximg src='" . $side['Photo'] ."'></div>";
  echo "</a><div class=mnmxtxt>" . $side['Description'] . "</div>";
  echo "</div></div>\n";
}

// Check ET to see if imps should be found
function Get_Imps(&$e,&$imps,$clear=1,$all=0) {
  global $Event_Types_Full;
  $ETs = Get_Event_Types(1);
  $ets = $ETs[$e['Type']]['State']; 
  $useimp = ($Event_Types_Full[$e['Type']]['UseImp'] && ($e['BigEvent']==0));
  if ($clear) $imps = array();
  for($i=1;$i<5;$i++) {
    if (isset($e["Side$i"])) { if ($ee = $e["Side$i"])  { 
	$s = Get_Side($ee);  
	if ($s && ($all || $ets >1 || ($ets==1 && Access('Participant','Side',$s)))) $imps[$useimp?$s['Importance']:0][] = $s; }; };
    if (isset($e["Act$i"]))  { if ($ee = $e["Act$i"])   { 
	$s = Get_Side($ee); 
	if ($s && ($all || $ets >1 || ($ets==1 && Access('Participant','Act',$s)))) $imps[$useimp?$s['Importance']:0][] = $s; }; };
    if (isset($e["Other$i"])){ if ($ee = $e["Other$i"]) { 
	$s = Get_Side($ee);  
	if ($s && ($all || $ets >1 || ($ets==1 && Access('Participant','Other',$s)))) $imps[$useimp?$s['Importance']:0][] = $s; }; };
  }
}

function ImpCount($imps) {
  $c = 0;
  foreach ($imps as $imp) foreach($imp as $s) $c++;
  return $c;
}

function Gallery($id,$embed=0) {
  if (!$embed) dohead($title, '/files/gallery.css');
  include_once("ImageLib.php");

  if (is_numeric($id)) {
    $Gal = db_get('Galleries',"id='$id'");
  } else {
    $Gal = db_get('Galleries',"SName='$id'");
  }
  if (!$Gal) Error_Page("Gallery $id does not exist");

  $name = $Gal['SName'];
  echo "<h2 class=maintitle>$name</h2><p>";
  echo '<div id=galleryflex>';

  $Imgs = Get_Gallery_Photos($Gal['id']);
  if ($Imgs) {
    foreach ($Imgs as $img) {
      echo "<div class=galleryarticle><a href='/" . $img['File'] . "'><img class=galleryarticleimg src='" . $img['File'] . "'></a>";
      if ($img['Caption']) echo "<div class=gallerycaption> " . $img['Caption'] . "</div>";
      echo "</div>\n";
    }
  } else {
    echo "<h2 class=Err>Sorry that Gallery is empty</h2>\n";
  }

  if ($Gal['Credits']) {
    echo '</div><h2 class="subtitle">Credits</h2>';
    echo "<p>Photos by: " . $Gal['Credits'] . "<p>";
  }

  if (!$embed) dotail();
}

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
  $id = AlphaNumeric($a['SName']);
  echo "<div class=$mnmx" . "_$fmt id=$id>";
  echo "<a href=/int/$link?sidenum=" . $side['SideId'] . ">";
  if ($mnmx != 'maxi' && $a['Image']) echo "<div class=mnmximgwrap><img class=mnmximg src='" . $a['Image'] ."'></div>";
  echo "<div class=mnmxttl style='font-size:" . (27+$side['Importance']*3) . "px'>" . $a['SName'] . "</div>";
  if ($mnmx == 'maxi' && $a['Image']) echo "<div class=mnmximgwrap><img class=mnmximg src='" . $a['Image'] ."'></div>";
  echo "</a><div class=mnmxtxt>" . $a['Text'] . "</div>";
  echo "</div></div>\n";
}

function ShowArticles() {
  global $db,$THISYEAR,$Coming_Type;
  // Specials data gathering - DANCE
    $ans = $db->query("SELECT count(*) AS Total FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$THISYEAR AND y.Coming=" . $Coming_Type['Y']);
    $Dsc = 0;
//    if ($ans) $Dsc= ($ans->fetch_assoc())['Total'];

    $ans = $db->query("SELECT s.Photo,s.SideId FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$THISYEAR AND s.Photo!='' AND y.Coming=" . 
		    $Coming_Type['Y'] . " ORDER BY RAND() LIMIT 1");
    if ($ans) {
      $DMany = $ans->fetch_assoc();
      $DPhoto = $DMany['Photo'];
    } else {
      $DPhoto = "/images/Hobos-Morris-2016.jpg";
    }
    $ans = $db->query("SELECT s.* FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$THISYEAR AND s.Photo!='' AND y.Coming=" . 
			    $Coming_Type['Y'] . " AND s.Importance!=0 ORDER BY RAND() LIMIT 2");
    if (!$ans) {
      $ans = $db->query("SELECT s.* FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$THISYEAR AND s.Photo!='' AND y.Coming=" . 
			$Coming_Type['Y'] . " ORDER BY RAND() LIMIT 2");
    }
    if ($ans) {
      $Dstuff = $ans->fetch_assoc();
      if ($Dstuff['SideId'] == $p['SideId']) $Dstuff = $ans->fetch_assoc();
    }

    // Music stuff
    $ans = $db->query("SELECT count(*) AS Total FROM Sides s, ActYear y WHERE s.SideId=y.SideId AND y.Year=$THISYEAR AND y.YearState>0 ");
    $Msc = 0;
//    if ($ans) $Msc= ($ans->fetch_assoc())['Total'];

    $ans = $db->query("SELECT s.Photo,s.SideId FROM Sides s, ActYear y WHERE s.IsAnAct=1 AND s.SideId=y.SideId AND y.Year=$THISYEAR AND s.Photo!='' AND y.YearState>0 " . 
			" ORDER BY RAND() LIMIT 1");
    if ($ans) {
      $MMany = $ans->fetch_assoc();
      $MPhoto = $MMany['Photo'];
    } else {
      $MPhoto = "/images/Hobos-Morris-2016.jpg";
    }

    $ans = $db->query("SELECT s.* FROM Sides s, ActYear y WHERE s.IsAnAct=1 AND s.SideId=y.SideId AND y.Year=$THISYEAR AND s.Photo!='' AND y.YearState>0 " . 
			" AND s.Importance!=0 ORDER BY RAND() LIMIT 2");
    if (!$ans) {
      $ans = $db->query("SELECT s.* FROM Sides s, ActYear y WHERE s.IsAnAct=1 AND s.SideId=y.SideId AND y.Year=$THISYEAR AND s.Photo!='' AND y.YearState>0 " . 
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
      if (substr($a['SName'],0,1) == '@') { // Special
	switch ($a['SName']) {
	case '@Dance_Imp':
	  ShowArticle(array(
		'SName'=>$Dstuff['SName'], 
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
		'SName'=>"Dancing in $THISYEAR",
		'Link'=>"$host/LineUpDance.php",
		'Text'=>"$Dsc Dance teams have already confirmed for $THISYEAR.  Many of your favourite teams and some new faces.\n",
		'Image'=>$DPhoto,
		'ImageWidth'=>$DMany['ImageWidth'],
		'ImageHeight'=>$DMany['ImageHeight'],
		'Importance'=>$imp
		));
	  break;
	case '@Music_Imp':
	  ShowArticle(array(
		'SName'=>$Mstuff['SName'], 
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
		'SName'=>"Music in $THISYEAR",
		'Link'=>"$host/LineUpMusic.php",
		'Text'=>"$Msc Music acts have already confirmed for $THISYEAR.\n",
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

