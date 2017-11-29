<?php
  include_once("fest.php");
  A_Check('Staff','Docs');
?>

<head>
<title>WMFF Staff | Search</title>
<?php include("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
</head>
<body>
<?php
  include("files/navigation.php"); 
  include("DocLib.php");

  echo '<div class="content">';

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
      $qry = "SELECT * FROM Documents WHERE Name LIKE '%$targ%' $xtr";
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
      exec("grep -lr '" . $targ . "' Store", $greplst);
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
?>

</div>

<?php include("files/footer.php"); ?>
</body>
</html>

