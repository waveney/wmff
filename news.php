<?php
  include_once('int/fest.php');
  include_once("int/NewsLib.php");
  include_once("files/facebook.php");
  
  dohead("News");

  echo "<div id=flex>";
  $items = Get_All_News(0);
  if ($items) { foreach ($items as $n) News_Item($n,0,1); }

  echo "<div class=mini>";
  echo "<h2 class=subtitle>Facebook News</h2>\n";
  echo '<div class="fb-page" data-href="https://www.facebook.com/WimborneFolk" data-tabs="timeline" data-width="500" data-height="800" data-small-header="true" data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="true"><blockquote cite="https://www.facebook.com/WimborneFolk" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/WimborneFolk">Wimborne Minster Folk Festival</a></blockquote></div>';
  echo "</div>";

  dotail();
?>
