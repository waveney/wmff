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
$AutoADD = 0;

function fm_addall($txt) {
  global $ADDALL;
  $ADDALL = $txt;
}

function fm_textinput($field,$value='',$extra='') {
  global $ADDALL,$AutoADD;
  $str = "<input type=text name=$field id=$field $extra $ADDALL";
  if ($AutoADD) $str .=  " oninput=AutoInput('$field') ";
  if ($value) $str .= " value=\"" . htmlspec($value) . '"';
  return $str  .">";
}

function fm_smalltext($Name,$field,$value,$chars=4,$extra='') {
  global $ADDALL,$AutoADD;
  $str = "$Name " . help($field) . "<input type=text name=$field id=$field $extra size=$chars $ADDALL";
  if ($AutoADD) $str .=  " oninput=AutoInput('$field') ";
  $str .= " value=\"" . htmlspec($value) . '"';
  return $str  .">";
}

function fm_smalltext2($Name,&$data,$field,$chars=4,$extra='') {
  global $ADDALL,$AutoADD;
  $str = "$Name " . help($field) . "<input type=text name=$field id=$field $extra size=$chars $ADDALL";
  if ($AutoADD) $str .=  " oninput=AutoInput('$field') ";
  if (isset($data[$field])) $str .= " value=\"" . htmlspec($data[$field]) . '"';
  return $str  .">";
}

function fm_text($Name,&$data,$field,$cols=1,$extra1='',$extra2='',$field2='',$extra3='') {
  global $ADDALL,$AutoADD;
  if ($field2 == '') $field2=$field;
  if ($extra3 == '') $extra3 = $extra1;
  $str = "<td $extra3>$Name" . ($Name?':':'') . help($field) . "<td colspan=$cols $extra1><input type=text name=$field2 id=$field2 $extra2 size=" . $cols*16; 
  if (isset($data[$field])) $str .= " value=\"" . htmlspec($data[$field]) ."\"";
  if ($AutoADD) $str .=  " oninput=AutoInput('$field2') ";
  return $str . " $ADDALL>";
}

function fm_text1($Name,&$data,$field,$cols=1,$extra1='',$extra2='',$field2='') {
  global $ADDALL,$AutoADD;
  if ($field2 == '') $field2=$field;
  $str = "<td colspan=$cols $extra1>$Name" . ($Name?':':'') . help($field) . "<input type=text name=$field2 id=$field2 $extra2 size=" . $cols*16; 
  if (isset($data[$field])) $str .= " value=\"" . htmlspec($data[$field]) ."\"";
  if ($AutoADD) $str .= " oninput=AutoInput('$field2') ";
  return $str . " $ADDALL>";
}

function fm_text0($Name,&$data,$field,$cols=1,$extra1='',$extra2='',$field2='') {
  global $ADDALL,$AutoADD;
  if ($field2 == '') $field2=$field;
  $str = $Name . ($Name?':':'') . help($field) . "<input type=text name=$field2 id=$field2 $extra2 size=" . $cols*16; 
  if (isset($data[$field])) $str .= " value=\"" . htmlspec($data[$field]) ."\"";
  if ($AutoADD) $str .= " oninput=AutoInput('$field2') ";
  return $str . " $ADDALL>";
}

function fm_simpletext($Name,&$data=0,$field,$extra='') {
  global $ADDALL,$AutoADD;
  $str = "$Name: " . help($field) . "<input type=text name=$field  id=$field $extra";
  if ($data) if (isset($data[$field])) $str .= " value=\"" . htmlspec($data[$field]) . "\"";
  if ($AutoADD) $str .=  " oninput=AutoInput('$field') ";
  return $str . " $ADDALL>\n";
}

function fm_number1($Name,&$data=0,$field,$extra1='',$extra2='',$field2='') {
  global $ADDALL,$AutoADD;
  if ($field2 == '') $field2=$field;
  $str = "<td $extra1>";
  if ($Name) $str .= "$Name: ";
  $str .= help($field) . "<input type=number name=$field2 id=$field2 $extra2";
  if ($data) if (isset($data[$field])) $str .= " value=\"" . htmlspec($data[$field]) . "\"";
  if ($AutoADD) $str .=  " oninput=AutoInput('$field2') ";
  return $str . " $ADDALL>\n";
}

