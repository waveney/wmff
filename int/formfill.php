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
          $memb['SName'] = $Value;
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
    // SHOULD never get here...
  }
?>

