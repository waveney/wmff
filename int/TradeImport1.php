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

function MungeAddress($txt,$r=0) {
  $txt = preg_replace('/\n/',', ',$txt);
  $txt = preg_replace('/<br \/>/',', ',$txt);
  $txt = preg_replace('/\s+,/',',',$txt);
  $txt = preg_replace('/,+/',',',$txt);
  $txt = preg_replace('/(,\s+\w\w\d+ +\d+\w\w)/','',$txt);
  $txt = preg_replace('/(,\s+\w\w\d+\w\w)/','',$txt);
  return $txt;
}

function PostCode($txt) {
  $txt = preg_replace('/\n/',', ',$txt);
  $txt = preg_replace('/<br \/>/',', ',$txt);
  $txt = preg_replace('/, ,/',',',$txt);
  $txt = preg_replace('/, ,/',',',$txt);
  if (preg_match('/, +(\w\w\d+ +\d+\w\w)/',$txt,$mtch)) return strtoupper($mtch[1]);
  if (preg_match('/, +(\w\w\d+\w\w)/',$txt,$mtch)) return strtoupper($mtch[1]);
  return '';
}

  $res = $db->query("SELECT * FROM wmfftrade");
  while ($tr = $res->fetch_assoc()) {
    if (preg_match('/DUPLICATE/',$tr['location'])) continue;
    $rec = array();
    $rec['TradeType'] = 1;
    if ($tr['cat'] == 'food') {
      $rec['TradeType'] = $TradType['Food'];
    } else if ($tr['cat'] == 'artisan') {
      $rec['TradeType'] = $TradType['Artisan'];
    } else if ($tr['Charity']) {
      $rec['TradeType'] = $TradType['National Charity'];
    }

    $rec['SName'] = $tr['business'];
    if ($rec['SName'] == '') $rec['SName'] = $tr['contactname'];
    $rec['Contact'] = $tr['contactname'];
    $rec['Email'] = $tr['email'];
    $rec['GoodsDesc'] = $tr['products'];
    if (preg_match('/^07/',$tr['phone'])) {
      $rec['Mobile'] = $tr['phone'];
    } else {
      $rec['Phone'] = $tr['phone'];
    }
    $rec['Charity'] = $tr['charity'];
    $rec['BID'] = (strtolower($tr['bidlevy']) == 'yes'?1:0);    
    $rec['ChamberTrade'] = (strtolower($tr['chamber']) == 'yes'?1:0);    
    $rec['Previous'] = (((strtolower($tr['festivaltrader']) == 'yes') || ($tr['status'] == 'paid'))?1:0);
    $rec['Address'] = MungeAddress($tr['invoiceaddress'],($rec['SName']=='Chilled'?1:0));
    $rec['PostCode'] = PostCode($tr['invoiceaddress']);
    $rec['PublicHealth'] = $tr['health'];
    
    $Tid = Insert_db('Trade',$rec);

    $yr = array();
    $yr['Tid'] = $Tid;
    $yr['Year'] = 2017;
    $yr['PitchSize0'] = $tr['pitchsize'];
    $yr['Fee'] = $tr['pitchfee'];
    $yr['Date'] = Date_BestGuess($tr['applydate']);
    $yr['BookingState'] = $Oldstate[$tr['status']];
    $TYid = Insert_db('TradeYear',$yr);

/*
echo $rec['SName'] . " ";
var_dump($rec);
var_dump($yr);
echo "<p>";
*/
    echo "Added " . $rec['SName'] . "<br>";

  }

  dotail();

?>
