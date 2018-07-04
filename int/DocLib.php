<?php
// Common Docs Library

function Get_DirList($d) {
  global $db;
  $qry = "SELECT * FROM Documents WHERE Dir='" . $d . "' AND State=0 ORDER BY SName";
  $res = $db->query($qry);
  if (!$res) return 0;
  $ans = array();
  while ($rec = $res->fetch_assoc()) $ans[] = $rec;
  return $ans;
}

function Get_SubDirList($d) {
  global $db;
  $qry = "SELECT * FROM Directories WHERE Parent='" . $d . "' AND State=0 ORDER BY SName";
  $res = $db->query($qry);
  if (!$res) return 0;
  $ans = array();
  while ($rec = $res->fetch_assoc()) $ans[] = $rec;
  return $ans;
}

$Dir_cache = array();

function Get_DirInfo($d,$new=0) {
  global $db,$Dir_cache;
  if ($new==0 && isset($Dir_cache[$d])) return $Dir_cache[$d];

  $qry = "SELECT * FROM Directories WHERE DirId='" . $d . "'";
  $res = $db->query($qry);
  if (!$res) return 0;
  return $Dir_cache[$d] = $res->fetch_assoc();
}

function Put_DirInfo($stuff) {
  global $db,$Dir_cache;

  $d = $stuff['DirId'];
  if (!isset($Dir_cache[$d])) return 0;
  $odata = $Dir_cache[$d];
  $fcnt = 0;
  $rec = "UPDATE Directories SET ";
  foreach ($stuff as $fld=>$val) {
    if ($val != $odata[$fld]) {
      if ($fcnt++) $rec .= ", ";
      $rec .= "$fld='" . $val . "'";
      $odata[$fld]=$val;
    }
  }
  if (!$fcnt) return 1;
  $Dir_cache[$d] = $odata;
  $rec .= " WHERE DirId='" . $d . "'";
//  echo "PUT $rec <p>";
  return $db->query($rec);
}

function Get_Parent($d) {
  if (!$d) return "<a href=Dir.php>Documents:</a>";
  $inf = Get_DirInfo($d);
  return Get_Parent($inf['Parent']) . " / <a href=Dir.php?d=$d>" . htmlspec($inf['SName']) . "</a>";
}

function Dir_FullName($d) {
  if (!$d) return "";
  $inf = Get_DirInfo($d);
  return Dir_FullName($inf['Parent']) . "/" . htmlspec($inf['SName']);
}

function File_FullName($f) {
  if (!$f) return "";
  $inf = Get_DocInfo($f);
  return Dir_FullName($inf['Dir']) . "/" . htmlspec($inf['SName']);
}

function Dir_FullPName($d) {
  if (!$d) return "";
  $inf = Get_DirInfo($d);
  return Dir_FullPName($inf['Parent']) . "/" . stripslashes($inf['SName']);
}

function File_FullPName($f) {
  if (!$f) return "";
  $inf = Get_DocInfo($f);
  return Dir_FullPName($inf['Dir']) . "/" . stripslashes($inf['SName']);
}

function Get_AllUsers($mode=0) { // 0 return login names, 1 return levels
  global $db;
  static $cache, $AccessC;
  if (isset($cache)) return ($mode)?$AccessC:$cache;
  $res = $db->query("SELECT UserId, Login, AccessLevel FROM FestUsers ORDER BY UserId");
  $ans = array();
  $ac = array();
  while ($us = $res->fetch_assoc()) {
    $uid = $us['UserId'];
    $ulog = $us['Login'];
    $ans[$uid] = $ulog;
    $ac[$uid] = $us['AccessLevel'];
  }
  $cache = $ans;
  $AccessC = $ac;
  return ($mode?$AccessC:$cache);
}

function Get_AllUsers4Sect($Sect,$also=-1,$Sect2='@@',$Sect3='@@') { // Sect = Music, Dance etc, include also even if not for sect
  global $db;
  $res = $db->query("SELECT * FROM FestUsers ORDER BY UserId");
  $ac = array();
  while ($us = $res->fetch_assoc()) {
    $uid = $us['UserId'];
    $ulog = $us['Login'];
    if ($us[$Sect] || $uid==$also || ($Sect2 != '@@' && isset($us[$Sect2])) || ($Sect2 != '@@' && isset($us[$Sect3]))) $ans[$uid] = $ulog;
  }
  return $ans;
}

