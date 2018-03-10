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

function Gallery($title,$dir,$credit='') {
  dohead($title, '/files/gallery.css');

  echo '<h2 class=maintitle>$title</h2>';
  echo '<div id=galleryflex>';

  if ($handle = opendir("../$dir")) {
    while (false !== ($entry = readdir($handle))) {
      if (preg_match('/^\./',$entry)) continue;
      echo "<div class=galleryarticle><a href=/$dir/$entry><img class=galleryarticleimg src='/$dir/$entry'></a></div>\n";
    }
    closedir($handle);
  }

  if ($credit) {
    echo '</div><h2 class="subtitle">Credits</h2>';
    echo "<p>Photos by: $credit<p>";
  }

  dotail();
}

function ShowArticle($a,$mxat=0) {
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
  $mnmx = ($a['Importance'] >= $mnat?'BigBox':'SmallBox');
  $id = AlphaNumeric($a['SName']);
  echo "<div class=$mnmx><a href=$host/" . $a['Link'] . ">";
  echo "<div class=BBtitle style='font-size:" . (27+$a['Importance']*3) . "px'>" . $a['SName'] . "</div>";
  if ($a['Image']) echo "<img class=BBImg src='" . $a['Image'] ."'>";
  echo "</a><div class=BBtxt>" . $a['Text'] . "</div>";
  echo "</div>\n";
}

function ShowArticles() {
  global $db,$THISYEAR,$Coming_Type;
  // Specials data gathering - DANCE
    $ans = $db->query("SELECT count(*) AS Total FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$THISYEAR AND y.Coming=" . $Coming_Type['Y']);
    $Dsc = 0;
    if ($ans) {
      $r = $ans->fetch_assoc();
      $Dsc=$r['Total'];
    }

    $ans = $db->query("SELECT s.Photo,s.SideId FROM Sides s, SideYear y WHERE s.SideId=y.SideId AND y.Year=$THISYEAR AND s.Photo!='' AND y.Coming=" . 
		    $Coming_Type['Y'] . " ORDER BY RAND() LIMIT 1");
    if ($ans) {
      $p = $ans->fetch_assoc();
      $DPhoto = $p['Photo'];
      $DMany = $p;
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
    if ($ans) {
      $r = $ans->fetch_assoc();
      $Msc=$r['Total'];
    }

    $ans = $db->query("SELECT s.Photo,s.SideId FROM Sides s, ActYear y WHERE s.IsAnAct=1 AND s.SideId=y.SideId AND y.Year=$THISYEAR AND s.Photo!='' AND y.YearState>0 " . 
			" ORDER BY RAND() LIMIT 1");
    if ($ans) {
      $p = $ans->fetch_assoc();
      $MPhoto = $p['Photo'];
      $MMany = $p;
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
  echo "<div class=FlexContain>";
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
	}
      } else {
	ShowArticle($a);
      }
    }
  }
  echo "</div><div class=content style='clear:both;'>";
}

