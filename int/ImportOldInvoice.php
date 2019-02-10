<?php

  include_once("fest.php");
  A_Check('SysAdmin');
  
  include_once("TradeLib.php");
  include_once("InvoiceLib.php");
  
  dostaffhead("Import Old Invoices");
  
  require 'vendor/autoload.php';

  use PhpOffice\PhpSpreadsheet\Spreadsheet;
  /* 
    Walk OldInvoices - find first xls for each company
    
    Read xls
    
    Find:
      Business - Look it up - if we have it and have a Sage Code defined skip company
      Find Sage Code used
      Find Contact Name
      Find Address
      
      If BZ exists - set all the data above not held, save, report, end for company
      
      Create BZ with details above, report, end for company
      
   */
  $Base = "Invoices/OldInvoices";
  $OldInv = opendir($Base);
  if (!$OldInv) echo "OldInv wont open<p>";
  while (($old2 = readdir($OldInv)) !== false) {
//    echo "$old2<br>";
    if (!is_dir("$Base/$old2")) continue;
    $old3 = opendir("$Base/$old2");
    while ($file2 = readdir($old3)) {
      $f = "$Base/$old2/$file2";
      if (is_dir($f)) continue;
      $suf = pathinfo($f,PATHINFO_EXTENSION);
      if ($suf != "xls") continue;
//      echo "<p>Processing: $f<p>";
      
      $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($f);
      $cellB9 = $spreadsheet->getActiveSheet()->getCell('B9')->getValue();
      $cellB8 = $spreadsheet->getActiveSheet()->getCell('B8')->getValue();
      $cellG13 = $spreadsheet->getActiveSheet()->getCell('G13')->getValue();
      $sageCode = preg_split('/\//',$cellG13)[0];

      $BZname = trim($cellB8 == ''?$cellB9:$cellB8);
      $sageCode = trim(preg_split('/\//',$cellG13)[0]);      
      $contact = trim($spreadsheet->getActiveSheet()->getCell('G12')->getValue());
      $lccont = strtolower($contact);
      $adr = [];
      for($i = ($cellB8 == ''?10:9);$i<=13;$i++) {
        $cell = $spreadsheet->getActiveSheet()->getCell("B$i")->getValue();
        if ($cell) $adr[] = $cell;
        }
      $address = implode(', ',$adr);
      
//      echo "BZ Name = $BZname - $sageCode - $contact - ";
      echo "$BZname - ";
            
      $Trad = Get_TraderByName(trim($BZname));
//var_dump($Trad);
      $change = 0;
      if ($Trad && isset($Trad['SN'])) {
        if ($Trad['SageCode'] != '') {
          if ($Trad['SageCode'] == $sageCode) {
            echo "Already in system ";
          } else {
            echo "Sagecodes different '" . $Trad['SageCode'] . "' - '$sageCode' ";
            $Trad['SageCode'] = $sageCode;
            $change = 1;
          }
        } else {
          echo "No Sagecode yet.<p>";
          $Trad['SageCode'] = $sageCode;
        }
        if ($Trad['Address'] == '') { $Trad['Address'] = $address; $change = 1; }
        if ($Trad['Contact'] == '') { $Trad['Contact'] = $contact; $change = 1; }
        else if (strtolower($Trad['Contact']) != $lccont) echo "Contacts different '" . $Trad['Contact'] . "' - '$contact' ";        
//          if ($change) Put_Trader($Trad); // commented to check workings        
        echo "<p>";        
      } else {
        echo "NOT IN SYSTEM YET<p>";
        $NewTrad = ['SN'=>$BZname, 'Contact'=>$contact, 'SageCode'=>$sageCode];
//        Insert_db("Trade",$NewTrad);
        
      }

      break 1;
    }
  }
   
  dotail();
?>
