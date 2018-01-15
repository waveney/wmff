<?php

// Displaying utilities for public site

function formatminimax(&$side,$link,$mnat=1) {
  echo "<div class=mnfloatleft>";
  if ($side['Photo']) {
    if (preg_match('/^\/(.*)/',$side['Photo'],$mtch)) {
      $stuff = getimagesize($mtch[1]);
    } else {
      $stuff = getimagesize($side['Photo']);
    }
    $wi = $stuff[0];
    $ht = $stuff[1];
    if ($wi > ($ht * 1.1)) {
      $fmt  = ($wi > ($ht * 2))?'b':'l';
    } else {
      $fmt  = ($ht > ($wi * 1.1))?'p':'s';
    }
  } else {
    $fmt = 't';
  } // fmt t=txt, l=ls, p=pt, s=sq, b=ban
  $mnmx = ($side['Importance'] > $mnat?'maxi':'mini');

  echo "<div class=$mnmx" . "_$fmt>";
  echo "<a href=/int/$link?sidenum=" . $side['SideId'] . ">";
  if ($mnmx != 'maxi' && $side['Photo']) echo "<div class=mnmximgwrap><img class=mnmximg src='" . $side['Photo'] ."'></div>";
  echo "<div class=mnmxttl style='font-size:" . (27+$side['Importance']*3) . "px'>" . $side['Name'] . "</div>";
  if ($mnmx == 'maxi' && $side['Photo']) echo "<div class=mnmximgwrap><img class=mnmximg src='" . $side['Photo'] ."'></div>";
  echo "</a><div class=mnmxtxt>" . $side['Description'] . "</div>";
  echo "</div></div>\n";
}

?>