function DeleteAll($d) {
  $dir  = Get_DirInfo($d);

  $fullname = Dir_FullName($d);
  $fullpath = dirname($fullname);
  DeleteDir($d);

  $pad1 = '';
  $pad2 = '';
  umask(0);
  while (file_exists("OldStore" . $fullname . $pad1 . $pad2 )) { $pad1 = '_'; $pad2++; };
  if (!file_exists("OldStore" . $fullpath)) mkdir("OldStore" . $fullpath, 0777, 1);
  rename("Store" . $fullname, "OldStore" . $fullname . $pad1 . $pad2 );
  Logg("Deleting Directory $fullname");
}

function DeleteDir($d) {
  global $db;
  $res = $db->query("UPDATE Directories SET State=1 WHERE DirId='" . $d . "'");
  $res = $db->query("UPDATE Directories SET State=1 WHERE Parent='" . $d ."'");
  if ($db->affected_rows ) {
    $subs = Get_SubDirList($d);
    foreach($subs as $sub)  { DeleteDir($sub); };
  }

  $res = $db->query("UPDATE Documents SET State=1 WHERE Directory='" . $d ."'");
}

function DeleteFile($f) {
  global $db;
  $res = $db->query("UPDATE Documents SET State=1 WHERE DocId='" . $f ."'");

  $fullname = File_FullName($f);
  $fullpath = dirname($fullname);
  $pad1 = '';
  $pad2 = '';
  umask(0);
  while (file_exists("OldStore" . $fullname . $pad1 . $pad2 )) { $pad1 = '_'; $pad2++; };
  if (!file_exists("OldStore" . $fullpath)) mkdir("OldStore" . $fullpath, 0777, 1);
  rename("Store" . $fullname, "OldStore" . $fullname . $pad1 . $pad2 );
  Logg("Deleting $fullname");
}

function Dir_recurse($d,$name,$level,$cur,$exclude) {
  if ($d == $exclude) return "";
  if ($cur==$d) { $ad=" selected"; } else { $ad = ''; };
  $ans = "<option value=$d $ad>" . str_repeat("|___",$level) . $name . "</option>\n";
  $subs = Get_SubDirList($d);
  if ($subs) {
    foreach ($subs as $sub) {
      $ans .= Dir_recurse($sub['DirId'],$sub['SName'],$level+1,$cur,$exclude);
    }
  }
  return $ans;
}

function Dir_All_Tree($NewDir,$cur=-1,$exclude=-1) {
  $ans = "<select name=$NewDir size=15>\n";
  $ans .= Dir_recurse(0,'Documents:',0,$cur,$exclude); 
  $ans .= "</select>\n";
  return $ans;
}

$Doc_cache = array();

function Get_DocInfo($f) {
  global $db,$Doc_cache;
  if (isset($Doc_cache[$f])) return $Doc_cache[$f];
  $qry = "SELECT * FROM Documents WHERE DocId='" . $f . "'";
  $res = $db->query($qry);
  if (!$res) return 0;
  $Doc_cache[$f] = $res->fetch_assoc();
  return $Doc_cache[$f];
}

function Put_DocInfo(&$finf) {
  global $db,$Doc_cache;
  $f = $finf['DocId'];
  if (!isset($Doc_cache[$f])) return 0;
  $odata = $Doc_cache[$f];
  $fcnt = 0;
  $rec = "UPDATE Documents SET ";
  foreach ($finf as $fld=>$val) {
    if ($val != $odata[$fld]) {
      if ($fcnt++) $rec .= ", ";
      $rec .= "$fld='" . $val . "'";
      $odata[$fld]=$val;
    }
  }
  if (!$fcnt) return 1;
  $Doc_cache[$f] = $odata;
  $rec .= " WHERE DocId='" . $f . "'";
  return $db->query($rec);
}

function Doc_Access($num) {
  return $num; // for now...  
}

function Doc_create($fname,$d,$size) {
  global $db,$USERID;
  $qry = "INSERT INTO Documents SET Dir=$d, SName='" . addslashes($fname) . "', Who='$USERID', Created=" . time() .
         ", filesize=" . $size . ", Access=666";
  $ans = $db->query($qry);
  return $ans;
}

function Set_Doc_Help() {
  static $t = array(
         'Access'=>'Currently has no effect, would allow restricted access for reading in the future if needed',
        'Actions'=>'Note Delete removes from view and archives the document/directory.  It is possible (but not easy) to retrieve these.',
        'Search'=>'Looks for files that contain the asked for string in the title and/or the content.  Thus searching for "i" would find all files with an i in them.  You can restrict a search to those by a particular person or date range.'
  );
  Set_Help_Table($t);
}

