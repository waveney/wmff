<?php

function ViewFile($file,$read=1,$targetname='',$Single=1) {
global $USERID;

  $path = pathinfo($file );
  $Dir = $path['dirname'];
  $BName = $path['basename'];
  $sfx = $path['extension'];
  
  $cachefile = "$Dir/CACHE$BName.html";

  static $tfnum = 0;



if (!file_exists($file)) Error_Page("Could not find file $file");
  Set_User();
  if (!$tfnum) system("rm Temp/$USERID.*");
  $tf = $USERID . "." . $tfnum . ".$sfx";
  $tfnum++;
  $id = "Embed$tfnum";
  $onload = ($Single?'':" onload=setIframeHeight(this.id) ");

if ($read) { // Attempt to read rather than download

  switch (strtolower($sfx)) {
  case 'pdf':

   if ($Single) {
      header('Content-Description: File Transfer');
      header('Content-Type: application/pdf');
      header("Content-Disposition: inline; filename='$BName'");
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize($file));
      readfile($file);
      exit;
    }
    
    copy($file,"Temp/$tf"); // No $onload as it does not work...
    echo "<iframe id=$id src='/js/ViewerJS/#/int/Temp/$tf' width=100%  height=" . ($Single?"800":"100%") . " ></iframe>";
    return;

/*    
    // Duff block...
     elseif (file_exists($cachefile)) {
      copy("$file.html","Temp/$tf.html");    
      echo "<iframe id=$id src='Temp/$tf.html' width=100%  height=" . ($Single?"800":"100%") . " $onload></iframe>";
      return;
    } else {
      copy($file,"Temp/$tf");    
      echo '<iframe id=' . $id . ' src="https://docs.google.com/gview?url=https://' . $_SERVER['SERVER_NAME'] . "/int/Temp/$tf" . 
           '&embedded=true" style="width:100%;" frameborder="0" ' . $onload . '></iframe>';
      return;
    }
*/

  case 'doc':
  case 'docx':
  case 'docmi':
  case 'dotm':
  case 'dotx':
  case 'xls':
  case 'xlsx':
  case 'xlsb':
  case 'xlsm':
  case 'pptx':
  case 'ppsx':
  case 'ppt':
  case 'pps':
  case 'pptm':
  case 'potm':
  case 'ppam':
  case 'potx':
  case 'ppsm':
    if ($Single) dohead("Show Office File");
    
    if (!$Single && (file_exists($cachefile))) {
      copy("$file.html","Temp/$tf.html");    
      echo "<iframe id=$id src='Temp/$tf.html' width=100%  height=" . ($Single?"800":"100%") . " $onload></iframe>"; 
    } else {
      copy($file,"Temp/$tf");
      echo "<iframe id=$id src='https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fwimbornefolk.co.uk%2Fint%2FTemp%2F$tf' width=100% height=" .  
          ($Single?"800":"100%") . " $onload></iframe>";
      if ($Single) dotail();
    }
    return;

  case 'jpg':
  case 'jpeg':
  case 'png':
    if ($Single) dohead("Show Image File");
    copy($file,"Temp/$tf");
    echo "<img src=Temp/$tf width=800\n";
    echo ">";
    if ($Single) dotail();
    return;

  case 'html':
  case 'htm':
    if ($Single) dohead("Show HTML File");
    copy($file,"Temp/$tf");
    echo "<iframe id=$id src='Temp/$tf' width=100%  height=" . ($Single?"800":"100%") . " $onload></iframe>";
    if ($Single) dotail();
    return;

  case 'txt':
    if ($Single) dohead("Show Text File");
    copy($file,"Temp/$tf");
    echo "<iframe id=$id src='Temp/$tf' width=100%  height=" . ($Single?"800":"100%") . " $onload></iframe>";
    if ($Single) dotail();
    return;
 
  case 'sql':
    if ($Single) dohead("Show SQL File");
    copy($file,"Temp/$tf.txt");
    echo "<iframe id=$id src='Temp/$tf.txt' width=100% height=" . ($Single?"800":"100%") . " $onload></iframe>";
    if ($Single) dotail();
    return;
 

  default : // Drop through to download
    if (!$Single) echo "Can not display $file<p>";
  }
} 

if ($targetname) $BName=$targetname;

//  down load if not read or no handler available
  header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header("Content-Disposition: attachment; filename='$BName'");
  header('Expires: 0');
  header('Cache-Control: must-revalidate');
  header('Pragma: public');
  header('Content-Length: ' . filesize($file));

  readfile($file);

}

// This is used to cache PDF and MS files so subsequent viewing is simpler and not cross platform
// dOES NOT DO AS WANTED - leaving code around for now.
function Cache_File($file) {
  global $USERID;
  $path = pathinfo($file );
  $Dir = $path['dirname'];
  $BName = $path['basename'];
  $sfx = $path['extension'];
  
  static $tfnum = 0;
   
  $cachefile = "$Dir/CACHE$BName.html";
  Set_User();
  if (!$tfnum) system("rm Temp/$USERID.*");
  $tf = $USERID . "." . ($tfnum++) . ".$sfx";

  switch ($sfx) {

  case 'pdf':
    copy($file,"Temp/$tf");
    $cached = file_get_contents('https://docs.google.com/gview?url=https://' . $_SERVER['SERVER_NAME'] . "/int/Temp/$tf&embedded=true");
    file_put_contents("$cachefile",$cached);
    return $cached;
  
  case 'doc':
  case 'docx':
  case 'docmi':
  case 'dotm':
  case 'dotx':
  case 'xls':
  case 'xlsx':
  case 'xlsb':
  case 'xlsm':
  case 'pptx':
  case 'ppsx':
  case 'ppt':
  case 'pps':
  case 'pptm':
  case 'potm':
  case 'ppam':
  case 'potx':
  case 'ppsm':
    copy($file,"Temp/$tf");
    $cached = file_get_contents('https://view.officeapps.live.com/op/view.aspx?src=https://' . $_SERVER['SERVER_NAME'] . "/int/Temp/$tf");
    file_put_contents("$cachefile",$cached);
    return $cached;

  default: // Not cached
    return; 
  }
}
?>
