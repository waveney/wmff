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
  global $EventTypeData;
  $ETs = Get_Event_Types(1);
  $ets = $ETs[$e['Type']]['State']; 
  $useimp = ($EventTypeData[$e['Type']]['UseImp'] && ($e['BigEvent']==0));
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

?>
