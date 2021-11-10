<?php
  include_once("fest.php");
  A_Check('Steward');

  dostaffhead("Invite Dance", ["/js/clipboard.min.js", "/js/emailclick.js", "/js/InviteThings.js"]);

  include_once("DanceLib.php"); 
  global $YEAR,$PLANYEAR,$Coming_Colours,$Coming_idx,$Bespoke,$YEARDATA;
  $Invited = (isset($_REQUEST['INVITED'])? "&INVITED" :"");

  echo "<h2>" . ($Invited?"Ongoing":"Invite") . " Dance Sides $YEAR</h2>\n";

  if (Access('Staff','Dance')) echo "<div class=floatright style=text-align:right><div class=Bespoke>" .
       "Sending:<button class=BigSwitchSelected id=BespokeM onclick=Add_Bespoke()>Generic Messages</button><br>" .  
       "Switch to: <button class=BigSwitch id=GenericM onclick=Add_Bespoke()>Bespoke Messages</button></div>" .
       "<div class=Bespoke hidden id=BespokeMess>" .
       "Sending:<button class=BigSwitchSelected id=GenericM1 onclick=Remove_Bespoke()>Bespoke Messages</button><br>" .  
       "Switch to: <button class=BigSwitch id=BespokeM1 onclick=Remove_Bespoke()>Generic Messages</button></div>" .
       "</div>";
       
  echo "Click on column header to sort by column.  Click on Side's name for more detail and programme when available,<p>";

  echo "If you click on the email link, press control-V afterwards to paste the standard link into message.<p>";
  
  if ($YEAR != $PLANYEAR) echo "No messages can be sent unless you are Richard...";

  echo "<div id=InformationPane></div><p>\n";
  echo fm_hidden('Year',$YEAR);
  $Types = Get_Dance_Types(1);
  foreach ($Types as $i=>$ty) $Colour[strtolower($ty['SN'])] = $ty['Colour'];


  echo "<h2>";
  $Loc = 0;
  if (isset($_GET{'LOC'})) $Loc = $_GET{'LOC'};
  $Contact =0;
  if (isset($_GET{'CONT'})) $Contact = $_GET{'CONT'};
  if ($Loc == 0) echo "<a href=InviteDance?LOC=1" . ($Contact?"&CONT=1":"") . "&Y=$YEAR$Invited>Show Location</a> &nbsp; &nbsp; &nbsp; &nbsp;\n";
  if ($Contact == 0) echo "<a href=InviteDance?CONT=1" .($Loc?"&LOC=1":"") . "&Y=$YEAR$Invited>Show Contact</a>\n";
  echo "</h2>";

  $LastYear = $YEARDATA['PrevFest'];
  $flds = "ly.Invite AS LyInvite, ly.Coming AS LyComing, y.*, s.*";
  $SideQ = $db->query("SELECT $flds FROM Sides AS s LEFT JOIN SideYear as y ON s.SideId=y.SideId AND y.year='$YEAR' " .
                        "LEFT JOIN SideYear as ly ON s.SideId=ly.SideId AND ly.year='$LastYear' WHERE s.IsASide=1 AND s.SideStatus=0 ORDER BY SN");
  if ($Invited) {
    $col5 = $col6 = $col7 = '';
  } else {
    $col5 = "Invited $LastYear";
    $col6 = "Coming $LastYear";
    $col7 = "Invite $YEAR";
  }
  $col8 = "Messages Sent $YEAR";
  $col9 = "Coming $YEAR";
  if (Access('Staff','Dance')) $col10 = "Proforma Emails";

  if (Access('SysAdmin')) {
    echo "Debug: <span id=DebugPane></span><p>"; 
  }

  if (!$SideQ || $SideQ->num_rows==0) {
    echo "<h2>No Sides Found</h2>\n";
  } else {
    $coln = 1; // Start at 1 for select col
    echo "<div class=tablecont><table id=indextable border width=100%>\n";
    echo "<thead><tr>";
    echo "<th><input type=checkbox name=SelectAll id=SelectAll onchange=ToolSelectAll(event)>\n";
    echo "<th width=200><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Type</a>\n";
    if ($Contact) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Contact</a>\n";
    if ($Loc) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Location</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Web</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Email</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Notes</a>\n";
    if ($col5) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>$col5</a>\n";
    if ($col6) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>$col6</a>\n";
    if ($col7) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'O')>$col7</a>\n";
    if ($col8) echo "<th style='max-width:200'><a href=javascript:SortTable(" . $coln++ . ",'T')>$col8</a>\n";
    if ($col9) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>$col9</a>\n";
    if ($col10) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>$col10</a>\n";

    echo "</thead><tbody>";
    while ($fetch = $SideQ->fetch_assoc()) {
      $ComeIdx = (isset($fetch['Coming'])?$Coming_idx[$fetch['Coming']]:'');
      if ($Invited) {
        if (strstr($ComeIdx,'N')) continue;
        if (!isset($fetch['Invited'])) continue;
        if ($fetch['Invited'] == '' && $ComeIdx == '') continue;
      }
      $snum = $fetch['SideId'];
      echo "<tr>";
      echo "<td><input type=checkbox name=E$i class=SelectAllAble>";
      echo "<td><a href=AddPerf?sidenum=$snum&Y=$YEAR id=SideName$snum>" . $fetch['SN'] . "</a>";
      if ($fetch['SideStatus']) {
        echo "<td>DEAD";
      } else {
        $ty = strtolower($fetch['Type']);
        $colour = '';
        foreach($Types as $T) {
          if ($T['Colour'] == '') continue;
          $lct = "/" . strtolower($T['SN']) . "/";
          if (preg_match($lct,$ty)) {
            $colour = $T['Colour'];
            break;
          }
        }
        if ($colour) {
          echo "<td style='background:$colour;'>" . $fetch['Type'];
        } else {
          echo "<td>" . $fetch['Type'];
        }
      }
      if ($Contact) echo "<td>" . $fetch['Contact'];
      if ($Loc) echo "<td>" . $fetch['Location'];

      echo "<td>";
        if (strlen($fetch['Website'])>6) echo weblink($fetch['Website'],'Web','target=_blank');
      echo "<td>";
        if ($fetch['Email']) {
          if (Feature("EmailButtons")) {
             echo "<button type=button id=Email$snum onclick=ProformaSend('Dance_Blank',$snum,'Email','SendProfEmail',1)>Email</button>"; 
          } else echo linkemailhtml($fetch,'Side',(!$fetch['Email'] && $fetch['AltEmail']? 'Alt' : '' ));
        }
//      echo "<td>" . linkemailhtml($fetch,'Side',(!$fetch['Email'] && $fetch['AltEmail']? 'Alt' : '' ),'ReportTed(event)');
      
      echo "<td>";
      if ($fetch['Notes'] || $fetch['YNotes'] || $fetch['PrivNotes']) {
        $Htext = htmlspecialchars($fetch['Notes'] . "\n" . $fetch['YNotes'] . "\n" . $fetch['PrivNotes']);
        echo "<img src=images/icons/LetterN.jpeg width=20 title='$Htext'>";
      }
      if (!$Invited) {
        echo "<td>";
        if (isset($fetch['LyInvite'])) echo $Invite_States[$fetch['LyInvite']];

        if (isset($fetch['LyComing'])) {
          echo "<td style='background:" . $Coming_Colours[$fetch['LyComing']] . "'>";
          echo $Coming_States[$fetch['LyComing']] . "\n";
        } else {
          echo "<td>";
        }
        echo "<td>" . fm_select2($Invite_States,$fetch['Invite'],"Invite$snum",0,"id=Invite$snum onchange=ChangeInvite(event)");
      }
      echo "<td width=250 height=38 style='max-width:200;max-height:38;'>";
      echo "<div id=Vited$snum class=scrollableY>";
      if (isset($fetch['Invited'])) echo $fetch['Invited'];
      echo "</div>";

//      echo "<button type=button id=Ted$snum onclick=ReportTed(event)>Y</button><span id=Vited$snum>";
//      if (isset($fetch['Invited'])) echo $fetch['Invited'];
//      echo "</span>";
      
      if (isset($fetch['Coming'])) {
        echo "<td style='background:" . $Coming_Colours[$fetch['Coming']] . "'>";
        echo $Coming_States[$fetch['Coming']] . "\n";
      } else {
        echo "<td>";
      }

      echo "<td>";
      if (isset($fetch['Email']) && $fetch['Email'] && ($YEAR==$PLANYEAR || Access('SysAdmin'))) {
        if (isset($fetch['Coming']) && $fetch['Coming']) {
//        echo $Coming_idx[$fetch['Coming']];
          switch ($Coming_idx[$fetch['Coming']]) {
          case 'R':
          case 'P':
            // if invited & less than a month to mid feb show remind 1 month, else remind - not written
            echo "<button type=button id=Remind$snum class=ProfButton onclick=ProformaSend('Dance_Decide_Month',$snum,'Decide','SendProfEmail')" . 
                  Proforma_Background('Decide') . ">Decide</button>";         
            if ($YEAR=='2020') echo "<button type=button id=Change$snum class=ProfButton onclick=ProformaSend('Dance_Change_Dates',$snum,'Change','SendProfEmail')" .
                                      Proforma_Background('Change') . ">Change</button>";        
            break;
          
          case '':
          default:
            if ($fetch['Invited']) {
              echo "<button type=button id=Remind$snum class=ProfButton onclick=ProformaSend('Dance_Decide_Month',$snum,'Decide','SendProfEmail')" .
                                      Proforma_Background('Decide') . ">Decide</button>";
            }
            echo "<button type=button id=Invie$snum class=ProfSmallButton onclick=ProformaSend('Dance_Invite',$snum,'Invite','SendProfEmail')" . 
                Proforma_Background('Invite') . ($fetch['Invite']?'':' hidden ') . ">Invite</button>"; 
                     
            if ($YEAR=='2020') echo "<button type=button id=Change$snum class=ProfButton onclick=ProformaSend('Dance_Change_Dates',$snum,'Change','SendProfEmail')" .
                                      Proforma_Background('Change') . ">Change</button>";

            break;
        
          case 'Y':
            // Actions to be added
//          var_dump($fetch);
            if ($fetch['Insurance'] && $fetch['Mobile'] &&
                  ((($fetch['Performers'] > 0) && $fetch['Address']) || ($fetch['Performers'] < 0)) && 
                  ($fetch['Sat'] || $fetch['Sun'])) {
            } else {
              echo "<button type=button id=Detail$snum class=ProfButton onclick=ProformaSend('Dance_Details',$snum,'Details','SendProfEmail')" . 
                   Proforma_Background('Details') . ">Details!</button>"; 
            }
            if ($YEAR=='2020') echo "<button type=button id=Change$snum class=ProfButton onclick=ProformaSend('Dance_Change_Dates',$snum,'Change','SendProfEmail')" .
                                      Proforma_Background('Change') . ">Change</button>";
            
            break;
          
          case 'N':
          case 'NY':
            if ($YEAR=='2020') echo "<button type=button id=Change$snum class=ProfButton onclick=ProformaSend('Dance_Reinvite_Change_Dates',$snum,'Reinvite','SendProfEmail')" .
                                      Proforma_Background('Reinvite') . ">Reinvite</button>";

//            echo "Woof";
            break;
          }        
        } else {
          echo "<button type=button id=Invie$snum class=" . ( $fetch['Invited']?"ProfSmallButton":"ProfButton") . " onclick=ProformaSend('Dance_Invite',$snum,'Invite','SendProfEmail')" . 
                  Proforma_Background('Invite') . ($fetch['Invite']?'':' hidden ') . ">Invite</button>";
          if (!isset($fetch['Coming']) || $Coming_idx[$fetch['Coming']]=='') {
            echo "<button type=button id=Remind$snum class=ProfButton onclick=ProformaSend('Dance_Remind',$snum,'Remind','SendProfEmail')" . 
                  Proforma_Background('Remind') . ($fetch['Invited']?'':' hidden ') . ">Remind</button>";

        }
      }
      if (FestFeature('EnableDateChange')) echo "<button type=button id=Change$snum class=ProfButton onclick=ProformaSend('Dance_Reinvite_Change_Dates',$snum,'Change','SendProfEmail')" .
                                      Proforma_Background('Reinvite') . ">Cancel</button>";
      if (FestFeature('EnableCancelMsg')) echo "<button type=button id=Cancel$snum class=ProfButton onclick=ProformaSend('Dance_Cancel_Fest',$snum,'Cancel','SendProfEmail')" .
                                      Proforma_Background('Cancel') . ">Cancel</button>";

      if (isset($fetch['Coming'])) {
        if (FestFeature('SpecialInvite') && $fetch['Coming'] == 2) {
           echo "<button type=button id=SpecInvite$snum class=ProfButton onclick=ProformaSend('Dance_SpecInvite',$snum,'SpecInvite','SendProfEmail')" .
                                      Proforma_Background('SpecInvite') . ">Reinvite</button>";
          }
        
        if (FestFeature('SpecialInvite') && $fetch['Coming'] == 4) {
           echo "<button type=button id=SpecPossible$snum class=ProfButton onclick=ProformaSend('Dance_SpecPossible',$snum,'SpecPossible','SendProfEmail')" .
                                      Proforma_Background('SpecPossible') . ">RePossible</button>";
          }
        }

//          echo "Meow";
      } else {
        echo "No Email!";      
      }

//      for($i=1;$i<5;$i++) {
//        echo "<td>" . ($fetch["SentEmail$i"]?"Y":"");
//      }
    }
    echo "</tbody></table></div>\n";
    
      $Dtypes = Get_Dance_Types(0);
      echo "<div id=SelTools data-t1=Tool_Type,2 data-t2=Tool_Invite,8 data-t3=Tool_Coming,10 data-t4=Tool_Coming_Last,7></div>"; // Encode all tools below selectname,col to test
      echo "<b>Select: Type=" . fm_select($Dtypes,$_POST,'Tool_Type',1,' oninput=ToolSelect(event)') ;
      echo " Invite=" . fm_select($Invite_States,$_POST,'Tool_Invite',1,' oninput=ToolSelect(event)') ;    
      echo " Coming $PLANYEAR=" . fm_select($Coming_States,$_POST,'Tool_Coming',1,' oninput=ToolSelect(event)') ;    
      echo " Coming $LastYear=" . fm_select($Coming_States,$_POST,'Tool_Coming_Last',1,' oninput=ToolSelect(event)') . "</b><p>";    
    
    
    
    
  }
  dotail();
?>