function fm_number($Name,&$data=0,$field,$extra1='',$extra2='',$field2='') {
  global $ADDALL,$AutoADD;
  if ($field2 == '') $field2=$field;
  $str = "<td $extra1>";
  if ($Name) $str .= "$Name: ";
  $str .= help($field) . "<td $extra1><input type=number name=$field id=$field $extra2";
  if ($data) if (isset($data[$field])) $str .= " value=\"" . htmlspec($data[$field]) . "\"";
  if ($AutoADD) $str .=  " oninput=AutoInput('$field2') ";
  return $str . " $ADDALL>\n";
}

function fm_nontext($Name,&$data,$field,$cols=1,$extra='') {
  global $ADDALL,$AutoADD;
  $str = "<td $extra>$Name:" . help($field) . "<td colspan=$cols $extra>";
  return $str . (isset($data[$field]) ? htmlspec($data[$field]) : '');
}

function fm_time($Name,&$data,$field,$cols=1,$extra='') {
  global $ADDALL,$AutoADD;
  return "<td>$Name:" . help($field) . "<td colspan=$cols><input type=time name=$field  id=$field $extra size=" . $cols*16 .
        ($AutoADD? " oninput=AutoInput('$field') " : "") . 
        " value=\"" . $data[$field] ."\" $ADDALL>";
}

function fm_hidden($field,$value,$extra='') {
  global $ADDALL,$AutoADD;
  return "<input type=hidden name=$field id=$field $extra value=\"" . htmlspec($value) ."\">";
}

function fm_textarea($Name,&$data,$field,$cols=1,$rows=1,$extra1='',$extra2='',$field2='') {
  global $ADDALL,$AutoADD;
  if ($field2 == '') $field2=$field;
  $str = "<td $extra1>$Name:" . help($field) . "<td colspan=$cols $extra1><textarea name=$field2 id=$field2 $ADDALL ";
  if ($AutoADD) $str .= " oninput=AutoInput('$field2') ";
  $str .= " $extra2 rows=$rows>" ;
  return $str . (isset($data[$field])?        htmlspec($data[$field]) : '' ) . "</textarea>\n";
}

function fm_basictextarea(&$data,$field,$cols=1,$rows=1,$extra1='',$field2='') {
  global $ADDALL,$AutoADD;
  if ($field2 == '') $field2=$field;
  $str = "<textarea name=$field2 id=$field2 $ADDALL $extra1 rows=$rows cols=" .$cols*20;
  if ($AutoADD) $str .= " oninput=AutoInput('$field2') ";
  $str .= ">" ;
  return $str . (isset($data[$field])? htmlspec($data[$field]) : '' ) . "</textarea>\n";
}

function fm_checkbox($Desc,&$data,$field,$extra='',$field2='',$split=0,$extra2='') {
  global $ADDALL,$AutoADD;
  if ($field2 == '') $field2=$field;
  if (isset($data[$field])) if ($data[$field]) {
    return ($Desc?"<label for=$field2>$Desc:</label>":'') . help($field) . ($split?"<td $extra2>":"") . "<input type=checkbox $ADDALL " . 
           ($AutoADD? " oninput=AutoCheckBoxInput('$field2') " : "") . " Name=$field2 id=$field2 $extra checked>";
  }
  return ($Desc?"<label for=$field2>$Desc:</label>":'') . help($field) . ($split?"<td $extra2>":"") . "<input type=checkbox $ADDALL " . 
          ($AutoADD? " oninput=AutoCheckBoxInput('$field2') " : "") . " Name=$field2 id=$field2 $extra>";
}

