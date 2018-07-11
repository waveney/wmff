<?php
// Common User Library

function Set_User_Help() {
  static $t = array(
         'AccessLevel'=>'Do not use Participant or Internal.  Set to blank to remove access',
        'Reserved'=>'System is for things like the documents root directory, nobody is for files created by people no longer with access',
        'Abrev'=>'Initials used for Timeline records rather than login name',
  );
  Set_Help_Table($t);
}

?>
