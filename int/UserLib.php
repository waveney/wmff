<?php
// Common User Library

function Set_User_Help() {
  static $t = array(
         'AccessLevel'=>'Do not use Participant or Internal.  Set to blank to remove access',
        'Reserved'=>'System is for things like the documents root directory, nobody is for files created by people no longer with access',
        'Abrev'=>'Initials used for Timeline records rather than login name',
        'NoTask'=>'Set This for test only users',
        'Public'=>'How visible are you on the contacts page, No, Yes, Role only',
  );
  Set_Help_Table($t);
}

$User_Public_Vis = ['No','Yes','Role Only'];

function Login_Details($key,&$data,$att=0) {
  $userid = $data['UserId'];
  switch ($key) {
  case 'LINK' : return "<a href='https://" . $_SERVER['HTTP_HOST'] . "/int/Login?ACTION=LIMITED&U=$userid&A=" . $data['AccessKey'] . "'>New Password link</a> "; 
  case 'USER' : return $data['Login'];
  case 'PWD' : return $data['ActualPwd']; // This is not stored in DB, but will be logged
  case 'WHO' : return firstword($data['SN']);
  }
}


?>