function fm_select2(&$Options,$Curr,$field,$blank=0,$selopt='',$field2='',$Max=0) {
  global $ADDALL,$AutoADD;
  if ($field2 == '') $field2=$field;
  $str = "<select name=$field2 $selopt id=$field2 $ADDALL ";
  if ($AutoADD) $str .= " oninput=AutoInput('$field2') ";
  $str .= ">";
  if ($blank) {
    $str .= "<option value=''";
    if ($Curr == 0) $str .= " selected";
    $str .= "></option>";
  }
  foreach ($Options as $key => $val) {
    if ($Max && !Access('SysAdmin') && $key>=$Max && $Curr!=$key) continue;
    $str .= "<option value=$key";
    if ($Curr == $key) $str .= " selected";
    $str .= ">" . htmlspec($val) . "</option>";
  }
  $str .= "</select>" . help($field) . "\n";
  return $str;
}

function fm_select(&$Options,$data,$field,$blank=0,$selopt='',$field2='',$Max=0) {
  if (isset($data[$field])) return fm_select2($Options,$data[$field],$field,$blank,$selopt,$field2,$Max);
  return fm_select2($Options,'@@@@@@',$field,$blank,$selopt,$field2,$Max);
}

function fm_radio($Desc,&$defn,&$data,$field,$extra='',$tabs=1,$extra2='',$field2='',$colours=0,$multi=0,$extra3='',$extra4='') {
  global $ADDALL,$AutoADD;
  if ($field2 == '') $field2=$field;
  $str = "";
  if ($tabs) $str .= "<td $extra>"; 
  if ($Desc) $str .= "$Desc:";
  $str .= help($field) . "&nbsp;";
  if ($tabs) $str .= "<td $extra2>"; 
  $done = 0;
  foreach($defn as $i=>$d) {
    if (!$d) continue;
    $str.= (($done && $tabs == 2) ? "<br>" : " ");
    $done = 1;
    if ($colours) $str .= "<span style='background:" . $colours[$i] . ";padding:4; white-space: nowrap;'>";
    $str .= "<label for=$field2$i $extra3>$d:</label>";
    $ex = $extra;
    $ex = preg_replace('/###F/',("'" . $field2 . "'"),$ex);
    $ex = preg_replace('/###V/',("'" . $i . "'"),$ex);
    if ($multi) {
      $str .= "<input type=checkbox name=$field2$i $ex id=$field2$i $ADDALL ";
      if ($AutoADD) $str .= " oninput=AutoInput('$field2$i',$i) ";    
      $str .= " value='$i'";
      if (isset($data["$field$i"]) && ($data["$field$i"] == $i)) $str .= " checked";
    } else {
      $str .= "<input type=radio name=$field2 $ex id=$field2$i $ADDALL ";
      if ($AutoADD) $str .= " oninput=AutoRadioInput('$field2',$i) ";
      $str .= " value='$i' $extra4";
      if (isset($data[$field]) && ($data[$field] == $i)) $str .= " checked";
    }
    $str .= ">\n";
    if ($colours) $str .= "</span>";
  }
  return $str;
}

function fm_date($Name,&$data,$field,$extra1='',$extra2='',$field2='') {
  global $ADDALL,$AutoADD;
  if ($field2 == '') $field2=$field;
  $str = "<td $extra1>$Name" . ($Name?':':'') . help($field) . "<td $extra1><input type=text name=$field2 id=$field2 $extra2 size=16"; 
  if (isset($data[$field]) && $data[$field]) $str .= " value=\"" . ($data[$field]?date('j M Y H:i',$data[$field]):'') . "\"";
  if ($AutoADD) $str .= " oninput=AutoInput('$field2') ";
  return $str . " $ADDALL>";
}

function fm_date1($Name,&$data,$field,$extra1='',$extra2='',$field2='') {
  global $ADDALL,$AutoADD;
  if ($field2 == '') $field2=$field;
  $str = "<td $extra1>$Name" . ($Name?':':'') . help($field) . "<input type=text name=$field2 id=$field2 $extra2 size=16"; 
  if (isset($data[$field]) && $data[$field]) $str .= " value=\"" . ($data[$field]?date('j M Y H:i',$data[$field]):'') ."\"";
  if ($AutoADD) $str .= " oninput=AutoInput('$field2') ";
  return $str . " $ADDALL>";
}