function Doc_Table_Head() {
  Set_Doc_Help();
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";
  $coln = 0;
  echo "Click on column to sort by column, on the Filename to view.<p>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>File Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Originator</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Size</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'D','dmy')>Date</a>\n";
//  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T',)>Access</a>" . help('Access') . "\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T',)>Actions</a>" . help('Actions') . "\n";
  echo "</thead><tbody>";
}

function Doc_List($file,$opts=0) {
  global $USERID;
  $name = htmlspec($file['SName']);
  $fid = $file['DocId'];
  $d = $file['Dir'];
  $dir = Get_DirInfo($d);
  $AllU = Get_AllUsers();
  echo "<tr><td><a href=ShowFile.php?f=$fid>" . $name . "</a>";
  echo "<td>" . $AllU[$file['Who']];
  echo "<td align=right>" . formatBytes($file['filesize'],0);
  echo "<td>" . date('d/m/y H:i:s',$file['Created']) . "<td>";
//  echo Doc_Access($file['Access']) . "<td>";
  if ($opts & 1) echo "<a href=Dir.php?d=$d>Directory</a> ";
  echo "<a href=ShowFile.php?d=$fid>Download</a> ";
  if (Access('Committee','Docs') || $dir['Who'] == $USERID || $sub['Who'] == $USERID ) {
    echo "<a href=Dir.php?f=$fid&d=$d&FileAction=Rename1>Rename</a> "; 
    echo "<a href=Dir.php?f=$fid&d=$d&FileAction=Move1>Move</a> "; 
    echo "<a href='Dir.php?f=$fid&d=$d&FileAction=Delete' " .
                  "onClick=\"javascript:return confirm('are you sure you want to delete this?');\">Delete</a> "; 
    if (Access('Committee','Docs')) {
      echo "<a href=Dir.php?f=$fid&d=$d&FileAction=Chown1>Chown</a> "; 
    }
  }
}

function Find_Doc_For($fname) { 
  global $db;
  $chunks = preg_split('/\//',addslashes($fname));
  $nchunks = sizeof($chunks);
  if ($nchunks < 2) return 0;
  if ($nchunks == 2) { $d = 0; }
  else {
    $d = 0;
    $chk = 1;
    while ($chk < $nchunks-1) {
      $res = $db->query("SELECT * FROM Directories WHERE Parent='" . $d . "' AND SName='" . $chunks[$chk] . "'");
      if (!$res) return 0;
      $ans = $res->fetch_assoc();
      $d = $ans['DirId'];
      $chk++;
    }
  }
  $res = $db->query("SELECT * FROM Documents WHERE Dir='" . $d . "' AND SName='" . $chunks[$nchunks-1] . "'");
  if (!$res) return 0;
  $ans = $res->fetch_assoc();
  return $ans;
}

function SearchForm() {
  if (!isset($_POST{'Titles'}) && !isset($_POST{'Cont'})) { $_POST{'Titles'} = 1; }
  elseif (!$_POST{'Titles'} && !$_POST{'Cont'}) $_POST{'Titles'} = 1;
  echo "<form action=Search.php method=post>";
  echo "Search " . fm_checkbox("Titles",$_POST,'Titles');
  echo fm_checkbox("Content",$_POST,'Cont');
  echo fm_simpletext("for",$_POST,'Target');
  $AllU = Get_AllUsers();
  echo " by " . fm_select($AllU,$_POST,'Who',1);
  echo fm_simpletext("From",$_POST,'From','size=10');
  echo fm_simpletext("Until",$_POST,'Until','size=10');
  echo "<input type=submit name=Search value=Search>";
  echo "</form>";
}

function Extract_Date($txt) {
  $chnks = preg_split('/\//',$txt);
  $bits = sizeof($chnks);

  $day = $chnks[0];
  $mnth = $chnks[1];
  if ($bits == 2) { // day/month assumed
    if ($mnth <= date('n')) return mktime(0,0,0,$mnth,$day); 
    return mktime(0,0,0,$mnth,$day,date('Y')-1); 
  } elseif ($bits ==3 ) { // day/month/year assumed - year can be 2 or 4 digit
    return mktime(0,0,0,$mnth,$day,$chnks[2]);
  } else {
    return '';
  }
}

?>
