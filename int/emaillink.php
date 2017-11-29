<?php
//  Return email link
  include("fest.php");
  include_once("DanceLib.php");
  global $USER,$USERID;

  $id = $_GET['id'];
  $x = (isset($_GET['x']) ? $_GET['x'] : '');
  $t = (isset($_GET['t']) ? $_GET['t'] : 'Side');

  switch ($t) {
  case 'Side':
  default:
    $data = Get_Side($id);
    break;
  case 'Act':
    $data = Get_Act($id);
    break;
  case 'Other':
    $data = Get_Other($id);
    break;
  }

  global $YEAR;
  if (!isset($data[$xtr . "Email"])) exit;
  $email = $data[$xtr . 'Email'];
  if ($email == '') exit;

  $email = Clean_Email($email);
  $key = $data['AccessKey'];
  if (isset($data[$xtr .'Contact'])) { $name = firstword($data[$xtr .'Contact']); }
  else { $name = $data['Name']; }

  $ProgInfo = Show_Prog($t,$id);

  $link = "mailto:$email?from='" . $USER['Email'] .
	 "'&subject=" . urlencode("Wimborne Minster Folk Festival $YEAR and " . $data['Name']);

  $paste = 
         "$name,<p>PUT MESSAGE HERE<p>$ProgInfo<p>" .
	 	"If you wish you can record and update your status, " .
		"provide descriptions for our website and programme, photos and videos and " .
	 	"give more precise information and see the most up to date programming by following " .
	 	"<a href=http://wimbornefolk.co.uk/int/Direct.php?t=$type&id=$id&key=$key>this link</a>.<p>  " .
	 	"Regards " . $USER['Name'] . "<p>" ;

  echo json_encode(array('link' => $link,'paste' => $paste));

?>
