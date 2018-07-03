<?php
  include_once("fest.php");
  A_Check('SysAdmin');

  dostaffhead("General Settings");

  global $EType_States,$TicketStates;
  include_once("DateTime.php");
  $Dates = array('PriceChange1','PriceChange2');

  echo "<div class='content'><h2>General Settings and Global Actions</h2>\n";
  
  function Put_General($now) {
    $y=$data['Year'];
    $Cur = Get_General($y);
    Update_db('General',$Cur,$now);
  }

  echo "<form method=post action='General.php'>\n";
  if (isset($_POST{'Year'})) { /* Response to update button */
    $ynum = $_POST{'Year'};
    Parse_DateInputs($Dates);
    if ($ynum > 0) {                                 // existing Year
      $Gen = Get_General($ynum);
      if (isset($_POST{'ACTION'})) {
        switch ($_POST{'ACTION'}) {
        case 'New Year' :
          break;
        }
      } else {
        Update_db_post('General',$Gen);
      }
    } else { /* New Year */
      $_POST{'Year'} = $YEAR;
      $ynum = Insert_db_post('General',$Gen);
    }
  } elseif (isset($_GET{'yearnum'})) {
    $ynum = $_GET{'yearnum'};
    $Gen = Get_General($ynum);
  } elseif (isset($_GET{'Create'})) {
    global $db;
    $ynum = $_GET{'Create'};
    Parse_DateInputs($Dates);
    $db->query("INSERT INTO General SET Year=$ynum");
    $Gen = Get_General($ynum);
  } else {
    $Gen = Get_General();
    if ($Gen) $ynum = $Gen['Year'];
  }

  $Gens = Get_Years();
//  var_dump($Gens);

//  echo "<!-- " . var_dump($Gen) . " -->\n";
  echo "<table width=90% border>\n";
    echo "<tr><td>Year:<td>";
      if (isset($ynum) && $ynum > 0) {
        echo $ynum . fm_hidden('Year',$ynum);
      } else {
        echo $YEAR;
        echo fm_hidden('Year',-1);
      }
// NOTE General contains LOTS of no longer used feilds - just ignore them
//    echo "<tr>" . fm_text('Version Number',$Gen,'Version') . "<td>Software Version Number - change will force css/js reload";
//    echo "<tr>" . fm_text('Prefix',$Gen,'Prefix') . "<td>Title prefix - used for testing only";
    echo "<tr><td>State of Family:<td>" . fm_select($EType_States,$Gen,'FamilyState') . "<td>Controls level of Participant interfaces";
    echo "<tr><td>State of Specials:<td>" . fm_select($EType_States,$Gen,'SpecialState') . "<td>";
    echo "<tr><td>State of Trade:<td>" . fm_select($EType_States,$Gen,'TradeState') . "<td>No effect yet";
    echo "<tr>" . fm_number1('Date of Friday',$Gen,'DateFri') . "<td>ie 8 for 8th of June.  It works out the rest from this\n";
    echo "<tr>" . fm_date('Date of Price Change 1',$Gen,'PriceChange1') . "<td>\n";
    echo "<tr>" . fm_date('Date of Price Change 2',$Gen,'PriceChange2') . "<td>\n";
    echo "<tr><td>Priced Complete Fri:" . fm_checkbox('',$Gen,'PriceComplete0') . "<td>This and all completes surpress more to come on tickets/events\n";
    echo "<tr><td>Priced Complete Sat:" . fm_checkbox('',$Gen,'PriceComplete1') . "<td>This and all completes surpress more to come on tickets/events\n";
    echo "<tr><td>Priced Complete Sun:" . fm_checkbox('',$Gen,'PriceComplete2') . "<td>This and all completes surpress more to come on tickets/events\n";
    echo "<tr><td>Ticket Control:<td>" . fm_select($TicketStates,$Gen,'TicketControl') . "<td>Master Ticketing control\n";

//    $comps = array('Ceildih','Session','Workshop','Concert','Family','Comedy','Special','Craft');

//    foreach($comps as $c) echo "<tr><td>$c Complete:" . fm_checkbox('',$Gen,$c . "Complete");
  echo "</table>\n";

  if ($ynum > 0) {
    echo "<Center><input type=Submit name='Update' value='Update'>\n";
    echo "</center>\n";

    // Last Year, // Current // Next | Create
    echo "<p><h2>Settings for ";
    if (isset($Gens[$ynum-1])) echo "<a href=General.php?yearnum=". ($ynum-1) . ">" . ($ynum-1) . "</a>, ";
    echo $ynum;
    if (isset($Gens[$ynum+1])) {
      echo ", <a href=General.php?yearnum=". ($ynum+1) . ">" . ($ynum+1) . "</a> ";
    } else {
      echo ", <a href=General.php?Create=". ($ynum+1) . ">Create " . ($ynum+1) . "</a> ";
    }
    echo "</h2>";
  } else { 
    echo "<Center><input type=Submit name=Create value='Create'></center>\n";
    echo "</form>\n";
  }


?>

</div>

<?php include_once("files/footer.php"); ?>
</body>
</html>
