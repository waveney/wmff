<?php

include_once('SignupLib.php');
function SetAccessKey(&$Data,$table,$id='id') {
  global $db;
  if ($Data['AccessKey']) return;
  $Data['AccessKey'] = $k = rand_string(40);
  $res = $db->query("UPDATE $table SET AccessKey='$k' WHERE $id=" . $Data[$id]);
}

function DanceLinks($Addr) {
  global $db;
  $res = $db->query("SELECT * FROM Sides WHERE Email='$Addr' AND IsASide=1 AND SideStatus=0");
  if ($res) while ($row = $res->fetch_assoc()) {
    SetAccessKey($row,'Sides','SideId');
    $ans[] = ['Dance Team','s',$row['SideId'],$row['SName'],$row['AccessKey']];
  }
  if (isset($ans)) return $ans;
}

function DanceAltLinks($Addr) {
  global $db;
  $res = $db->query("SELECT * FROM Sides WHERE AltEmail='$Addr' AND IsASide=1 AND SideStatus=0");
  if ($res) while ($row = $res->fetch_assoc()) {
    SetAccessKey($row,'Sides','SideId');
    $ans[] = ['Dance Team','sa',$row['SideId'],$row['SName'],$row['AccessKey'],$row['SName'],$row['Email']];
  }
  if (isset($ans)) return $ans;
}

function MusicLinks($Addr) {
  global $db;
  $res = $db->query("SELECT * FROM Sides WHERE Email='$Addr' AND IsASide=0 AND SideStatus=0");
  if ($res) while ($row = $res->fetch_assoc()) {
    SetAccessKey($row,'Sides','SideId');
    $ans[] = ['Act','a',$row['SideId'],$row['SName'],$row['AccessKey']];
  }
  if (isset($ans)) return $ans;
}

function MusicAltLinks($Addr) {
  global $db;
  $res = $db->query("SELECT * FROM Sides WHERE AltEmail='$Addr' AND IsASide=0 AND SideStatus=0");
  if ($res) while ($row = $res->fetch_assoc()) {
    SetAccessKey($row,'Sides','SideId');
    $ans[] = ['Act','aa',$row['SideId'],$row['SName'],$row['AccessKey'],$row['SName'],$row['Email']];
  }
  if (isset($ans)) return $ans;
}

function TradeLinks($Addr) {
  global $db;
  $res = $db->query("SELECT * FROM Trade WHERE Email='$Addr' AND Status=0 ");
  if ($res) while ($row = $res->fetch_assoc()) {
    SetAccessKey($row,'Trade','Tid');
    $ans[] = ['Trader','t',$row['Tid'],$row['SName'],$row['AccessKey']];
  }
  if (isset($ans)) return $ans;
}

function StewardLinks($Addr) {
  global $db;
  $res = $db->query("SELECT * FROM Stewards WHERE Email='$Addr' ");
  if ($res) while ($row = $res->fetch_assoc()) {
    SetAccessKey($row,'Stewards','id');
    $ans[] = ['Steward','w',$row['id'],$row['SName'],$row['AccessKey']];
  }
  if (isset($ans)) return $ans;
}

function SubmitLinks($Addr) {
  global $db,$SignUpActivities;
  $res = $db->query("SELECT * FROM SignUp WHERE Email='$Addr' ");
  if ($res) while ($row = $res->fetch_assoc()) {
    SetAccessKey($row,'SignUp','id');
    $ans[] = [$SignUpActivities[$row['Activity']],'u',$row['id'],$row['SName'],$row['AccessKey'],$row['Year']];
  }
  if (isset($ans)) return $ans;
}

function Links_Email($Addr,&$list) {
  $Mess = '';

  foreach($list as $e) {
    switch ($e[1]) {
    case 's':
    case 'a':
    case 'o':
    case 't':
      $Mess .= "You are recorded as the contact person for the " . $e[0] . " <b>" . $e[3] . "</b> you can update, amend and cancel your record " .
        "<a href=https://wimbornefolk.co.uk/int/Access.php?i=" . $e[2] . "&t=" . $e[1] . "&k=" . $e[4] . ">Here</a><p>";
      break;

    case 'w':
      $Mess .= "You are recorded as a " . $e[0] . " you can update, amend and cancel your record " .
        "<a href=https://wimbornefolk.co.uk/int/Access.php?i=" . $e[2] . "&t=" . $e[1] . "&k=" . $e[4] . ">Here</a><p>";
      break;

    case 'sa':
    case 'aa':
    case 'oa':
      $Mess .= "You are recorded as an alternative contact for the " . $e[0] . " <b>" . $e[3] . "</b> To change this please contact " . $e[5] . 
                " <a href=mailto: " . $e[6] . ">" . $e[6] . "</a><p>";
      break;

    case 'u':
      $Mess .= "You are recorded as the contact person for the " . $e[0] . " <b>" . $e[3] . "</b> in " . $e[5] . 
                ".  This is historic record with no user edits possible at the moment.<p>";
      break;
    }
  }

  if (file_exists("testing")) {
    SendEmail("Richard@wavwebs.com","WMFF records of $Addr",$Mess);
  } else {
    SendEmail($Addr,"WMFF data records of $Addr",$Mess);
  }

  $logf = fopen("LogFiles/DataCheck.txt","a");
  if( $logf) {
    fwrite($logf,"\n\nEmail to : " . $whoto . "\n\n" . $Mess);
    fclose($logf);
  }

}

function Data_Check_Emails($Addr) {
  $LinkList = [];

// Reject hacking attempts
  if (strlen($Addr) > 30) return;
  if (!strpos($Addr,'@')) return;
  if (strpos($Addr,"'")) return;

  $ads = DanceLinks($Addr);
  if ($ads) $LinkList = array_merge($LinkList,$ads);
  
  $ads = DanceAltLinks($Addr);
  if ($ads) $LinkList = array_merge($LinkList,$ads);
  
  $ads = MusicLinks($Addr);
  if ($ads) $LinkList = array_merge($LinkList,$ads);
  
  $ads = MusicAltLinks($Addr);
  if ($ads) $LinkList = array_merge($LinkList,$ads);
  
  $ads = TradeLinks($Addr);
  if ($ads) $LinkList = array_merge($LinkList,$ads);
  
  $ads = StewardLinks($Addr);
  if ($ads) $LinkList = array_merge($LinkList,$ads);
  
  $ads = SubmitLinks($Addr);
  if ($ads) $LinkList = array_merge($LinkList,$ads);
  
//var_dump($LinkList);

  if (count($LinkList)) {
    if (0 && access('SysAdmin')) {
      echo "Links found:<p><table border>\n";
      foreach ($LinkList as $a) echo "<tr><td>" . implode("<td>",$a) . "<td><a href=/int/Access.php?t=" . $a[1] . "&i=" . $a[2] . "&k=" . $a[4] . ">Use</a>\n";
      echo "</table><p>\n";
    } else {
      Links_Email($Addr,$LinkList);
    }
  } else {
    if (access('SysAdmin')) echo "No Links found";
  }
}

?>
