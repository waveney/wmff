<?php

// Fudge to get email working I hope

function SendEmail($to,$sub,$letter) {
  $url = 'http://www.wimbornefolk.org/RemoteEmail.php';
  $data = array('TO' => $to, 'SUBJECT' => $sub, 'CONTENT'=>$letter, 'KEY' => 'UGgugue2eun23@');

  // use key 'http' even if you send the request to https://...
  $options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
  );
  $context  = stream_context_create($options);
  $result = file_get_contents($url, false, $context);
  if ($result === FALSE) { /* Handle error */ }
}

SendEmail("richardjproctor42@gmail.com","test message","Test message via other domain");
echo "Done!";

?>
