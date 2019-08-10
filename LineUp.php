<?php
  include_once ("int/fest.php");

  $T = 'Dance';
  
  if (isset($_GET['T'])) $T = $_GET['T'];
  if (strlen($T) > 12 || preg_match('/\W/',$T)) $T = 'Dance';  

  dohead("$T Line-up",[],1,'T');

  set_ShowYear();
  include_once("int/ProgLib.php");
  include_once("int/DispLib.php");
  include_once("int/DanceLib.php");
  include_once("int/MusicLib.php");

  global $db,$Coming_Type,$YEAR,$PLANYEAR,$Book_State,$EType_States;
  
  $now = time();
  $Sizes = [5,4,3,2,2,1,1];
  $ShortDesc = 1;
  switch ($T) {
  case 'Dance':
    $ET = Get_Event_Type_For("Dancing");
    if ($YEAR < $PLANYEAR) {
      echo "In $YEAR, These Dance teams were in Wimborne.  Click on the name or photograph to find out more and where they were dancing.<p>\n" .
                          "<b><a href=/int/ShowDanceProg?Cond=1&Pub=1&Y=$YEAR>Dance Programme for $YEAR</a></b><p>\n";
    } else {
//      echo "In " . ($YEAR-1) . " we had over " . Count_Perf_Type('IsASide',$YEAR-1) . " teams performing, for $YEAR we already have " .
//             Count_Perf_Type('IsASide',$YEAR) . " confirmed and lots more to come.<p>";
//      echo "This year we have " . Count_Perf_Type('IsASide',$YEAR) . " great folk dance teams for you to enjoy.<p>";
  
      if (Feature('DanceComp')) echo "We will also be having a <a href=int/ShowArticles?w=NWDanceComp>Competiton for the best North West Morris Team</a>.<p>";
  
      echo "<a href=/int/ShowArticles?w=DanceStyles>Find out more about the Dance Styles</a><p>";
    
//      echo "Click on the name of a team, or their photograph to find out more about them and where they are dancing.<p>\n";
      if ($ET['State'] >=3 ) echo "<b><a href=/int/ShowDanceProg?Cond=1&Pub=1&Y=$YEAR>" . $EType_States[$ET['State']] . " Dance Programme for $YEAR</a></b><p>\n";
    }
    $SideQ = $db->query("SELECT s.*, y.*, IF(s.DiffImportance=1,s.DanceImportance,s.Importance) AS EffectiveImportance " .
             "FROM Sides AS s, SideYear AS y WHERE s.SideId=y.SideId AND y.year=$YEAR AND y.Coming=" . $Coming_Type['Y'] . 
             " AND s.IsASide=1 AND y.ReleaseDate<$now ORDER BY EffectiveImportance DESC, s.RelOrder DESC, s.SN");
    $Sizes = [3,3,3,2,2,1,1];
    $ShortDesc = 0;
    break;
    
  case 'Music':
    $ET = Get_Event_Type_For("Music");
    if ($YEAR < $PLANYEAR) {
      echo "In $YEAR, These Acts were in Wimborne.  Click on the name or photograph to find out more and where they performed.<p>\n";
    } else {
      echo "Click on the name of a Act, or their photograph to find out more about them and where they are performing.<p>\n";
    }

    $SideQ = $db->query("SELECT s.*, y.*, IF(s.DiffImportance=1,s.MusicImportance,s.Importance) AS EffectiveImportance FROM Sides AS s, SideYear AS y " .
           "WHERE s.SideId=y.SideId AND y.year=$YEAR AND y.YearState>=" . $Book_State['Booking'] . 
           " AND s.IsAnAct=1 AND y.ReleaseDate<$now ORDER BY EffectiveImportance DESC, s.RelOrder DESC, s.SN");

    $ShortDesc = 0;
    break;

  case 'Comedy':
    $ET = Get_Event_Type_For("Comedy");
    if ($YEAR < $PLANYEAR) {
      echo "In $YEAR, These Acts were in Wimborne.  Click on the name or photograph to find out more and where they performed.<p>\n";
    } else {
//      echo "Click on the name of a Act, or their photograph to find out more about them and where they are performing.<p>\n";
    }

    $SideQ = $db->query("SELECT s.*, y.*, IF(s.DiffImportance=1,s.ComedyImportance,s.Importance) AS EffectiveImportance  FROM Sides AS s, SideYear AS y " .
           "WHERE s.SideId=y.SideId AND y.year=$YEAR AND y.YearState>=" . $Book_State['Booking'] . 
           " AND s.IsFunny=1 AND y.ReleaseDate<$now ORDER BY EffectiveImportance DESC, s.RelOrder DESC, s.SN");

    $ShortDesc = 0;
    break;

  
  case 'Family':
    $ET['FirstYear'] = 2019; // FUDGE TODO make better
    if ($YEAR < $PLANYEAR) {
      echo "In $YEAR, These Children's Entertainers were in Wimborne.  Click on the name or photograph to find out more and where they performed.<p>\n";
    } else {
      echo "Click on the name of a Children's Entertainer, or their photograph to find out more about them and where they are performing.<p>\n";
    }

    $SideQ = $db->query("SELECT s.*, y.*, IF(s.DiffImportance=1,s.FamilyImportance,s.Importance) AS EffectiveImportance  FROM Sides AS s, SideYear AS y " .
           "WHERE s.SideId=y.SideId AND y.year=$YEAR AND y.YearState>=" . $Book_State['Booking'] . 
           " AND s.IsFamily=1 AND y.ReleaseDate<$now ORDER BY EffectiveImportance DESC, s.RelOrder DESC, s.SN");
    break;

  
  case 'Other':
    $ET['FirstYear'] = 2019; // FUDGE TODO make better
    echo "These are performers who don't fit into any of the other categories.<p>";
    if ($YEAR < $PLANYEAR) {
      echo "In $YEAR, These other performers were in Wimborne.  Click on the name or photograph to find out more and where they performed.<p>\n";
    } else {
      echo "Click on the name of a performer, or their photograph to find out more about them and where they are performing.<p>\n";
    }

    $SideQ = $db->query("SELECT s.*, y.*, IF(s.DiffImportance=1,s.OtherImportance,s.Importance) AS EffectiveImportance  FROM Sides AS s, SideYear AS y " .
           "WHERE s.SideId=y.SideId AND y.year=$YEAR AND y.YearState>=" . $Book_State['Booking'] . 
           " AND s.IsOther=1 AND y.ReleaseDate<$now ORDER BY EffectiveImportance DESC, s.RelOrder DESC, s.SN");

    break;
    
  default:
    Error_Page('No line up selected');
  }
  

  $Slist = [];
  if ($SideQ) while($side = $SideQ->fetch_assoc()) $Slist[] = $side;
  formatLineups($Slist,'ShowPerf',$Sizes,$ShortDesc);
  
  echo "<div style='clear:both;'>";
  $Prev = $YEAR-1;
  if ($Prev >= $ET['FirstYear']) {
    if ($T == 'Dance') echo "<b><a href=/int/ShowDanceProg?Cond=1&Pub=1&Y=$Prev>Complete Dance Programme for $Prev</a>, ";
    echo "<br clear=all><a href=/LineUp?t=$T&Y=$Prev>$T Line Up $Prev</a></b><p>";
  }

  dotail();
?>

