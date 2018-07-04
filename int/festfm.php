<?php

$HelpTable = 0;

function Set_Help_Table(&$table) {
  global $HelpTable;
  $HelpTable = $table;
}

function Add_Help_Table(&$table) {
  global $HelpTable;
  $HelpTable = array_merge($HelpTable,$table);
}

function help($fld) {
  global $HelpTable;
  if (!isset($HelpTable[$fld])) return;
  return " <img src=/images/icons/help.png id=Help4$fld title='" . $HelpTable[$fld] . "' style='margin-bottom:-4;'> ";
}

function htmlspec($data) {
  return utf8_decode(htmlspecialchars(utf8_encode(stripslashes($data)), ENT_COMPAT|ENT_SUBSTITUTE));
}

$ADDALL = '';

function fm_addall($txt) {
  global $ADDALL;
  $ADDALL = $txt;
}

function fm_textinput($field,$value='',$extra='') {
  global $ADDALL;
  $str = "<input type=text name=$field size=16 $extra $ADDALL";
  if ($value) $str .= " value=\"" . htmlspec($value) . '"';
  return $str  .">";
}

function fm_smalltext($Name,$field,$value,$chars=4,$extra='') {
  global $ADDALL;
  $str = "$Name " . help($field) . "<input type=text name=$field $extra size=$chars $ADDALL";
  $str .= " value=\"" . htmlspec($value) . '"';
  return $str  .">";
}

function fm_smalltext2($Name,&$data,$field,$chars=4,$extra='') {
  global $ADDALL;
  $str = "$Name " . help($field) . "<input type=text name=$field $extra size=$chars $ADDALL";
  if (isset($data[$field])) $str .= " value=\"" . htmlspec($value) . '"';
  return $str  .">";
}

function fm_text($Name,&$data,$field,$cols=1,$extra1='',$extra2='',$field2='') {
  global $ADDALL;
  if ($field2 == '') $field2=$field;
  $str = "<td $extra1>$Name" . ($Name?':':'') . help($field) . "<td colspan=$cols $extra1><input type=text name=$field2 $extra2 size=" . $cols*16; 
  if (isset($data[$field])) $str .= " value=\"" . htmlspec($data[$field]) ."\"";
  return $str . " $ADDALL>";
}

function fm_text1($Name,&$data,$field,$cols=1,$extra1='',$extra2='',$field2='') {
  global $ADDALL;
  if ($field2 == '') $field2=$field;
  $str = "<td colspan=$cols $extra1>$Name" . ($Name?':':'') . help($field) . "<input type=text name=$field2 $extra2 size=" . $cols*16; 
  if (isset($data[$field])) $str .= " value=\"" . htmlspec($data[$field]) ."\"";
  return $str . " $ADDALL>";
}

function fm_simpletext($Name,&$data=0,$field,$extra='') {
  global $ADDALL;
  $str = "$Name: " . help($field) . "<input type=text name=$field $extra";
  if ($data) if (isset($data[$field])) $str .= " value=\"" . htmlspec($data[$field]) . "\"";
  return $str . " $ADDALL>\n";
}

function fm_number1($Name,&$data=0,$field,$extra1='',$extra2='',$field2='') {
  global $ADDALL;
  if ($field2 == '') $field2=$field;
  $str = "<td $extra1>";
  if ($Name) $str .= "$Name: ";
  $str .= help($field) . "<input type=number name=$field2 $extra2";
  if ($data) if (isset($data[$field])) $str .= " value=\"" . htmlspec($data[$field]) . "\"";
  return $str . " $ADDALL>\n";
}

function fm_number($Name,&$data=0,$field,$extra1='',$extra2='') {
  global $ADDALL;
  $str = "<td $extra1>$Name: " . help($field) . "<td $extra1><input type=number name=$field $extra2";
  if ($data) if (isset($data[$field])) $str .= " value=\"" . htmlspec($data[$field]) . "\"";
  return $str . " $ADDALL>\n";
}

function fm_nontext($Name,&$data,$field,$cols=1,$extra='') {
  global $ADDALL;
  $str = "<td $extra>$Name:" . help($field) . "<td colspan=$cols $extra>";
  return $str . (isset($data[$field]) ? htmlspec($data[$field]) : '');
}

function fm_time($Name,&$data,$field,$cols=1,$extra='') {
  global $ADDALL;
  return "<td>$Name:" . help($field) . "<td colspan=$cols><input type=time name=$field $extra size=" . $cols*16 .
        " value=\"" . $data[$field] ."\" $ADDALL>";
}

