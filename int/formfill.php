<?php
  include_once("fest.php");
  
  $field = $_POST['F'];
  $Value = $_POST['V'];
  $id    = $_POST['I'];
  $type  = $_POST['D'];

//  var_dump($_POST);  
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
    } else if (preg_match('/(Olap\D*)(\d*)/',$field,$match)) { // Overlaps are a special case
      $Exist = Get_Overlaps_For($id);
      $O = $StO = (isset($Exist[$match[2]]) ? $Exist[$match[2]] : ['Sid1'=>$id,'Cat2'=>0]);
      $Other = ($O['Sid1'] == $id)?'Sid2':'Sid1'; 
      $OtherCat =  ($O['Sid1'] == $id)?'Cat2':'Cat1';
      $O[ ['OlapType' => 'OType', 
           'OlapMajor' => 'Major', 
           'OlapActive' => 'Active', 
           'OlapDays' => 'Days', 
           'OlapSide' => $Other, 
           'OlapAct' => $Other, 
           'OlapOther' => $Other,
           'OlapCat' => $OtherCat][$match[1]] ] = $Value;
      if ((isset($O['id'])) && $O['id']) {
        Update_db('Overlaps',$StO,$O); 
      } else {
        Insert_db('Overlaps',$O); 
      }
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
      echo Put_SideYear($Perfy);
      exit;
    }
    include_once("MusicLib.php");
    $Perfy = Get_ActYear($id);
    if (isset($Perfy[$field])) {
      $Perfy[$field] = $Value;
      echo Put_ActYear($Perfy);
      exit;
    }
    if (!$Perfy) {
      $flds = table_fields('ActYear');
      if (isset($flds[$field])) {
        $Perfy = Default_AY($id);
        $Perfy[$field] = $Value;
        echo Put_ActYear($Perfy);
        exit;
      }
    }

    // SHOULD never get here...

        
  case 'Trader': 
    include_once("TradeLib.php");
    $Trad = Get_Trader($id);
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
  }
?>

