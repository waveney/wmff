<?php
// Updates to data following on screen drag drops, returns info pane html
//    $("#Infomation").load("dpupdate.php", "D=" + dstId + "&S=" + srcId + "&Y=" + $("#DayId").text() + "&E=" + 
//			$("input[type='radio'][name='EInfo']:checked").val()	);
//  include("minimalfiles/header.php");
/* ids are
	E$CurOrder::	Empty box
	N$CurOrder::	Note id
	I$CurOrder::	Note input
	S$CurOrder:$tt:$id	Grid with entry
	M$CurOrder:$tt:$id	Note with entry
	J$CurOrder:$tt:$id	Note input entry
	Z0:Side:$id	Side Side
	Z0:Act:$id	Act Side
	Z0:Other:$id	Other Side
    $("#InformationPane").load("beupdate.php", "D=" + dstId + "&S=" + srcId + "&EV=" + $("#EVENT").text() + "&E=" + 
			$("input[type='radio'][name='EInfo']:checked").val()	);
*/

  include("fest.php");

  if (isset($_GET['D'])) {
    $dstId = $_GET['D'];  
    $note = $_GET['N'];  
    $Ev   = $_GET['EV'];

    preg_match('/(.)(\d*):(.*):(\d*)/',$dstId,$dstmtch);

    $dor = $dstmtch[2];
    $dtt = $dstmtch[3];
    $did = $dstmtch[4];

    $Other = db_get('BigEvent',"Event=$Ev AND EventOrder=$dor");
    if ($Other) {
      $Orig = $Other;
      if ($note) {
        $Other['Notes'] = $note;
        Update_db('BigEvent',$Orig,$Other);
      } else {
        db_delete('BigEvent',$Other['BigEid']);
      }
    } else {
      $new = array('Event'=>$Ev, 'Type'=>'Note', 'EventOrder'=>$dor, 'Notes'=>$note);
      Insert_db('BigEvent',$new);
    }

  }
?>