function fm_date0($Name,&$data,$field,$extra1='',$extra2='',$field2='') {
  global $ADDALL,$AutoADD;
  if ($field2 == '') $field2=$field;
  $str = $Name . ($Name?':':'') . help($field) . "<input type=text name=$field2 id=$field2 $extra2 size=16"; 
  if (isset($data[$field]) && $data[$field]) $str .= " value=\"" . ($data[$field]?date('j M Y H:i',$data[$field]):'') ."\"";
  if ($AutoADD) $str .= " oninput=AutoInput('$field2') ";
  return $str . " $ADDALL>";
}

function fm_pence($desc,&$data,$field,$extra1='',$extra2='',$field2='') {
  global $ADDALL,$AutoADD;
  if ($field2 == '') $field2=$field;
  $str = "<td $extra1>$desc" . ($desc?':':'') . help($field) . "<td $extra1>&pound;<input type=text name=$field2 id=$field2 $extra2 "; 
  if (isset($data[$field])) $str .= " value=\"" . $data[$field]/100 ."\"";
  if ($AutoADD) $str .=  " oninput=AutoInput('$field2') ";
  return $str . " $ADDALL>";
}

function fm_pence1($desc,&$data,$field,$extra1='',$extra2='',$field2='') {
  global $ADDALL,$AutoADD;
  if ($field2 == '') $field2=$field;
  $str = "<td $extra1>$desc" . ($desc?':':'') . help($field) . "&pound;<input type=text name=$field2 id=$field2 $extra2 "; 
  if (isset($data[$field])) $str .= " value=\"" . $data[$field]/100 ."\"";
  if ($AutoADD) $str .=  " oninput=AutoInput('$field2') ";
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
    $dest = preg_replace('/&.*/','',$dest);
  } else {
    $dest = preg_replace("/.*tu.be/i",'',$dest);
  }
  return "<iframe style='max-width:100%; width:560; height:315' src='https://www.youtube.com/embed/" . $dest . "' frameborder=0 allowfullscreen></iframe>";
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

function UpperFirstChr($stuff) {
  return strtoupper(substr($stuff,0,1)) . strtolower(substr($stuff,1));
}

function SAO_Report($i) {
  $OSide = Get_Side( $i ); 
  $str = "<a href=/int/ShowPerf?id=$i>" . $OSide['SN'];
  if ($OSide['Type']) $str .= " (" . trim($OSide['Type']) . ")";
  return $str . "</a>";
}

function SName(&$What) {
  if (isset($What['ShortName'])) if ($What['ShortName']) return $What['ShortName'];
  return $What['SN'];
}

