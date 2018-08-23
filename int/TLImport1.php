<?php
  include_once("fest.php");
  A_Check('SysAdmin');

  dostaffhead("Import TL Data take 1");

  include_once("DateTime.php");
  include_once("TLLib.php");
  include_once("DocLib.php");  

  if (!isset($_FILES['CSVfile'])) {
    echo '<div class="content"><h2>Import Timeline Data From CSV</h2>';
    echo '<form method=post enctype="multipart/form-data">';
    echo "<input type=file name=CSVfile><br>";
    echo "Test Only: <input type=checkbox name=TestFull checked><br>";
    echo "<input type=submit name=Import value=Import><br></form>\n";
  } else {
    $TestOnly = $_POST['TestFull'];
    $F = fopen($_FILES["CSVfile"]["tmp_name"],"r");
    $headers = fgetcsv($F);
    foreach($headers as $i=>$d) $hindx[$d] = $i;

//var_dump($headers); echo "<P>";

    $frst = mktime(0,0,0,9,1,2017);
    $week = 7*24*60*60;
    
    $All = Get_AllUsers(2);
    $AllA = [];
    foreach ($All as $usr) if ($usr['Abrev']) $AllA[$usr['Abrev']] = $usr['UserId'];

    while (($bts = fgetcsv($F)) !== FALSE) {
      if (!$bts[0]) continue;     
      $stuff=[];
      $brack='';
      foreach ($headers as $i=>$d) $stuff[$d] = $bts[$i];
//var_dump($bts); var_dump($stuff); echo "<p>";
      $rec = array();
      $rec['Title'] = $stuff['What'];
      $rec['Year'] = 2018;
      $rec['Created'] = time();
      $rec['CreatedBy'] = 1;
      $rec['Start'] = $frst + ($stuff['Start']-1)*$week;
      $rec['Due'] = $rec['Start'] + $stuff['End']*$week;
      $rec['Recuring'] = $stuff['Rec'];
      $rec['Assigned'] = (isset($AllA[$stuff['Who']])?$AllA[$stuff['Who']]:0);
      
      if (!$TestOnly) Insert_db('TimeLine',$rec);

/*
echo $rec['SName'] . " ";
var_dump($rec);
var_dump($yr);
echo "<p>";
*/
      echo "Added " . $rec['Title'] . "<br>";
    }
  }

  dotail();

?>
