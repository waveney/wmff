<?php
//  Read Access to James's Event table for Acts and Other Participants
// Will be replaced by direct handling like dance for 2018

function OldMunge(&$stuff) { // Convert from James's Event database to form used for main workings
  $EventTranslate = array('app'=>'App','title'=>'SName','link1'=>'Website','facebook'=>'Facebook','twitter'=>'Twitter','instagram'=>'Instagram',
		'youtube'=>'Video','description'=>'Blurb');

  $fluf['id'] = $stuff['id'];

// Bulk traslations
  foreach ($EventTranslate as $o=>$n) if ($stuff[$o]) $fluf[$n] = $stuff[$o];

// Special cases - description, link2, img, headline

  $desc = substr($stuff['description'],0,150);
  $desc = substr($desc, 0, strrpos($desc, '.'));
  $fluf['Description'] = $desc; 
  if ($stuff['link2']) $fluf['Website'] .= " " . $stuff['link2'];
  if ($stuff['img']) $fluf['Photo'] = "/images/" . $stuff['img'];
  $fluf['Importance'] = ($stuff['headline'] == 'true' ? 1 : 0 );

  return $fluf;
}

function Get_Act($Who) {
  global $db;
  $res = $db->query("SELECT * FROM wmffevent WHERE id=$Who");
  if (!$res) return 0;
  $ans = $res->fetch_assoc();
  return OldMunge($ans);
}

function Get_Other($Who) {
  return Get_Act($Who);
}

function Select_Acts($type=0,$Extra='') { // type 0 just name list, 1 full data, 2 munged
  global $db;

//  $res = $db->query("SELECT * FROM wmffevent WHERE cat='music' AND display='true' $Extra ORDER BY headline, title");
  $res = $db->query("SELECT * FROM wmffevent WHERE cat!='dance' AND display='true' $Extra ORDER BY headline, title");
  while ($act = $res->fetch_assoc()) {
    switch ($type) {
    case 0:
      $ans[$act['id']] = $act['title'];
      break;
    case 1:
      $ans[$act['id']] = $act;
      break;
    case 2:
      $ans[$act['id']] = OldMunge($act);
      break;
    }
  }
  return $ans;
}

function Select_Others($type=0,$Extra='') {// type 0 just name list, 1 full data, 2 munged
  global $db;

  $res = $db->query("SELECT * FROM wmffevent WHERE cat != 'music' AND cat != 'dance' AND display='true' $Extra ORDER BY headline, title");
  while ($act = $res->fetch_assoc()) {
    switch ($type) {
    case 0:
      $ans[$act['id']] = $act['title'];
      break;
    case 1:
      $ans[$act['id']] = $act;
      break;
    case 2:
      $ans[$act['id']] = OldMunge($act);
      break;
    }
  }
  return $ans;
}

?>