function Social_Link(&$data,$site,$mode=0,$text='') { // mode:0 Return Site as text, mode 1: return blank/icon
  if (! isset($data[$site]) || strlen($data[$site]) < 5) return ($mode? '' :$site);
  $link = $data[$site];
  if (preg_match("/$site/i",$link)) {
    $follow = ($text? $text . $site :'');
    return " " . weblink($link,($mode? ( "<img src=/images/icons/$site.jpg title='$follow'> $follow") : $site)) . ($mode?"<br>":"");
  }
  return " <a href=http://$site.com/$link>" . ($mode? ( "<img src=/images/icons/$site.jpg>") : $site) . "</a><br>";
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


function Print_Pound($amt) {
  return ($amt<0?"-":"") . sprintf((ctype_digit($amt)?"&pound;%d":"&pound;%0.2f"),abs($amt));
}

function Print_Pence($amt) {
  if ($amt%100 == 0)   return ($amt<0?"-":"") . sprintf("&pound;%0.0f",abs($amt)/100);
  return ($amt<0?"-":"") . sprintf("&pound;%0.2f",abs($amt)/100);
}

function DurationFormat($mins) { // Show N mins as N <=90, x hr ymins 
  if ($mins <=90 ) return "$mins minutes";
  return (int)($mins/60) . " hours " . (($mins%60) ? (($mins%60) . " minutes") : "");
}

function Register_AutoUpdate($type,$ref) {
  global $AutoADD;
  echo fm_hidden('AutoType',$type);
  echo fm_hidden('AutoRef',$ref);
  $AutoADD = 1;
}

function FestDate($day,$format='M',$Year=0) {
  global $YEARDATA,$YEAR;
  static $Years;

  if ($Year == 0) $Year=$YEAR;
  $ShortYear = substr($Year,0,4);
  if ($Year != $YEARDATA['Year']) {
    $Years = Get_Years();
    if (isset($Years[$Year])) {
      $YD = $Years[$Year];
      $date = mktime(0,0,0,$YD['MonthFri'],$YD['DateFri']+$day,$ShortYear);
    } else {
      return "Unknown yet";
    }
  } else {
    $date = mktime(0,0,0,$YEARDATA['MonthFri'],$YEARDATA['DateFri']+$day,$ShortYear);
  }
  
  switch (strtoupper($format)) {
    default:
    case 'S': return date('D j M',$date);
    case 'Y': return date('l jS M',$date);
    case 'M': return date('D jS M Y',$date);
    case 'L': return date('l jS F Y',$date);
    case 'F': return date('l jS F',$date);
  }
}

function ChunkSplit($txt,$maxlen,$maxchnks) {
  $Words = explode(' ',$txt);
  $Res = [];
  $left = '';
  foreach ($Words as $w) {
    if ($left) {
      if (strlen("$left $w") <= $maxlen) {
        $left .= " $w";
      } else if (strlen($w) < $maxlen) {
        $Res[] = $left;
        $left = $w;
      } elseif (strlen("$left $w") <= 2*$maxlen) {
        $chk = "$left $w";
        $Res[] = substr($chk,0,$maxlen);
        $left = substr($chk,$maxlen);
      } else {
        $Res[] = $left;
        $Res[] = substr($w,0,$maxlen);
        $left = substr($w,$maxlen);
      }
    } elseif (strlen($w) < $maxlen) {
      $left = $w;
    } else {
      $Res[] = substr($w,0,$maxlen);
      $left = substr($w,$maxlen);
    }
  }
  if ($left) $Res[] = $left;
  
  return $Res;
}

function linkemailhtml(&$data,$type="Side",$xtr='',$ButtonExtra='') {
  global $YEAR,$USER;
  if (!Access('Staff')) return 0;
  include_once("DanceLib.php");
  $Label = '';
  if (isset($data['HasAgent']) && ($data['HasAgent'])) {
    if ($xtr == '') {
      if (!isset($data["AgentEmail"])) return "";
      $email = $data['AgentEmail'];
      $xtr = 'Agent';
      if (isset($data['AgentName'])) { $name = firstword($data['AgentName']); }
      else { $name = $data['SN']; }
    } else if ($xtr == '!!') {
      if (!isset($data["Email"])) return "";
      $email = $data['Email'];
      $xtr = '';
      $Label = 'Direct ';
      if (isset($data[$xtr .'Contact'])) { $name = firstword($data[$xtr .'Contact']); }
      else { $name = $data['SN']; }
    } else {
      if (!isset($data[$xtr . "Email"])) return "";
      $email = $data[$xtr . 'Email'];
      $Label = $xtr;
      if (isset($data[$xtr .'Contact'])) { $name = firstword($data[$xtr .'Contact']); }
      else { $name = $data['SN']; }
    }
  } else {
    if ($xtr == '!!') $xtr = '';
    if (!isset($data[$xtr . "Email"])) return "";
    $email = $data[$xtr . 'Email'];
    $Label = $xtr;
    if (isset($data[$xtr .'Contact'])) { $name = firstword($data[$xtr .'Contact']); }
    else { $name = $data['SN']; }
  }
  if ($email == '') return "";
  $email = Clean_Email($email);
  $key = $data['AccessKey'];
  if (isset($data['SideId'])) {
    $id = $data['SideId'];
  } else if (isset($data['Tid'])) {
    $id = $data['Tid'];
  }
  if (!isset($id)) return "";

  $link = "'mailto:$email?from=" . $USER['Email'] .
         "&subject=" . urlencode("Wimborne Minster Folk Festival $YEAR and " . $data['SN']) . "'";
  $direct = "<a href=https://" . $_SERVER['HTTP_HOST'] . "/int/Direct?t=$type&id=$id&key=$key&Y=$YEAR>this link</a>  " ;

  if (isset($data['SideId'])) {
    if ($data['IsASide'] && !$data['TotalFee']) {
      include_once("ProgLib.php");
      $ProgInfo = Show_Prog($type,$id,1);
      $Content = urlencode("$name,<p>" .
              "<div id=SideLink$id>" .
              "Please add/correct details about your side's contact information and your preferences in " .
              "terms of days coming, number of dance spots, etc. by visiting $direct.</div><p>" .
              "You can update information at any time, until the programme goes to print. " .
              "(You'll also be able to view your programme times, once we've done the programme)<p>" .
              "<div id=SideProg$id>$ProgInfo</div><p>" .
              "Regards " . $USER['SN'] . "<p>"); 
    } else {
      include_once("MusicLib.php");
      $Content = MusicMail($data,$name,$id,$direct);
    }
  } else { // Trade/Invoicing (I think gets here)
    $Content = urlencode("$name,<p>" . "<div id=SideLink$id>To update, correct, and administer your booking please visit $direct.</div></p>" .
               "Regards " . $USER['SN'] . "<p>"); 
  }

  $lnk = "<button onclick=\"emailclk($link,'Email$id'); $ButtonExtra\" id=Em$id target='_blank' type=button>$Label Email</button>" .
         "<div hidden><div id=Email$id>$Content</div></div>";
  return $lnk;
}

global $DDdata;
$DDdata = [
    'Insurance' => [ 'UseYear'=>1, 'AddState'=>1, 'tr'=>1, 'SetValue'=>1, 'cols'=>[2,2] ],
    'RiskAssessment' => [ 'UseYear'=>1, 'AddState'=>1, 'Name' => 'Risk Assessment', 'tr'=>1, 'SetValue'=>1, 'cols'=>[2,2] ],
    'StagePA'  => [ 'UseYear'=>0, 'AddState'=>0, 'Name'=>'PA requirements', 'tr'=>0, 'SetValue'=>'@@FILE@@', 'cols'=>[2,2], 'path'=>'PAspecs' ],
    'Photo'    => [ 'UseYear'=>0, 'AddState'=>0, 'tr'=>0, 'SetValue'=>'URL', 'Extra'=>"acceptedFiles: 'image/*',", 'cols'=>[2,2], 'path'=>'images', 'Show'=>1 ], 
    'NewPhoto' => [ 'UseYear'=>0, 'AddState'=>0, 'tr'=>0, 'SetValue'=>'URL','cols'=>[1,1], 'URL'=>'PhotoProcess.php', 'Replace'=>1, 
                 'Extra'=>"acceptedFiles: 'image/*',", 'Show'=>1],
    'NewImage' => [ 'UseYear'=>0, 'AddState'=>0, 'tr'=>0, 'SetValue'=>'URL','cols'=>[1,1], 'URL'=>'PhotoProcess.php', 'Replace'=>1, 
                 'Extra'=>"acceptedFiles: 'image/*',"],
];


//var_dump($DDdata); exit;
function fm_DragonDrop($Call, $Type,$Cat,$id,&$Data,$Mode=0,$Mess='',$Cond=1,$tddata1='',$tdclass='',$hide=0) {
  global $db,$InsuranceStates,$YEAR,$DDdata;

//var_dump($Call, $Type,$Cat,$id,$Mode,$Mess,$Cond);
  $str = '';
  $DDd = &$DDdata[$Type];
//var_dump($DDd);
  $Name = $Type;
  $hid = ($hide?' hidden ':'');
  if (isset($DDd['Name'])) $Name = $DDd['Name'];
    
  if ($Call || isset($DDd['Show'])) {  
    if ($DDd['tr']) {
      $str .= "<tr><td $tddata1 $hid>$Name:";
      if (!$Cond) {
        $str .= "<td colspan=4>You will be able to upload your $Name here in $YEAR\n";
        return $str;
      }
    }
  
    $Padding = time();  
    if (isset($DDd['Show'])) {
      $str .= "<td class=Drop$Type >";
      if (isset($Data[$Type]) && $Data[$Type]) {
        $str .= "<img id=Thumb$Type src=" . $Data[$Type] . " height=120>";
      } else {
        $str .= "No Photo Yet";
      }
      $str .= "<td class='Result$Type $tdclass' $hid><div class=dropzone id=Upload$Type$Padding ></div><script>";
    } else {
      $str .= "<td class='Drop$Type $tdclass' $hid><div class=dropzone id=Upload$Type$Padding ></div><script>";
    }


    $url = (isset($DDd['URL'])? $DDd['URL'] : 'DragAndDropped.php');
    $replace = (isset($DDd['Replace'])? 1 : 0 );
    $extra = (isset($DDd['Extra'])? $DDd['Extra'] : '');
    $str .= <<<XXX
  Dropzone.options.Upload$Type$Padding = { 
    paramName: "Upload",
    url: '$url',
    $extra
    createImageThumbnails: 0,
    init: function() {
      this.on("success", function(e,r) { 
        console.log(r);
        if ($replace) { 
          document.open(); document.write(r); document.close();
        } else {
          $('.Result$Type').remove(); 
          $('.Drop$Type').replaceWith(r)
        }
      });
    },
    sending: function(file, xhr, formData){
      formData.append('Cat',"$Cat" );
      formData.append('Id', "$id" );
      formData.append('Type',"$Type" );
      if ($Mode) formData.append('Mode',"$Mode" ); 
      if ('$tdclass' != '') formData.append('Class',"$tdclass" );  
    },
    dictDefaultMessage: "Drop $Name here to upload"
  };
XXX;
    $str .= "</script>";
  }
//      init: function() {
//        this.on("success", function(e,r) { document.open(); document.write(r); document.close(); });
//      },

  if (isset($DDd['path'])) {
    $pdir = $DDd['path'];
  } else {
    $pdir = ($DDd['UseYear']?"$Type/$YEAR/$Cat":$Type);
  }
  $path = "$pdir/$id";
  $files = glob("$path.*"); 
 
  if ($Mode) {
    if ($DDd['AddState']) {
      $str .= "<td class='Result$Type $tdclass' $hid colspan=" . $DDd['cols'][0] . ">";
      $str .= "<div class=NotCSide>" . fm_radio($Type,$InsuranceStates,$Data,$Type,'',0) . "</div>";
    }
  } elseif ($DDd['AddState']) {
    $ddat = (isset($Data[$Type])?$Data[$Type]:'');
    $str .= "<td class='Result$Type $tdclass' $hid colspan=" . $DDd['cols'][0] . ">";
    $tmp['Ignored'] = $ddat;
    $str .= fm_checkbox("$Type Uploaded",$tmp,'Ignored','disabled');
    $str .= fm_hidden($Type,$ddat);   
  }
  
  if ($files) {
    $Current = $files[0];
    $Cursfx = pathinfo($Current,PATHINFO_EXTENSION );
    $str .= "<td class='Result$Type $tdclass' $hid colspan=" . $DDd['cols'][1] . "><a href=ShowFile?l=$path.$Cursfx>View $Name file</a><br>";  
  }
  if ($Mess) $str .= "<td class='Result$Type $tdclass' $hid>$Mess";
  return $str;
}

/* TODO
--Documents
--Insurance
--RiskAccess
Photos - action after upload
PA specs - complex behavior, 
-- Perf Files
Invoices?  

DragonDrop(Call: 1 Page, 0 Update
  Type: Insurance | RiskAccess | PA | ...
  Cat: Side | Perf | Trade | ... (Side and Trade give yeardata, Perf give permdata)
  id, Data: id and data
  Imp: -> xtr1
  Mode: 0 user,1 sys
  Code: ??
  Mess: Mess to append
  UseYear: Derrive from Type
  AddState: Derrive from Type


*/

?>
