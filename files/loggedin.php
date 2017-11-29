<?php
$getuser = $_COOKIE['jmnuser'];
$getexpire = $_COOKIE['jmnexpire'];
$midnight = "MIDNIGHT";
if($getexpire == $midnight)
{
$tonight = strtotime('tomorrow midnight') - time();
$expire = time() + $tonight;
}

if($getexpire != $midnight)
{
$expire = time() + $getexpire;
}
setcookie("jmnuser","$getuser", "$expire");
?>