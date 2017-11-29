<?php
  include_once("DanceLib.php"); 
  include_once("MusicLib.php"); 
  include_once("ProgLib.php"); 
  include_once("PLib.php"); 

function Show_Contract($snum,$mode=0) { // mode=-1 Draft,0 proposed, 1 freeze reason - see contractConfirm
  global $Mess,$Action,$MASTER,$Cat_Type,$YEAR,$THISYEAR,$DayList,$Event_Types;

  $FullDay = array('Friday', 'Saturday', 'Sunday');

  $str = "<div class=content900>\n";

  $Side = Get_Side($snum);
  $Sidey = Get_Actyear($snum,$YEAR);
  $Booked = Get_User($Sidey['BookedBy']);

  $str .= "<h2>Wimborne Minster Folk Festival - WimborneFolk.co.uk - Contract</h2>\n";

  if ($mode == 0) $str .= "<em><b>Proposed contract:</b></em><p>\n";
  if ($mode < 0) $str .= "<em><b>DRAFT contract:</b></em><p>\n";

  $str .= "Standard Agreement between Band/Artist/Performer & Employer.<p>\n";

  $str .= "This Agreement made as of " . date('d/m/Y',  ($Sidey['ContractDate']>0?$Sidey['ContractDate']:time())) . 
	" by and between the parties identified below.<p>\n";

  $str .= "In consideration for the following covenants, conditions, and promises, the Employer identified below agrees to
hire the below-identified Artist to perform an engagement and the Artist agrees to provide such performance
services, under the following terms and conditions:<p>\n";

  $str .= "This agreement for performance services is entered into by the performers(s) known as:<br>";
  $str .= "<b>" . $Side['Name'] . " </b>(now referred to as Artist) and : <b>" . $Booked['Name'] . "</b> for and on behalf of
Wimborne Minister Folk Festival (now referred to as Employer)<p>\n";

  $str .= "Performances:<p>";

  $Evs = Get_Events4Act($snum,$YEAR);
  $ETs = Get_Event_Types();
  $evc = $evd = $evv = 0;
  $riders = array();
  $evday = array(0,0,0);
  $pkday = array(0,0,0);
  $pkvens = array();
  $pkking = "";
  if ($Evs) {
    $Venues = Get_Venues(1);
    $str .= "<table border>";
    $str .= "<tr><td>Number<td>Event Type<td>Date<td>On Stage at<td>Start<td>Duration<td colspan=3>Where\n";
    foreach($Evs as $e) {
      $evc++;
      if ($e['SubEvent'] < 0) { $End = $e['SlotEnd']; } else { $End = $e['End']; };
      if (($e['Start'] != 0) && ($End != 0) && ($e['Duration'] == 0)) $e['Duration'] = timeadd2real($End, - $e['Start']);
      $str .= "<tr><td>$evc<td>" . $ETs[$e['Type']] . "<td>" . $DayList[$e['Day']] . " " . ($MASTER['DateFri']+$e['Day']) ."th June $YEAR";
      $str .= "<td>" . ($e['Start']? ( timecolon(timeadd2($e['Start'],- $e['Setup']) )) : "TBD" ) ;
      $str .= "<td>" . ($e['Start']?timecolon($e['Start']):"TBD");
      $str .= "<td>" . ($e['Duration']? ( $e['Duration'] . " minutes") :"TBD"); 
      $evd += $e['Duration'];
      if ($e['Duration'] == 0) $evv = 1;
      $str .= "<td>";
      $evday[$e['Day']]++;
      if ($e['Venue']) {
	$v = $Venues[$e['Venue']];
	$str .= SName($v) . "<br>";
        if ($v['Address']) $str .= $v['Address'] . "<br>" . $v['PostCode'] ."<br>";
        if ($v['Description']) $str .= $v['Description'];
	if ($v['MusicRider']) $riders[$v] = 1;
	if ($v['Parking']) {
	  $pkday[$e['Day']]++;
	  if (!isset($pkvens[$v['VenueId']])) {
	    $pkvens[$v['VenueId']] = 1;
	    if ($pking) $pking .= ", ";
	    $pking .= SName($v);
	  }
	}
      } else {
        $str .= "TBD";
      }
    } 
    $str .= "</table>\n";
  }

  $str .= "Total of $evc events, with a total duration of " . ($evv?"at least ":"") . "$evd minutes.<p>\n";

  $str .= "Total Fee: &pound;" . $Sidey['TotalFee'];
  if ($Sidey['OtherPayment']) $str .= " plus " . $Sidey['OtherPayment'];
  $str .= "<p>\n";

  $str .= "<b>BACS:</b> Sort Code: " . $Side['SortCode'] . " Account Number: " . $Side['Account'] . " Account Name : " . $Side['AccountName'] . "<p>\n";

  $str .= "ON ARRIVAL: Please report to Info Desk just inside the Allendale Centre 01202 887247 (manned from 2pm Friday)<p>\n";

  if ($Side['StagePA'] == 'None') {
    $str .= "If you have any PA/Technical requirments, please fill in the relevant section on your Acts pesonal record.<p>\n";
  } else {
    $str .= "Thankyou for filling in your PA/Technical requirments.<p>\n";
  }

  // Riders for Venues
  foreach ($riders as $v) {
    $str .= "<b>Rider for " . $Venues[$v]['Name'] . "</b>:" . $Venues[$v]['MusicRider'] . "<p>\n";
  }

  if (strlen($Sidey['Rider']) > 5) $str .= "<b>Rider:</b> " . $Sidey['Rider'] . "<p>\n";
  // Extra for supplied camping

  $faq = include("InnerMusicFAQ.php");

  if ($pking) {
    $allfree = 1;
    $freon = '';
    for ($i=0;$i<3;$i++) {
      if ($evday[$i] > 0 && $pkday[$i] == 0) $allfree = 0;
      if ($evday[$i] > 0 && $pkday[$i] != 0) {
	if ($freon) $freon .= " and ";
	$freon .= $FullDay[$i];
      }
    }

    if ($pkingand = preg_replace('/,([^,]*)$/'," and $1",$pking)) $pking = $pkingand;

    if ($allfree) { 
      $ptxt = "You may request free parking near $pking.";
      $faq = preg_replace("/<PARKING>.*<\/PARKING>/",$ptxt,$faq);
    } if ($freon) {
      $ptxt = "On $freon you may book free parking near $pking.<p>";
      $faq = preg_replace("/<PARKING>/",$ptxt,$faq);
    } else {
      // Should never get here
    }
  }

  $str .= $faq;

  if ($mode > 0) {
    $str .= "This contract was " . $ContractMethods[$mode] . " on " . date('d/m/y',$Sidey['ContractDate']) . "<P>\n";
  }

  $str .= "</div>";  
  return $str;
}

?>
