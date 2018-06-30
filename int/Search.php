<?php
  include_once("fest.php");
  A_Check('Staff','Docs');

  dostaffhead("Document Search");

  include_once("files/navigation.php"); 
  include_once("DocLib.php");

  $table = 0;
  $lsted = array();
  if (isset($_POST{'Search'})) {
    $xtr = '';
    $from = $until = '';
    if ($_POST{'Who'}) $xtr = ' AND Who=' . $_POST{'Who'};
    if ($_POST{'From'}) $from = Extract_Date($_POST{'From'});
    if ($_POST{'Until'}) $until = Extract_Date($_POST{'Until'});
    $targ = $_POST{'Target'};
    if (isset($_POST{'Titles'}) || !isset($_POST{'Cont'})) {
      if ($from) $xtr .= " AND Created>$from ";
      if ($until) $xtr .= " AND Created<$until ";
      $qry = "SELECT * FROM Documents WHERE SName COLLATE UTF8_GENERAL_CI LIKE '%$targ%' $xtr";
      $res = $db->query($qry);
      if ($res && $res->num_rows) {
        Doc_Table_Head();
        $table = 1;
        while($doc = $res->fetch_assoc()) {
          Doc_List($doc,1);
          $lsted[$doc['DocId']]=1;
        }
      }
    }

    if (isset($_POST{'Cont'})) {
      exec("grep -lir '" . $targ . "' Store", $greplst);
      if ($greplst) {
        foreach($greplst as $file) {
          $doc = Find_Doc_For($file);
          if (!$doc) continue;
//echo "from = $from until = $until now =" . time() . "doc= ". var_dump($doc) . "<P>";
          if ($_POST{'Who'}) if ($doc['Who'] != $_POST{'Who'}) continue;
          if ($from) if ($doc['Created'] < $from) continue;
          if ($until) if ($doc['Created'] > $until) continue;
          if (isset($lsted[$doc['DocId']])) continue;
          if (!$table) {
            Doc_Table_Head();
            $table = 1;
          }
          Doc_List($doc,1);
          $lsted[$doc['DocId']]=1;
        }
      }
    }

    if($table) {
      echo "</tbody></table>\n";
    } else {
      echo "<h2>Not found</h2>\n";
    }
  }

  SearchForm();

  dotail();
?>