function fm_hidden($field,$value,$extra='') {
  global $ADDALL;
  return "<input type=hidden name=$field id=$field $extra value=\"" . htmlspec($value) ."\">";
}

function fm_textarea($Name,&$data,$field,$cols=1,$rows=1,$extra1='',$extra2='') {
  global $ADDALL;
  $str = "<td $extra1>$Name:" . help($field) . "<td colspan=$cols $extra1><textarea name=$field $ADDALL $extra2 rows=$rows cols=" .$cols*20 . ">" ;
  return $str . (isset($data[$field])?        htmlspec($data[$field]) : '' ) . "</textarea>\n";
}

function fm_basictextarea(&$data,$field,$cols=1,$rows=1,$extra1='',$field2='') {
  global $ADDALL;
  if ($field2 == '') $field2=$field;
  $str = "<textarea name=$field2 $ADDALL $extra1 rows=$rows cols=" .$cols*20 . ">" ;
  return $str . (isset($data[$field])?        htmlspec($data[$field]) : '' ) . "</textarea>\n";
}

function fm_checkbox($Desc,&$data,$field,$extra='',$field2='') {
  global $ADDALL;
  if ($field2 == '') $field2=$field;
  if (isset($data[$field])) if ($data[$field]) return ($Desc?"$Desc:":'') . help($field) . "<input type=checkbox $ADDALL Name=$field2 $extra checked>";
  return ($Desc?"$Desc:":'') . help($field) . "<input type=checkbox $ADDALL Name=$field2 $extra>";
}

function fm_select2(&$Options,$Curr,$field,$blank=0,$selopt='',$field2='') {
  global $ADDALL;
  if ($field2 == '') $field2=$field;
  $str = "<select name=$field2 $ADDALL $selopt>";
  if ($blank) {
    $str .= "<option value=''";
    if ($Curr == 0) $str .= " selected";
    $str .= "></option>";
  }
  foreach ($Options as $key => $val) {
    $str .= "<option value=$key";
    if ($Curr == $key) $str .= " selected";
    $str .= ">" . htmlspec($val) . "</option>";
  }
  $str .= "</select>" . help($field) . "\n";
  return $str;
}

function fm_select(&$Options,$data,$field,$blank=0,$selopt='',$field2='') {
  if (isset($data[$field])) return fm_select2($Options,$data[$field],$field,$blank,$selopt,$field2);
  return fm_select2($Options,'@@@@@@',$field,$blank,$selopt,$field2);
}

function fm_radio($Desc,&$defn,&$data,$field,$extra='',$tabs=1,$extra2='',$field2='') {
  global $ADDALL;
  if ($field2 == '') $field2=$field;
  $str = "";
  if ($tabs) $str .= "<td $extra>"; 
  if ($Desc) $str .= "$Desc:";
  $str .= help($field) . " ";
  if ($tabs) $str .= "<td $extra2>"; 
  $done = 0;
  foreach($defn as $i=>$d) {
    if (!$d) continue;
    if ($done && $tabs == 2) $str.= "<br>";
    $done = 1;
    $str .= "$d:";
    $ex = $extra;
    $ex = preg_replace('/###F/',("'" . $field2 . "'"),$ex);
    $ex = preg_replace('/###V/',("'" . $i . "'"),$ex);
    $str .= "<input type=radio name=$field2 $ADDALL $ex value='$i'";
    if (isset($data[$field]) && ($data[$field] == $i)) $str .= " checked";
    $str .= ">\n";
  }
  return $str;
}

function fm_date($Name,&$data,$field,$extra1='',$extra2='',$field2='') {
  global $ADDALL;
  if ($field2 == '') $field2=$field;
  $str = "<td $extra1>$Name" . ($Name?':':'') . help($field) . "<td $extra1><input type=text name=$field2 $extra2 size=16"; 
  if (isset($data[$field]) && $data[$field]) $str .= " value=\"" . date('j M Y H:i',$data[$field]) . "\"";
  return $str . " $ADDALL>";
}

function fm_date1($Name,&$data,$field,$extra1='',$extra2='',$field2='') {
  global $ADDALL;
  if ($field2 == '') $field2=$field;
  $str = "<td $extra1>$Name" . ($Name?':':'') . help($field) . "<input type=text name=$field2 $extra2 size=16"; 
  if (isset($data[$field]) && $data[$field]) $str .= " value=\"" . date('j M Y H:i',$data[$field]) ."\"";
  return $str . " $ADDALL>";
}

function Disp_CB($what) {
  echo "<td>" . ($what?'Y':'');
}

