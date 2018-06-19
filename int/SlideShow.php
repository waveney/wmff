<?php
  include_once("fest.php");

  include_once("ImageLib.php");

  $id = $_GET['g'];
  $start = (isset($_GET['s'])?$_GET['s']:0);
// Get gallery and start (default 0)
// Setup show and start it
  if (strlen($id)<10) {
    if (is_numeric($id)) {
      $Gal = db_get('Galleries',"id='$id'");
    } else {
      $Gal = db_get('Galleries',"SName='$id'");
    }
  }
  if (!$Gal) Error_Page("Gallery $id does not exist");

  $name = $Gal['SName'];
  dohead($name, '/css/jquery.bxslider.css','/js/jquery.bxslider.min.js');
  echo "<h2 class=maintitle>$name</h2><p>";
  echo "<center style='height:95vh !important;'>\n";
  echo "<ul class=bxslider>\n";

  $Imgs = Get_Gallery_Photos($Gal['id']);
  $count = 0;
  if ($Imgs) {
    foreach ($Imgs as $img) {
      echo "<li><img src='" . $img['File'] . "'" ;
      if ($img['Caption']) echo " title='" . $img['Caption'] . "'";
      echo " width=100% style='object-fit:contain; max-height:95vh !important;' >\n";
      $count++;
    }
  } else {
    echo "<h2 class=Err>Sorry that Gallery is empty</h2>\n";
  }
  echo "</ul></center>\n";

  echo '<script>
  $(document).ready(function(){
    $(".bxslider").bxSlider({
	adaptiveHeight: true,
	keyboardEnabled: true,
	autoControls: true,
	auto: true,
	pagerType: "short",
';
  echo "startSlide: $start,\n";
  echo '
    });
  });

</script>';

  dotail();
?>
