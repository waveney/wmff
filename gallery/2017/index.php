<?php
  include_once("int/fest.php");

  dohead("2017 Photo Gallery", '/files/gallery.css');

  echo '<h2 class="maintitle">2017 Photo Gallery</h2>';
  echo '<div id="galleryflex">';

  $dir = 'images/gallery/2017';
  if ($handle = opendir("../../$dir")) {
    while (false !== ($entry = readdir($handle))) {
      if (preg_match('/^\./',$entry)) continue;
      echo "<div class=galleryarticle><a href=/$dir/$entry><img class=galleryarticleimg src=/$dir/$entry></a></div>\n";
    }
    closedir($handle);
  }

  echo '</div><h2 class="subtitle">Credits</h2>';
  echo '<p>Photos by: Andy Sherlock, Chris Lyons, Jo Elkington, Jon Vincent, Helen Jones, Phoebe Reeks and Stephen Jones.<p>';
  dotail();
?>
