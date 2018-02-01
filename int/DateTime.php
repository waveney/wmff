<?php
// All time and date related stuff in one place

  date_default_timezone_set('GMT');

//      Put a Month eg Jan or January (will be end of) or a date as in 20/1  or 20th Jan or 20/1/18 or Jan 20th or Jan 20.";
//	If 1 / format is 20/1, if 2 / format is 20/1/18
//	If numbers dom = numbers else eom
//	Find LC Month String - Look for short form only?
//	returns 0 or best guess date
function Date_BestGuess($txt) {
  global $THISYEAR;
  $Months = array('jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec');
  $daysOfM = array(31,28,31,30,31,30,31,31,30,31,30,31);
  $yr = $day = $mnth = 0;
  if (preg_match('/(\d+)\/(\d+)\/?(\d+?)/',$txt,$mtch)) {
    $day = $mtch[1];
    $mnth = $mtch[2];
  } else if (preg_match('/(\d+)-(\d+)-(\d+)/',$txt,$mtch)) {
    $day = $mtch[3];
    $mnth = $mtch[2];
    $yr = $mtch[1];
  } else {
    $day = -1;
    if (preg_match('/(\d+)/',$txt,$mtch)) $day = $mtch[1];
    $lctxt = strtolower($txt);
    $mnth = 0;
    for ($i = 0; $i < 12; $i++) if (preg_match("/" . $Months[$i] . "/",$lctxt)) $mnth = $i+1;
    if (!$mnth) return 0;
    if ($day < 0) $day = $daysOfM[$mnth-1];
  } 

  if ($yr) return mktime(0,0,0,$mnth,$day,$yr);
  if ($mnth <= 6) return mktime(0,0,0,$mnth,$day,$THISYEAR);
  return mktime(0,0,0,$mnth,$day,$THISYEAR-1);
}

//	12:27, 1227, 12, 2, 2PM, Midday, 5 to 3, 2pm, 11am, 10 mins (just 10), 2 hours, 2 hrs, 1 hour 20 mins
//	if MINS==0 Returns time as 2400 format hhmm
//	if MINS !=0 trying to get N minutes - affects how 2,12 are passed returns ddd mins
//	morethan an earlier time that this should be more than helps sort out 11 is it 11 am or 11pm
function Time_BestGuess($txt,$MINS=0,$morethan=0) {
//  echo "Best guess of $txt ";
  $lt = strtolower($txt);
  $lt = preg_replace('/\s+/', '', $lt);
  $hr = $min = 0;
  if (!$txt) return $txt;
  if (preg_match('/(\d+):(\d+) *?(\a\a)/',$lt,$mtch)) {
    $hr = $mtch[1];
    if ($mtch[3] == 'pm') $hr+=12;
    $min = $mtch[2];
  } else if (preg_match('/(\d+):(\d+)/',$lt,$mtch)) {
    $hr = $mtch[1];
    if ($hr < 10) $hr+=12;
    $min = $mtch[2];
  } else if (preg_match('/(\d\d)(\d\d)/',$lt,$mtch)) {
    $hr = $mtch[1];
    $min = $mtch[2];
  } else if (($MINS==0) && preg_match('/(\d)(\d\d)/',$lt,$mtch)) {
    $hr = $mtch[1];
    $min = $mtch[2];
  } else if (preg_match('/(\d+)(\D+)(\d*)(\D*)$/',$lt,$mtch)) {
    $n1 = $mtch[1];
    $w1 = $mtch[2];
    $n2 = $mtch[3];
    $w2 = $mtch[4];
    switch ($w1) {
      case 'to':
	if ($n2) { //?????
          $hr = $n2;
          if ($morethan) {
            if ($hr*100 < $morethan) $hr+=12;
          } else {
             if ($hr < 10) $hr+=12;
          }
          $min = -$n1;	  
        }
        break;

      case 'pm':
        $hr = $n1+12;
	break;

      case 'am':
	$hr = $n1;
	break;

      case 'mins':
      case 'min':
	$min = $n1; 
	break;

      case 'hr':
      case 'hrs':
      case 'hours':
      case 'hour':
	$hr = $n1;
	if ($w2) {
	  switch ($w2) {
	  case 'mins':
	  case 'min':
	    $min = $n2;
            break;

	  default:
	    break;
          }
	} else if ($n2) $min = $n2;
	break;
    }
  } else if (preg_match('/(\d+)/',$lt,$mtch)) {
    if ($MINS) { 
      $min = $mtch[1]; 
    } else if ($morethan) {
      $hr = $mtch[1];
      if ($hr*100 < $morethan) $hr+=12;
    } else {
      $hr = $mtch[1];
      if ($hr < 10) $hr+=12;
    }
  } else if (preg_match('/(\D+)/',$lt,$mtch)) {
    $w1 = $mtch[1];
    if ($w1 == 'midday') $hr = 12;
    if ($w1 == 'midnight') $hr = 24;
  } else { 
    echo "Unknown format of time ... $lt<p>\n";
    return -1;
  }

  if ($MINS) {
    $ans = $hr*60+$min;
  } else {
    while ($min < 0) { $min += 60; $hr--; };
    while ($min >= 60) { $min -= 60; $hr++; };
    while ($hr <=0) $hr+=24;
    while ($hr >24) $hr-=24;
    $ans = ($hr*100 + $min);
  }
//  echo $ans . "<p>";
  return $ans;
}

function timeadd($time,$plus) { // times is in 24 hour format such as 1300, plus is minutes (can be negative)
  $t = (int)($time/100)*60 + $time%100 + $plus;
  return ((int)($t/60))*100+$t%60;
}

function timeround($time,$gran) { // round time to granularity gran eg 30
  $btim = (int)(((timereal($time) + $gran/2)/$gran))*$gran;
  return ((int)($btim/60))*100+$btim%60;
}

function timeadd2($time,$plus) { // both times and plus is in 24 hour format such as 1300
  $t = (int)($time/100)*60 + $time%100 + (int)($plus/100)*60 + $plus%100;
  return ((int)($t/60))*100+$t%60;
}

function timeadd2real($time,$plus) { // both times and plus is in 24 hour format such as 1300, result is in minutes
  $t = (int)($time/100)*60 + $time%100 + (int)($plus/100)*60 + $plus%100;
  return $t;
}

function timereal($time) {
  return (int)($time/100)*60 + $time%100; 
}

function timeformat($time) {
  return ((int)($time/60))*100+$time%60;
}

function timecolon($time) { // format as x:0y
  $h = (int)($time/100);
  return sprintf("%d:%02d",$h,$time%100);
}

function Parse_TimeInputs(&$feilds,&$minflds=NULL) {
  foreach($feilds as $fld) {
    if (isset($_POST[$fld])) $_POST[$fld] = Time_BestGuess($_POST[$fld]);
  }
  if ($minflds) foreach($minflds as $fld) {
    if (isset($_POST[$fld])) $_POST[$fld] = Time_BestGuess($_POST[$fld],1);
  }
}

function Parse_DateInputs(&$feilds) {
  foreach($feilds as $fld) {
    if (isset($_POST[$fld])) $_POST[$fld] = Date_BestGuess($_POST[$fld]);
  }
}
?>
