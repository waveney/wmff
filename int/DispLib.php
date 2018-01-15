<?php

// Displaying utilities for public site

function formatminimax(&$side,$link) {
  echo "<div class=mnfloatleft>";
  if ($side['Photo']) {
    if (preg_match('/^\/(.*)/',$side['Photo'],$mtch)) {
      $stuff = getimagesize($mtch[1]);
    } else {
      $stuff = getimagesize($side['Photo']);
    }
    $wi = $stuff[0];
    $ht = $stuff[1];
    if ($wi > ($ht * 1.05)) {
      $fmt  = ($wi > ($ht * 2))?'b':'l';
    } else {
      $fmt  = ($ht > ($wi * 1.05))?'p':'s';
    }
  } else {
    $fmt = 't';
  } // fmt t=txt, l=ls, p=pt, s=sq, b=ban

  echo "<div class=" . ($side['Importance']?"maxi_$fmt":"mini_$fmt") . ">";
  echo "<a href=/int/$link?sidenum=" . $side['SideId'] . ">";
  if (!$side['Importance'] && $side['Photo']) echo "<img class=mnmximg src='" . $side['Photo'] ."'>";
  echo "<h2 class=mnmxttl style='font-size:" . (27+$side['Importance']*3) . "px'>" . $side['Name'] . "</h2><br clear=all>";
  if ($side['Importance'] && $side['Photo']) echo "<img class=mnmximg src='" . $side['Photo'] ."'>";
  echo "</a><div class=mnmxtxt>" . $side['Description'] . "</div>";
  echo "</div></div>\n";
}

?>
