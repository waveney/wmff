<?php
  include_once("fest.php");
  A_Check('SysAdmin');
  dostaffhead('Import Old Trade Data');
  global $YEAR,$db;
  include_once("TradeLib.php");
  include_once("DateTime.php");

  $TradTypes = Get_Trade_Types();
  $TradType = array_flip($TradTypes);

  $Oldstate = array('confirmed'=>3,'unconfirmed'=>1,'paid'=>5,'deposit'=>4,'declined'=>2,'denied'=>2);

  $Ttype = 1;
  if (!isset($_FILES['CSVfile'])) {
    echo '<div class="content"><h2>Import Trader Data From Mandy CSV</h2>';
    echo '<form method=post enctype="multipart/form-data">';
    echo "<input type=file name=CSVfile><br>";
    echo "Test Only: <input type=checkbox name=TestFull checked><br>";
    echo "<input type=submit name=Import value=Import><br></form>\n";
  } else {
    $TestOnly = $_POST['TestFull'];
    $F = fopen($_FILES["CSVfile"]["tmp_name"],"r");
    $headers = fgetcsv($F);
    foreach($headers as $i=>$d) $hindx[$d] = $i;

    while (($bts = fgetcsv($F)) !== FALSE) {
      $stuff=array();
      $brack='';
      foreach ($headers as $i=>$d) $stuff[$d] = $bts[$i];
//var_dump($bts);
    $rec = array();
    $yr = array();
    $rec['TradeType'] = $Ttype;
    if ($stuff['Email address']) {
      $em = $stuff['Email address'];
      $res = "SELECT * FROM Trade WHERE Email LIKE '%$em%'";
      $q = $db->query($res);
      if ($q->num_rows) {
	$orec = $q->fetch_assoc();
	if ($orec['Previous'] == 0) {
	  $orec['Previous'] = 1;
	  Put_Trader($orec);
	  echo "Updated " . $orec['Name'] . "<br>";
	} else {
	  echo "Trader " . $stuff['Activity Type'] . " already in database.<br>";
	}
      } else {
	$rec['Name'] = $stuff['Activity Type'];
	$rec['Contact'] = $stuff['Contact Name'];
	$rec['Email'] = $stuff['Email address'];
        if (preg_match('/^07/',$stuff['Contact No.'])) {
          $rec['Mobile'] = $stuff['Contact No.'];
        } else {
          $rec['Phone'] = $stuff['Contact No.'];
        }
        $rec['GoodsDesc'] = $stuff['Products Sold'];
        $rec['Previous'] = 1;

        if (!$TestOnly) $Tid = Insert_db('Trade',$rec);
        $yr['Tid'] = $Tid;
        $yr['Year'] = 2017;
        $yr['PitchSize0'] = $stuff['Pitch Size'];

        $yr['BookingState'] = 5;
        if (!$TestOnly) $TYid = Insert_db('TradeYear',$yr);

/*
echo $rec['Name'] . " ";
var_dump($rec);
var_dump($yr);
echo "<p>";
*/
      if (!$TestOnly) echo "Added " . $rec['Name'] . "<br>";
      }
    } else {
      if ($stuff['Activity Type'] == 'Food') $Ttype = 2;
      if ($stuff['Activity Type'] == 'Non-Food') $Ttype = 1;
      if ($stuff['Activity Type'] == 'Artisan') $Ttype = $TradType['Artisan'];
      if ($stuff['Activity Type'] == "Children's Activities") $Ttype = $TradType["Children's Activities"];
      if ($stuff['Activity Type'] == 'Charity Sunday') $Ttype = $TradType['Local Charity'];
      if ($stuff['Activity Type'] == 'Face Painting') $Ttype = $TradType['Face Painting'];
      if ($stuff['Activity Type'] == 'Street Pedlars') $Ttype = $TradType['Street Pedlars'];
      echo "Ignoring (for now) : " . $stuff['Activity Type'] . "<br>";
    }


  }
  }

  dotail();

?>
