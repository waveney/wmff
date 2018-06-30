<?php
  include_once("fest.php");
  A_Check('Committee','Venues');

  dostaffhead("Add/Change Venue");
  include_once("ProgLib.php");
  include_once("MapLib.php");

  Set_Venue_Help();

  echo "<div class='content'><h2>Add/Edit Venues</h2>\n";
  echo "<form method=post action='AddVenue.php'>\n";
  if (isset($_POST{'VenueId'})) { /* Response to update button */
    $vid = $_POST{'VenueId'};
    if ($vid > 0) {                                 // existing Venue
      $Venue = Get_Venue($vid);
      Update_db_post('Venues',$Venue);
    } else { /* New */
      $proc = 1;
      if (!isset($_POST['SName'])) {
        echo "<h2 class=ERR>NO NAME GIVEN</h2>\n";
        $proc = 0;
      }
      $vid = Insert_db_post('Venues',$Venue,$proc);
    }
    Update_MapPoints(); 
  } elseif (isset($_GET{'v'})) {
    $vid = $_GET{'v'};
    $Venue = Get_Venue($vid);
  } elseif (isset($_GET['Copy'])) {
    $cvid = $_GET['Copy'];
    $Venue = Get_Venue($cvid);
    $vid = -1;
  } else {
    $Venue = array();
    $vid = -1;
  }

  $RealSites = Get_Real_Venues(0);
  $VirtSites = Get_Virtual_Venues();

  if (isset($Venue['Image']) && $Venue['Image']) {
    echo "<div class=floatright><img src=" . $Venue['Image'] . " width=400><br>";
    if (isset($Venue['Caption']) && $Venue['Caption']) echo $Venue['Caption'] . "<br>"; 
    if (isset($Venue['Image2']) && ($Venue['Image2'])) {
      echo "<img src=" . $Venue['Image2'] . " width=400><br>";
      if (isset($Venue['Caption2']) && $Venue['Caption2']) echo $Venue['Caption2'] . "<br>"; 
    }    
    echo "</div>";
  }
  echo "<table style='width:70%' border>\n";
    if (isset($vid) && $vid > 0) {
      echo "<tr><td>Venue Id:<td>";
      echo $vid . fm_hidden('VenueId',$vid);
    } else {
      echo fm_hidden('VenueId',-1);
    }
    echo "<tr>" . fm_text('Short Name', $Venue,'ShortName');
    echo "<tr>" . fm_text('SName',$Venue,'SName',3);
    echo "<tr>" . fm_text('Address',$Venue,'Address',3);
    echo          fm_text('Post Code',$Venue,'PostCode',1);
    echo "<tr>" . fm_textarea('Description',$Venue,'Description',5,2);
    echo "<tr>" . fm_textarea('Directions Extra',$Venue,'DirectionsExtra',5,2);
    echo "<tr>" . fm_text('Lat',$Venue,'Lat',1);
    echo          fm_text('Long',$Venue,'Lng',1);
    echo          fm_text('MapImp',$Venue,'MapImp',1);
    echo "<tr>" . fm_text('Image',$Venue,'Image',1);
    echo          fm_text('Caption',$Venue,'Caption',1);
    echo          fm_text('Image2',$Venue,'Image2',1);
    echo          fm_text('Caption2',$Venue,'Caption2',1);
    echo "<tr>" . fm_text('Website',$Venue,'Website',1);
    echo     "<td>" . fm_checkbox('Supress Free',$Venue,'SupressFree');
    echo "<tr><td>" . fm_checkbox('Bar',$Venue,'Bar') . "<td>" . fm_checkbox('Food',$Venue,'Food') . fm_text('Food/Bar text',$Venue,'BarFoodText') . "\n";
    echo "<tr>" . fm_text('Notes',$Venue,'Notes',3);
    echo "<td colspan=2>Do NOT use if:" . fm_select($RealSites,$Venue,'DontUseIf',1) . " In use";
    echo "<tr><td>Status<td>" . fm_select($Venue_Status,$Venue,'Status');
    echo "<td>" . fm_checkbox('Dance Setup Overlap',$Venue,'SetupOverlap');
    echo "<td>" . fm_checkbox('Is Virtual',$Venue,'IsVirtual');
    echo "<td colspan=2>Part of:" . fm_select($VirtSites,$Venue,'PartVirt',1);
    echo "<tr><td>Venue For:<td colspan=2>" . fm_checkbox('Dance',$Venue,'Dance');
    echo fm_checkbox('Music',$Venue,'Music');
    echo fm_checkbox('Children',$Venue,'Child');
    echo fm_checkbox('Craft',$Venue,'Craft');
    echo fm_checkbox('Other',$Venue,'Other');
    echo "<td colspan=2>" . fm_checkbox('Ignore Multiple Use Warning',$Venue,'AllowMult');
    echo "<tr><td>" . fm_simpletext("Dance Importance",$Venue,'DanceImportance','size=4');
    echo "<td>" . fm_simpletext("Music Importance",$Venue,'MusicImportance','size=4');
    echo "<td>" . fm_simpletext("Other Importance",$Venue,'OtherImportance','size=4');
    echo "<tr><td colspan=2>Treat as Minor for Dance on:" . help('Minor') . "<td>" . fm_checkbox('Sat',$Venue,'MinorSat') ;
    echo "<td>" . fm_checkbox('Sun',$Venue,'MinorSun');
    echo "<tr><td>Surfaces:<td>" . fm_select($Surfaces,$Venue,'SurfaceType1',1);
    echo "<td>" . fm_select($Surfaces,$Venue,'SurfaceType2',1) . "\n";
    echo "<tr>" . fm_text('Dance Rider',$Venue,'DanceRider',5);
    echo "<tr>" . fm_text('Music Rider',$Venue,'MusicRider',5);
    echo "<tr>" . fm_text('Other Rider',$Venue,'OtherRider',5);
//    echo "<tr><td>" . fm_checkbox("Parking",$Venue,'Parking');
    echo "</table>\n";

  if ($vid > 0) {
    echo "<Center><input type=Submit name='Update' value='Update'>\n";
    echo "</center>\n";
  } else { 
    echo "<Center><input type=Submit name=Create value='Create'></center>\n";
  }
  echo "</form>\n";
  echo "<h2><a href=VenueList.php>List Venues</a> , \n";
    echo "<a href=AddVenue.php>Add Another Venue</a>, \n";
    echo "<a href=AddVenue.php?Copy=$vid>Copy To Another Venue</a>, \n";
    echo "<a href=VenueShow.php?v=$vid&Mode=1>Show Venue</a></h2>";

  dotail();
?>

