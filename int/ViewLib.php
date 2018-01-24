<?php

function ViewFile($file,$read=1) {
global $USERID;

$sfx = pathinfo($file,PATHINFO_EXTENSION );
$base = basename($file);

if (!file_exists($file)) Error_Page("Could not find file $file");

  system("rm Temp/$USERID.*");
  $tf = $USERID . "." . time() . ".$sfx";

if ($read) { // Attempt to read rather than download

  switch (strtolower($sfx)) {
  case 'pdf':
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header("Content-Disposition: inline; filename='$base'");
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;

  case 'doc':
  case 'docx':
  case 'docmi':
  case 'dotm':
  case 'dotx':
  case 'xls':
  case 'xlsx':
  case 'xlsb':
  case 'xlsm':
  case 'csv':
  case 'pptx':
  case 'ppsx':
  case 'ppt':
  case 'pps':
  case 'pptm':
  case 'potm':
  case 'ppam':
  case 'potx':
  case 'ppsm':
    dohead("Show Office File");
    copy($file,"Temp/$tf");
    echo "<iframe src='https://view.officeapps.live.com/op/view.aspx?src=http%3A%2F%2Fwimbornefolk.co.uk%2Fint%2FTemp%2F$tf'";
    echo " width=100% height=800";
    echo "></iframe>";
    dotail();
    exit;

  case 'jpg':
  case 'jpeg':
  case 'png':
    dohead("Show Image File");
    copy($file,"Temp/$tf");
    echo "<img src=Temp/$tf width=800>\n";
    dotail();
    exit;

  case 'html':
  case 'htm':
    dohead("Show HTML File");
    copy($file,"Temp/$tf");
    echo "<iframe src='Temp/$tf' width=100% height=800></iframe>";
    dotail();
    exit;

  case 'txt':
  case 'sql':
    dohead("Show Text File");
    copy($file,"Temp/$tf");
    echo "<iframe src='Temp/$tf' width=100% height=800></iframe>";
    dotail();
    exit;
 

  default : // Drop through to download
  }
} 
//  down load if not read or no handler available
  header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header("Content-Disposition: attachment; filename='$base'");
  header('Expires: 0');
  header('Cache-Control: must-revalidate');
  header('Pragma: public');
  header('Content-Length: ' . filesize($file));

  readfile($file);

}
?>
