<?php
  include_once("fest.php");
  $field = $_POST['F'];
  $Value = $_POST['V'];
  $id    = $_POST['I'];
  $type  = $_POST['D'];

//  var_dump($_POST);  
// Special returns @x@ changes id to x, #x# sets feild to x, !x! important error message
  switch ($type) {
  case 'Performer':
    include_once("DanceLib.php");
    if (preg_match('/BandMember(\d*):(\d*)/',$field,$match)) { // Band Members are a special case
      include_once("MusicLib.php");
      if ($match[2]) { // Existing entry
        $CurBand = Get_Band($id);
        $memb = $CurBand[$match[1]];
        if ($Value) {
          $memb['SN'] = $Value;
          Put_BandMember($memb);
        } else {
          db_delete('BandMembers',$memb['BandMemId']);
          echo "@BandMember" . $match[1] . ":0@";
        }
        exit;
      }
      $CurBand = Get_Band($id);
      $memb = Add_BandMember($id,$Value);
      echo "@BandMember" . $match[1] . ":$memb@";
      exit;
    } else if (preg_match('/^Olap.*/',$field)) { // Overlaps are a special case
      $Exist = Get_Overlaps_For($id);
      if (preg_match('/(Olap\D+)(\d+)/',$field,$match)) {
        $OFld = $match[1];
        $ORule = $match[2];
      } elseif (preg_match('/Olap(\d+)(\D*)/',$field,$match)) {
        $OFld = $match[2];
        $ORule = $match[1];
      } else { echo "Undefined Olap format"; exit();
      }
      
      $O = $StO = (isset($Exist[$ORule]) ? $Exist[$ORule] : ['Sid1'=>$id,'Cat2'=>0]);
      $Other = ($O['Sid1'] == $id)?'Sid2':'Sid1'; 
      $OtherCat =  ($O['Sid1'] == $id)?'Cat2':'Cat1';
      $O[ ['OlapType' => 'OType', 
           'OlapMajor' => 'Major', 
           'OlapActive' => 'Active', 
           'OlapDays' => 'Days', 
           'OlapSide' => $Other, 
           'OlapAct' => $Other, 
           'OlapOther' => $Other,
           'OlapCat' => $OtherCat,
           'Cat' => $OtherCat][$OFld] ] = $Value;

      if ((isset($O['id'])) && $O['id']) {
        Update_db('Overlaps',$StO,$O); 
      } else {
        Insert_db('Overlaps',$O); 
      }
    } else if (preg_match('/^Perf(\d+)_Side(\d+)/',$field,$match)) { // Overlaps are a special case
      $Exist = Get_Overlaps_For($id);
      $ORule = $match[2];
      $O = $StO = (isset($Exist[$ORule]) ? $Exist[$ORule] : ['Sid1'=>$id,'Cat2'=>0]);
      $Other = ($O['Sid1'] == $id)?'Sid2':'Sid1'; 
      $O[$Other] = $Value;
      if ((isset($O['id'])) && $O['id']) {
        Update_db('Overlaps',$StO,$O); 
      } else {
        Insert_db('Overlaps',$O); 
      }
    } else if ($field == 'Photo' && (preg_match('/^\s*https?:\/\//i',$Value ))) { // Remote Photos are a special case - look for localisation
      $Perf = Get_Side($id);
      include_once("ImageLib.php");
      preg_match('/\.(jpg|jpeg|gif|png)/i',$Value,$mtch);

      if ($mtch) {
        $sfx = $mtch[1];
        $loc = "/images/Sides/$id.$sfx";
        $res = Localise_Image($Value,$Perf,$loc);
        Put_Side($Perf);
        if ($res) {
          echo "!$res!";
        } else {
          echo "#$loc#PerfThumb#$loc#";
        }
        exit;
      };
      echo "1, Not a recognisable image";
      exit;
    } else if ($field == 'ReleaseDate') {
      include_once("DateTime.php");
      $Value = Date_BestGuess($Value);
    } else if (preg_match('/(Sat|Sun)(Arrive|Depart)/',$field)) {
      include_once("DateTime.php");
      $Value = Time_BestGuess($Value);
    }
    
    // else general cases
    
    $Perf = Get_Side($id);
    if (isset($Perf[$field])) {
      $Perf[$field] = $Value;
      echo Put_Side($Perf);
      exit;
    }

    $Perfy = Get_SideYear($id);
      if (!$Perfy) {
        $flds = table_fields('SideYear');
        if (isset($flds[$field])) {
          $Perfy = Default_SY($id);
          $Perfy[$field] = $Value;
          echo Put_SideYear($Perfy);
          exit;
        }
      }
      if (isset($Perfy[$field])) {
        $Perfy[$field] = $Value;
//        var_dump($Perfy);
        echo Put_SideYear($Perfy);
        exit;
      }
    // SHOULD never get here...

        
  case 'Trader': 
    include_once("TradeLib.php");

    $Trad = Get_Trader($id);
    if ($field == 'Photo' && (preg_match('/^\s*https?:\/\//i',$Value ))) { // Remote Photos are a special case - look for localisation
      include_once("ImageLib.php");
      preg_match('/\.(jpg|jpeg|gif|png)/i',$Value,$mtch);
      if ($mtch) {
        $sfx = $mtch[1];
        $loc = "/images/Trade/$id.$sfx";
        $res = Localise_Image($Value,$Trad,$loc);
        Put_Trader($Trad);
        if ($res) {
          echo "!$res!";
        } else {
          echo "#$loc#TradThumb#$loc#";
        }
        exit;
      };
      echo "1, Not a recognisable image";
      exit;
    }


    if (isset($Trad[$field])) {
      $Trad[$field] = $Value;
      echo Put_Trader($Trad);
      exit;
    }   
    $Trady = Get_Trade_Year($id);
    if (isset($Trady[$field])) {
      $Trady[$field] = $Value;
      echo Put_Trade_Year($Trady);
      exit;
    }
    if (!$Trady) {
      $flds = table_fields('TradeYear');
      if (isset($flds[$field])) {
        $Trady = Default_Trade($id);
        $Trady[$field] = $Value;
        echo Put_Trade_Year($Trady);
        exit;
      }
    }
    
    // SHOULD never get here...    
    exit;  
    
  case 'Event':
    include_once("ProgLib.php");
    $Event = Get_Event($id);

    if (preg_match('/^(Start|End|SlotEnd|DoorsOpen)$/',$field)) {
      include_once("DateTime.php");
      $Value = Time_BestGuess($Value);
    } else if (preg_match('/^(Setup|Duration)$/',$field)) {
      include_once("DateTime.php");
      $Value = Time_BestGuess($Value,1);
//    } else if (preg_match('/PerfType\d+/',$field,$res)) {
//      $field = $res[1];
    } else if (preg_match('/Perf\d+_(Side\d+)/',$field,$res)) {
      $field = $res[1];
    }
    
//    echo "Field=$field Val=$Value<br>";
    if (isset($Event[$field])) { // General case
      $Event[$field] = $Value;
      echo Put_Event($Event);
      exit;
    }

    // SHOULD never get here...    
    exit;  
     
  }
?>