function weblink($dest,$text='Website',$alink='',$all=0) {
  $dest = stripslashes($dest);
  $sites = explode(' ',$dest);
  if (count($sites) > 1) {
    $ans = '';
    foreach($sites as $site) {
      $ans .= "<a $alink target=_blank href='";
      if (!preg_match("/^https?/",$site)) $ans .= 'http://';
      $ans .= "$site'>";
      preg_match("/^(https?:\/\/)?(.*?)(\/|$)/",$site,$m);
      $ans .= $m[2];
      $ans .= "</a> ";
      if ($all==0) break;
    }
    return $ans;      
  } else {
    if (preg_match("/^http/",$dest)) return "<a href='$dest' $alink target=_blank>$text</a>";
    return "<a href='http://$dest' $alink target=_blank>$text</a>";
  }
}

function weblinksimple($dest) {
  $dest = stripslashes($dest);
  $ans = "<a target=_blank href='";
  if (!preg_match("/^https?/",$dest)) $ans .= 'http://';
  $ans .= "$dest'>";
  return $ans;      
}

function videolink($dest) {
  $dest = stripslashes($dest);
  if (preg_match("/^http/",$dest)) return "'" . $dest ."'";
  if (preg_match('/watch\?v=/',$dest)) {
    return preg_replace("/.*watch\?v=/", 'youtu.be/', $dest);
  } else if (preg_match('/src="(.*?)" /i',$dest,$match)) {
    return preg_replace("/www.youtube.com\/embed/", 'youtu.be', $match[1]);
  }
  return "'http://" . $dest ."'";
}

function embedvideo($dest) {
  $dest = stripslashes($dest);
  if (preg_match("/<iframe.*src/i",$dest)) return $dest;
  if (preg_match('/.*watch\?v=(.*)/',$dest,$mtch)) {
    $dest = $mtch[1];
  } else {
    $dest = preg_replace("/.*tu.be/i",'',$dest);
  }
  return "<iframe width=560 height=315 src='https://www.youtube.com/embed/" . $dest . "' frameborder=0 allowfullscreen></iframe>";
}

function Clean_Email(&$addr) {
  if (preg_match('/<([^>]*)>?/',$addr,$a)) return $addr=trim($a[1]);
  if (preg_match('/([^>]*)>?/',$addr,$a)) return $addr=trim($a[1]);
  $addr = preg_replace('/ */','',$addr);
  return $addr = trim($addr);
}


function formatBytes($size, $precision = 2) {
  if ($size==0) return 0;
  $base = log($size, 1024);
  $suffixes = array('', 'K', 'M', 'G', 'T', 'P');   
  return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}

function firstword($stuff) {
  if (preg_match('/(\S*?)\s/',trim($stuff),$s)) return $s[1];
  return $stuff;
}

function SAO_Report($i) {
  $OSide = Get_Side( $i ); 
  $str = "<a href=/int/ShowDance.php?sidenum=$i>" . $OSide['SName'];
  if ($OSide['Type']) $str .= " (" . trim($OSide['Type']) . ")";
  return $str . "</a>";
}

function SName(&$What) {
  if (isset($What['ShortName'])) if ($What['ShortName']) return $What['ShortName'];
  return $What['SName'];
}

function Social_Link(&$data,$site,$mode=0) { // mode:0 Return Site as text, mode 1: return blank/icon
  if (! isset($data[$site]) || strlen($data[$site]) < 5) return ($mode? '' :$site);
  $link = $data[$site];
  if (preg_match("/$site/i",$link)) {
    return weblink($link,($mode? ( "<img src=/images/icons/$site.jpg>") : $site));
  }
  return "<a href=http://$site.com/$link>" . ($mode? ( "<img src=/images/icons/$site.jpg>") : $site) . "</a>";
}

function NoBreak($t,$Max=0) {
  if ($Max == 0) return preg_replace('/ /','&nbsp;',$t);
  $Words = preg_split('/ /',$t);
  $Count = -1;
  foreach($Words as $word) {
    if (++$Count == 0) { 
      $NewTxt = $word;
    } else { 
      $NewTxt .= ( ($Count % $Max)==0?' ':'&nbsp;') . $word;
    }
  }
  return $NewTxt;
}

function FormatList(&$l) {
  $res = implode(', ',$l);
  $res = preg_replace('/, ([^,]*$)/'," and $1",$res);
  return $res;
}

function AlphaNumeric($txt) {
  return preg_replace('/[^a-zA-Z0-9]/','',$txt);
}

function DurationFormat($mins) { // Show N mins as N <=90, x hr ymins 
  if ($mins <=90 ) return "$mins minutes";
  return (int)($mins/60) . " hours " . (($mins%60) ? (($mins%60) . " minutes") : "");
}

?>