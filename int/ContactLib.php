<?php
global $ContCatState, $ContCatColours;
 $ContCatState = ['Closed','Opening Soon','Open'];
 $ContCatColours = ['red','orange','#00CC00'];
  
function Get_ContactCat($id) {
  global $db;
  $res=$db->query("SELECT * FROM ContactCats WHERE id=$id");
  if ($res) {
    $ans = $res->fetch_assoc();
    return $ans;
  }
  return 0; 
}

function Get_ContactCats() {
  global $db;
  $full = [];
  $res = $db->query("SELECT * FROM ContactCats ORDER BY SN ");
  if ($res) {
    while ($typ = $res->fetch_assoc()) {
      $full[$typ['id']] = $typ;
    }
  }
  return $full;
}

function Put_ContactCat(&$now) {
  $e=$now['id'];
  $Cur = Get_ContactCat($e);
  return Update_db('ContactCats',$Cur,$now);
}

