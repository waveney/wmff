<?php

function Get_All_News($nid=0,$lim=50,$fut=0) { // 0 - Current, 1 = all, if current wont give those in future
  global $db;
  $news = array();
  $now = time();
  $xtra = '';
  if ($nid==0 || $fut==0) $xtra .= " WHERE ";
  if ($nid==0) $xtra .= " display!=0 ";
  if ($nid==0 && $fut==0) $xtra .= " AND ";
  if ($fut==0) $xtra .= " created<$now ";
  $res = $db->query("SELECT * FROM News $xtra ORDER BY id DESC LIMIT 0,$lim");
  if ($res) while ($ns = $res->fetch_assoc())  $news[] = $ns;
  return $news;
}

function Get_News($id) {
  global $db;
  $res=$db->query("SELECT * FROM News WHERE id=$id");
  if ($res) return $res->fetch_assoc();
  return 0; 
}

function Put_News(&$now) {
  $e=$now['id'];
  $Cur = Get_News($e);
  return Update_db('News',$Cur,$now);
}

function News_List_Item(&$news) {
  $getid = $news['id'];
  $gettitle = $news['SName'];
  $getdate = date('j M Y', $news['created']);
  echo "<p style=margin-bottom:10px;>$getdate | <a class=shonar href=/news/?id=$getid rel=bookmark style=color:#FFFFFF;><strong>$gettitle</strong></a></p>";  
}

function News_Item(&$news,$tlim=500,$more=0,$class='newsimg') { // if tlim=0 all text, more=1 always show more button, 2 = never
  $getid = $news['id'];
  $getcontent = $news['content'];
  $getimage = $news['image'];
  $getauthor = ucwords($news['author']);
  $getdate = date('j F Y', $news['created']+0);

  if ($tlim && $more<2) {
    $More = (strlen($getcontent) > $tlim);
    if ($More) {
      $getcontent = substr($getcontent,0,$tlim);
      $getcontent = substr($getcontent, 0, strrpos($getcontent, '.'));
    }
  } 

  echo "<div class=news>";
  if(!empty($getimage)) {
    $img=$getimage;
    if (!preg_match('/^http(s?):/',$getimage)) $img = "/images/" . $img;
    echo "<div class=$class><a href=/int/newsitem.php?id=$getid rel=bookmark><img src='$img' alt=\"Wimborne Minster Folk Festival\" class=$class></a>";
    if ($news['caption']) echo "<br>" . $news['caption'];
    echo "</div>";
  }
  echo "<h2 class=subtitle><a href=/int/newsitem.php?id=$getid rel=bookmark>" . $news['SName'] . "</a></h2>\n";
  echo "<span class=newsdate>$getdate by $getauthor</span><p>\n";
  echo "<span class=newstext>$getcontent</span>\n";

  if ($news['Link']) {
    if ($news['LinkText']) {
      echo "<p><a href=" . $news['Link'] . ">" . $news['LinkText'] . "</a><p>";
    } else {
      echo "<p><a href=" . $news['Link'] . ">Link</a><p>";
    }
  }
  if ($more==1 || ($more ==0 && $More)) echo "<p><a class=button href=/int/newsitem.php?id=$getid rel=bookmark style=\"color:#ffffff;\">Read More</a><p>";
  echo "</div>\n";
}

function Get_All_Articles($nid=0) { // 0 - Current, 1 = all, if current wont give those in future
  global $db;
  $Arts = array();
  $res = $db->query("SELECT * FROM Articles " . ($nid?'':' WHERE InUse=1 '));
  if ($res) while ($ns = $res->fetch_assoc())  $Arts[] = $ns;
  return $Arts;
}

function Get_Article($id) {
  global $db;
  $res=$db->query("SELECT * FROM Articles WHERE id=$id");
  if ($res) return $res->fetch_assoc();
  return 0; 
}

function Put_Article(&$now) {
  $e=$now['id'];
  $Cur = Get_Article($e);
  return Update_db('Article',$Cur,$now);
}

?>
