<?php
// Participant Display Lib - Generalises Show_Side etc

function Show_Part($Side,$CatT='',$Mode=0,$Form='DanceEdit.php') { // if Cat blank look at data to determine type.  Mode=0 for public, 1 for ctte
  global $MASTER,$Side_Statuses,$Importance,$Surfaces,$Surface_Colours,$Noise_Levels,$Noise_Colours,$Share_Spots,$Mess,$Action,$ADDALL,$CALYEAR,$PLANYEAR,$YEAR,$OlapTypes,$OlapCats,$OlapDays;
  if ($CatT == '') {
    $CatT = ($Side['IsASide'] ? 'Side' : $Side['IsAnAct'] ? 'Act' : 'Other');
  } else {
    if ($Side['IsASide'] || $Side['IsAnAct'] || $Side['IsOther']) {
    } else {
      if ($CatT == 'Side') $Side['IsASide']=1;
      if ($CatT == 'Act') $Side['IsAnAct']=1;
      if ($CatT == 'Other') $Side['IsOther']=1;
    }
  }

  $Mstate = ($PLANYEAR >= $CALYEAR && $PLANYEAR == $YEAR);

  Set_Side_Help();
  $snum=$Side['SideId'];
  if ($Side['IsAnAct']) Add_Act_Help();
  if ($Side['IsOther']) Add_Act_Help();
  $Sidey = Get_SideYear($snum);

  if ( isset($Side['Photo']) && ($Side['Photo'])) echo "<img class=floatright src=" . $Side['Photo'] . " height=80>\n";
  echo "<input  class=floatright type=Submit name='Update' value='Save Changes' form=mainform>";
  if ($Mode && isset($Side['Email']) && strlen($Side['Email']) > 5) {
    echo "If you click on the ";
    if (isset($Side['HasAgent']) && $Side['HasAgent']) {
      echo linkemailhtml($Side,'Side','Agent','Agents');
      if (isset($Side['Email'])) echo " or contacts " . linkemailhtml($Side,'Side','!!');
    } else {
      if (isset($Side['Email'])) echo linkemailhtml($Side,'Side','!!');
    }
    if (isset($Side['AltEmail']) && $Side['AltEmail']) echo " or " . linkemailhtml($Side,'Side','Alt');
    echo ", press control-V afterwards to paste the <button type=button onclick=Copy2Div('Email$snum','SideLink$snum')>standard link</button>";

// ADD CODE TO ONLY PROVIDE PROGRAMME WHEN AVAIL - Dance only?
    if ($Side['IsASide']) echo " and <button type=button onclick=Copy2Div('Email$snum','SideProg$snum')>programme</button> into message.";
    echo "<p>\n";
  }

  $Adv = '';
  $Imp = '';
  if ($Mode) {
    echo "<span class=NotSide>Fields marked are not visible to participants.</span>";
    echo "  <span class=NotCSide>Marked are visible if set, but not changeable by participants.</span>";
  } else {
    $Adv = ''; // 'class=Adv'; // NEEDS MODING FOR NON DANCE
    if ($Mstate && $Side['IsASide'] && isset($Sidey['Coming']) && $Sidey['Coming']==2 ) {
      echo "<h2 class=floatright>You have <span id=ImpC>0</span> of 4 <span class=red>Most Important</span> Dance things filled in </h2>";
      $Imp = 'class=imp';
    }
    echo "Please keep this information up to date, even if you are not coming so we can invite you in the future.";
  }
  $snum = $Side['SideId'];

//********* PUBLIC

  echo "<form method=post id=mainform enctype='multipart/form-data' action=$Form>";
  echo "<table width=90% border class=SideTable>\n";
    echo "<tr><th colspan=8><b>Public Information</b>" . Help('PublicInfo');
    echo "<tr>" . fm_text(($Side['IsASide']?'Team Name':'Act Name'), $Side,'SName',3,'','autocomplete=off onchange=nameedit(event) oninput=nameedit(event) id=SName');
      $snx = 'class=ShortName';
      if (((isset($Side['SName'])) && (strlen($Side['SName']) > 20) ) || (isset($Side['ShortName']) && strlen($Side['ShortName']) != 0)) { 
        if (strlen($Side['ShortName']) == 0) $Side['ShortName'] = substr($Side['SName'],0,20);
      } else {
        $snx .= ' hidden';
      }
      echo fm_text('Grid Name', $Side,'ShortName',1,$snx,$snx . " id=ShortName") . "\n";
      echo fm_text('Type', $Side,'Type') . "\n";

    if ($Side['IsASide']) echo "<tr>" . fm_textarea('Costume Description <span id=CostSize></span>',$Side,'CostumeDesc',7,1,'',
                        'maxlength=150 oninput=SetDSize("CostSize",150,"CostBlurb") id=CostBlurb'); 
    echo "<tr>" . fm_textarea('Short Blurb <span id=DescSize></span>',$Side,'Description',7,1,'',
                        'maxlength=150 oninput=SetDSize("DescSize",150,"ShortBlurb") id=ShortBlurb'); 
    echo "<tr>" . fm_textarea('Blurb for web',$Side,'Blurb',7,2,'', 'size=2000' ) . "\n";
    echo "<tr>";
      if (isset($Side['Website']) && strlen($Side['Website'])>1) {
        echo fm_text(weblink($Side['Website']),$Side,'Website');
      } else {
        echo fm_text('Website',$Side,'Website');
      };
      echo fm_text('Recent Photo',$Side,'Photo',1,'style="min-width:145;"'); 

      echo "<td colspan=4>Select Photo file to upload:";
      echo "<input type=file $ADDALL name=PhotoForm id=PhotoForm onchange=document.getElementById('PhotoButton').click()>";
      echo "<input hidden type=submit name=Action value=Photo id=PhotoButton>";
      if ($Mess && $Action == 'Photo') echo "<br>$Mess\n";
    echo "<tr>";
      if (isset($Side['Video']) && $Side['Video'] != '') {
        echo fm_text("<a href=" . videolink($Side['Video']) . ">Recent Video</a>",$Side,'Video',1,$Adv);
      } else {
        echo fm_text('Recent Video',$Side,'Video',1,$Adv);
      };
      echo fm_text(Social_Link($Side,'Facebook' ),$Side,'Facebook');
      echo fm_text(Social_Link($Side,'Twitter'  ),$Side,'Twitter');
      echo fm_text(Social_Link($Side,'Instagram'),$Side,'Instagram');

//********* PRIVATE

    echo "<tr><th colspan=8><b>Private Information</b>" . Help('PrivateInfo');
    if ($Mode) {
      echo "<tr><td class=NotSide>Id:";//<td class=NotSide>";
        if (isset($snum) && $snum > 0) {
             echo $snum . fm_hidden('SideId',$snum);
          echo fm_hidden('Id',$snum);
        } else {
          echo fm_hidden('SideId',-1);
          echo fm_hidden('Id',-1);
        }
        echo "<td class=NotSide colspan=2>Importance:" . fm_select($Importance, $Side,'Importance');
        echo fm_text('Where found',$Side,'Pre2017',1,'class=NotSide','class=NotSide'); // This sort of info from SideYear in future
//        echo "<td class=NotSide>Last Checked:" . help('DataCheck'] . "<td class=NotSide>" . $Side['DataCheck'] . "\n";
//        if (Access('SysAdmin')) {
          echo "<td class=NotSide colspan=2>" . fm_checkbox('Dance Side',$Side,'IsASide');
          echo fm_checkbox('Music Act',$Side,'IsAnAct') . fm_checkbox('Other',$Side,'IsOther');
//        }
        echo "<td class=NotSide>State:" . fm_select($Side_Statuses,$Side,'SideStatus') . "\n";
    } else {
      echo fm_hidden('SideId',$snum);
      echo fm_hidden('Id',$snum);
    }
    if ($Mode == 0 || !Access('SysAdmin')) {
      echo fm_hidden('IsASide',$Side['IsASide']);
      echo fm_hidden('IsAnAct',$Side['IsAnAct']);
      echo fm_hidden('IsOther',$Side['IsOther']);
    }

    echo "<tr id=AgentDetail " . (isset($Side['HasAgent']) && $Side['HasAgent']?"":"hidden") . ">";
      echo fm_text('<span id=AgentLabel>Agent</span>',$Side,'AgentName');
      echo fm_text1('Email',$Side,'AgentEmail',2);
      echo fm_text('Phone',$Side,'AgentPhone');
      echo fm_text('Mobile',$Side,'AgentMobile');

    echo "<tr>" . fm_text('<span id=ContactLabel>Contact</span>',$Side,'Contact');
      echo fm_text1('Email',$Side,'Email',2);
      echo fm_text('Phone',$Side,'Phone');
      echo fm_text('Mobile',$Side,'Mobile',1,$Imp,'onchange=updateimps()') . "\n";
    echo "<tr>" . fm_text('Address',$Side,'Address',5,$Imp,'onchange=updateimps()');
      echo fm_text('Post Code',$Side,'PostCode')."\n";
    echo "<tr $Adv>" . fm_text('Alt Contact',$Side,'AltContact');
      echo fm_text1('Alt Email',$Side,'AltEmail',2);
      echo fm_text('Alt Phone',$Side,'AltPhone');
      echo fm_text('Alt Mobile',$Side,'AltMobile')."\n";
//    echo "<tr $Adv>" . fm_text('Alt Address',$Side,'AltAddress',5,$Imp,'onchange=updateimps()');
//      echo fm_text('Alt Post Code',$Side,'AltPostCode')."\n";
    if ($Side['IsASide']) {
      echo "<tr $Adv>" . fm_textarea('Requests',$Side,'Likes',3,1);
        echo fm_text('Animal',$Side,'MorrisAnimal');
      echo "<tr><td>Surfaces:" . help('Surfaces') . "<td colspan=3>";
        for($st=1;$st<=5;$st++) {
          $surf = $Surfaces[$st];
          echo "<span style='Background:" . $Surface_Colours[$st] . ";padding:4'>" .fm_checkbox($surf,$Side,"Surface_$surf") . "</span>";
        };
        echo "<td>Shared Spots:<td>" . fm_select($Share_Spots,$Side,'Share');
        if (!isset($Side['NoiseLevel'])) $Side['NoiseLevel']=0;
        echo "<td colspan=2 $Adv>" . fm_radio("Music Volume",$Noise_Levels,$Side,'NoiseLevel','',0,'','',$Noise_Colours);
      echo "<tr $Adv>";
        echo fm_textarea('Workshops',$Side,'Workshops',3,1);
      if ($Mode) echo "<td class=NotSide>" . fm_checkbox("Need Bank",$Side,'NeedBank');
    };

    if (!$Side['IsASide'] || (isset($Side['NeedBank']) && $Side['NeedBank'])) { 
      echo "<tr><td>Bank Details:" . help('Bank');
        echo fm_text('Sort Code',$Side,'SortCode');
        echo fm_text('Bank Account Number',$Side,'Account');
        echo fm_text('Account Name',$Side,'AccountName');
        echo "<td>" . fm_checkbox('Are you VAT registered',$Side,'VATreg');
    }

// PA 
    echo "<tr " . (($Side['IsASide'] && !$Side['IsAnAct'] && !$Side['IsOther'])?$Adv:"") . ">";
      if (!isset($Side['StagePA']) || ($Side['StagePA'] == '')) $Side['StagePA'] = 'None';
      echo "<td>PA Requirements:";
      $f = ($Side['StagePA'] == '@@FILE@@');  // This does not use fm_radio as it has many speccial cases
      echo "<td><label for=StagePAtext>Text</label> <input type=radio $ADDALL name=StagePAtext value=1 onchange=setStagePA(1) id=StagePAtext " . ($f?"":"checked") . "> " .
           "<label for=StagePAfile>File</label> <input type=radio $ADDALL name=StagePAtext value=2 onchange=setStagePA(0) id=StagePAfile " . ($f?"checked":"") . ">" .
           Help("StagePA");
      echo "<td id=StagePAtextF colspan=5" . ($f?' hidden':'') . " >" . fm_basictextarea($Side,'StagePA',5,1,'id=StagePA');
      echo "<td id=StagePAFileF" . ($f?'':' hidden') . " colspan=4>";
      $files = glob("PAspecs/$snum.*");
      if ($files) {
        $Current = $files[0];
        $Cursfx = pathinfo($Current,PATHINFO_EXTENSION );
        if (file_exists("PAspecs/$snum.$Cursfx")) {
          echo "Have been uploaded.  <span id=ViewPA> <a href=ShowFile.php?l=PAspecs/$snum.$Cursfx>View</a></span> &nbsp; &nbsp; &nbsp; ";
        }
      }
      echo "Select PA requirements file to upload:";
      echo "<input type=file name=PASpec id=PASpec onchange=document.getElementById('PASpecButton').click() style='color:transparent;'  title='to upload'>";
      echo "<input hidden $ADDALL type=submit name=Action value=PASpecUpload id=PASpecButton>";
      if ($Mess && $Action == 'PASpecUpload') echo "<br>$Mess\n";
    if (!$Side['IsASide']) { 
      echo "<td>" . fm_checkbox("Has Agent",$Side,'HasAgent','onchange=AgentChange(event)');
    }

// Members
    if ($Side['IsAnAct']) { // May need for Other
      $Band = Get_Band($snum);      
      $BandPerRow=7;
      $Curband = $Band? count($Band) : 0;
      $Rows = max(1,ceil($Curband/$BandPerRow));
      $colcnt = 0;
      $row = 0;
      $bi = 0;
      echo "<tr id=BandRow$row><td id=BandMemRow1 rowspan=$Rows>Band Members: <button type=button onclick=AddBandRow($BandPerRow)>+</button>";
      if (is_array($Band)) {
        foreach ($Band as $B) {
            if ($colcnt >= $BandPerRow) {
            $row++;
            echo "<tr id=BandRow$row>";
            $colcnt = 0;
          }
          echo "<td>" . fm_textinput("BandMember$bi:" . $B['BandMemId'],$B['SName'],'onchange=BandChange(event)');
          $colcnt++;
          $bi++;
        }
      }
      while ($colcnt < $BandPerRow) {
        echo "<td>" . fm_textinput("BandMember" . ($bi++) . ":0",'','onchange=BandChange(event)');
        $colcnt++;
      }
      echo "<tr hidden id=AddHere></tr>\n";
    }

// OVERLAPS
    echo "<tr>" . fm_textarea('Shared Performers',$Side,'Overlaps',7,2);
    if ($Mode) { // only ctte can build rule sets
      $olaps = Get_Overlaps_For($snum);
      $rows = count($olaps)+1;
      $SideList=Sides_All($snum);
      $ActList=Act_All();
      $OtherList=Other_All();
      $row = 0;
      echo "<tr id=OverlapRow$row class=NotSide><td rowspan=$rows class=NotSide>Overlap Rules: \n"; //<button type=button onclick=AddOverlapRow()>+</button>\n";
      for ($i = 0; $i < $rows; $i++) {
        $O = (isset($olaps[$i]) ? $olaps[$i] : ['Sid1'=>$snum,'Cat2'=>0]);
        $Other =  ($O['Sid1'] == $snum)?'Sid2':'Sid1';
        $OtherCat =  ($O['Sid1'] == $snum)?'Cat2':'Cat1';
        if ($i) echo "<tr id=OverlapRow$i class=NotSide>";
        echo "<td colspan=7 class=NotSide>Type: " . fm_select($OlapTypes,$O,'OType',0,'',"OlapType$i") . 
                fm_checkbox("Major",$O,'Major','',"OlapMajor$i") . 
                fm_radio(" With",$OlapCats,$O,$OtherCat,'onchange=OlapCatChange(event,###F,###V)',0,'',"OlapCat$i") . 
                fm_select($SideList,$O,$Other,1,"id=OlapSide$i " .($O[$OtherCat]>0?'hidden':''),"OlapSide$i") . 
                fm_select($ActList,$O,$Other,1,"id=OlapAct$i " .($O[$OtherCat]!=1?'hidden':''),"OlapAct$i") . 
                fm_select($OtherList,$O,$Other,1,"id=OlapOther$i " .($O[$OtherCat]!=2?'hidden':''),"OlapOther$i") .
                " On Days: " . fm_select($OlapDays,$O,'Days',0,'',"OlapDays$i") . 
                fm_checkbox("Rule Active",$O,'Active','',"OlapActive$i") . "\n";
      } 
    }

    if ($Mode) {
      echo "<tr>" . fm_text('Location',$Side,'Location',2,'class=NotSide');
      if (Access('SysAdmin')) {
        echo fm_nontext('Access Key',$Side,'AccessKey',3,'class=NotSide','class=NotSide'); 
        if (isset($Side['AccessKey'])) {
          echo "<td class=NotSide><a href=Direct.php?id=$snum&key=" . $Side['AccessKey'] . "&Y=$YEAR>Use</a>" . help('Testing');
        }
      }
    }

    if ($Mode) {
      echo "<tr>" . fm_textarea('Notes',$Side,'Notes',7,2,'class=NotSide','class=NotSide');
    }
  echo "</table>\n";
}

//******************************************************* SIDE YEAR ***********************************************
// This needs modification for non dance
function Show_Part_Year($snum,$Sidey,$year=0,$CatT='',$Mode=0) { // if Cat blank look at data to determine type.  Mode=0 for public, 1 for ctte
  global $YEAR,$CALYEAR,$PLANYEAR,$MASTER,$Invite_States,$Coming_States,$Coming_Colours, $Mess,$Action,$ADDALL,$Invite_Type;
  global $InsuranceStates,$Book_State,$Book_States,$Book_Colours,$ContractMethods;
  if ($year==0) $year=$YEAR;
  if ($CatT == '') {
    $CatT = ($Side['IsASide'] ? 'Side' : $Side['IsAnAct'] ? 'Act' : 'Other');
  } else {
    if ($CatT == 'Side') $Side['IsASide']=1;
    if ($CatT == 'Act') $Side['IsAnAct']=1;
    if ($CatT == 'Other') $Side['IsOther']=1;
  }
  $Side=Get_Side($snum);

  Set_Side_Year_Help();
  if ($CatT != 'Side') Add_Act_Year_Help();

  $Mstate = ($PLANYEAR >= $CALYEAR && $PLANYEAR == $YEAR);

  $Adv = '';
  $Imp = '';
  if ($Mode) {
    if ($year < $PLANYEAR) { // Then it is historical - no changes allowed
      fm_addall('disabled readonly');
    }
  } else {
    $Adv = 'class=Adv';
    if ($Mstate)  $Imp = 'class=imp';
  }

  $Request = 0;
  if ($Mode == 0 && (!isset($Sidey['Coming']) || $Sidey['Coming'] == 0) && (!isset($Sidey['Invite']) || $Sidey['Invite'] >= $Invite_Type['No'])) {
    if ($YEAR >= $PLANYEAR) echo "<h2><a href=DanceRequest.php?sidenum=$snum&Y=$YEAR>Request Invite for $YEAR</a></h2>";
    $Request = 1;
  } 
    
//var_dump($Sidey);var_dump($Invite_Type);
  $Self = ($Mode ? $_SERVER{'PHP_SELF'} : "DanceEdit.php");
  echo "<div class=floatright><h2>";
  $OList = [];
  if (isknown($snum,$year-1)) $OList[] = $year-1;
  if (Get_General($year+1) && (isknown($snum,$year+1) || (($year+1) >= $PLANYEAR))) $OList[] = $year+1;
  if ($year != $PLANYEAR) $OList[] = $PLANYEAR;
    
  sort($OList);
  if (count($OList)) {
    $last = -1;
    foreach ($OList as $dv) {
      if ($dv == $last) continue;
      echo " <a href=$Self?sidenum=$snum&Y=$dv>$dv</a> ";
      $last = $dv;
    }
  }

  echo "</h2></div>";

  if ($Request == 0) {
    echo "<h2>Dancing in $year</h2>";
  
    echo fm_hidden('Year',$year);
    echo fm_hidden('Y',$year);
    if (isset($Sidey['syId']) && ($Sidey['syId'])) echo fm_hidden('syId',$Sidey['syId']);

    echo "<table width=90% border class=SideTable>\n";

      if ($Mode) {
        echo "<tr><td class=NotSide>Invite:<td class=NotSide>" . fm_select($Invite_States,$Sidey,'Invite');
          echo fm_text('Invited',$Sidey,'Invited',1,'class=NotSide');
      }

      echo "<tr><td>";
        if ($Mode == 0 && !$Sidey['Coming']) {
          echo ($Sidey['Invited']?"Status:":"Expect Invitation:");
        } else {
          echo "Status:";
        }

//        echo "<td>" . fm_select($Coming_States ,$Sidey,'Coming',0,'id=Coming_states');
        echo "<td colspan=3>" . fm_radio('',$Coming_States ,$Sidey,'Coming','',0,'id=Coming_states','',$Coming_Colours);
//function fm_radio($Desc,&$defn,&$data,$field,$extra='',$tabs=1,$extra2='',$field2='') {
          if ($Mstate) { 
            echo fm_text("<span $Imp>How Many Performers Wristbands</span>",$Sidey,'Performers',0.5,'','onchange=updateimps()');
            if ($Mode) {
              if (isset($Sidey['WristbandsSent'])) echo fm_checkbox("Sent",$Sidey,"WristbandsSent"); 
            } else {
              if ($Sidey['WristbandsSent']) {
                  $tmp['Ignored2'] = 1;
                echo fm_checkbox('Sent',$tmp,'Ignored2','disabled');
              }
              if (isset($Sidey['WristbandsSent'])) echo fm_hidden('WristbandsSent',$Sidey['WristbandsSent']);
            }
          } else {
            if (isset($Sidey['WristbandsSent'])) echo fm_hidden('WristbandsSent',$Sidey['WristbandsSent']);
          }
        if ($Mstate) {
//          echo fm_text('QE Car Park Tickets',$Sidey,'CarPark');
        }
  
      echo "<tr><td rowspan=5>" . ((isset($Sidey['Invited']) && $Sidey['Invited']) ? "Coming on:" : "Would like to come on:" );
        echo "<td>" . fm_checkbox('Friday',$Sidey,'Fri','onchange=ComeSwitch(event)');
//        echo fm_text1('Daytime Spots',$Sidey,'FriDance',1,'class=ComeFri');
        echo "<td class=ComeFri>" . fm_checkbox('Dance Friday Eve?',$Sidey,'FriEve');
      echo "<tr>";
        echo "<td rowspan=2>" . fm_checkbox('Saturday',$Sidey,'Sat','onchange=ComeSwitch(event)');
        echo fm_text1('Daytime Spots',$Sidey,'SatDance',1,'class=ComeSat');
        echo "<td class=ComeSat>" . fm_checkbox('Plus the Procession',$Sidey,'Procession');
        echo "<td class=ComeSat>" . fm_checkbox('Dance Saturday Eve?',$Sidey,'SatEve');
        echo "<tr>" .fm_text1('Earliest Spot',$Sidey,'SatArrive',1,'class=ComeSat');
        echo fm_text1('Latest Spot',$Sidey,'SatDepart',1,'class=ComeSat');  
      echo "<tr>";
        echo "<td rowspan=2>" . fm_checkbox('Sunday',$Sidey,'Sun','onchange=ComeSwitch(event)');
        echo fm_text1('Daytime Spots',$Sidey,'SunDance',1,'class=ComeSun');
        echo "<tr>" .fm_text1('Earliest Spot',$Sidey,'SunArrive',1,'class=ComeSun');
        echo fm_text1('Latest Spot',$Sidey,'SunDepart',1,'class=ComeSun');  
      if ($Mode) {
        echo "<tr><td class=NotSide>" . fm_checkbox('Tuesday',$Sidey,'Tue') . "<td class=NotSide>" . fm_checkbox('Wednesday',$Sidey,'Wed');
        echo "<td class=NotSide>" . fm_checkbox('Thursday',$Sidey,'Thur') . "<td class=NotSide>" . fm_checkbox('Monday',$Sidey,'Mon');
      } else {
        if ($Sidey['Tue']) echo fm_hidden('Tue',1);
        if ($Sidey['Wed']) echo fm_hidden('Wed',1);
        if ($Sidey['Thur']) echo fm_hidden('Thur',1);
        if ($Sidey['Mon']) echo fm_hidden('Mon',1);
      }
      if ($Mode) {
        echo "<tr>". fm_number1('Fee',$Sidey,'TotalFee','class=NotCSide') . fm_text('Other payments',$Sidey,'OtherPayment',3,'class=NotCSide');
      } else if ($Sidey['TotalFee']) {
        echo "<tr><td>Fee:<td>&pound;" . $Sidey['TotalFee'];
        if ($Sidey['OtherPayment']) echo fm_text('Other payments',$Sidey,'OtherPayment',1,'disabled readonly');
      }

      if (isset($Sidey['TotalFee']) && $Sidey['TotalFee']) { // Contract if there is a fee

// Contract - RO to Act, Confirmed ACT only
/* Mode 0 - IF Booked - View Contract, IF Contract Ready - View Contract, Confirm Contract, IF Other & EVs - View DRAFT contract
                If old contracts, link to old contracts and link to diff old/current, Confirm button -> conf by click
   Mode 1 - If Booked - View Contract, Else view DRAFT Contract
                If Contract Ready - Confirm by Email radio button
                If old contracts, link to old contracts and link to diff old/current
*/
        if ($Mode) {
          include_once('DocLib.php');
          $AllMU = Get_AllUsers4Sect('Dance',$Sidey['BookedBy'],'Other');
          echo "<tr>";  // all NotSide (for now) invite coming, Booked by - list default current user
            echo "<td class=NotSide>Booked By: " . fm_select($AllMU,$Sidey,'BookedBy',1);
//            Contract_State_Check($Sidey,0);
            if (1 || Access('SysAdmin')) {
              echo fm_radio("Booking State",$Book_States,$Sidey,'YearState','class=NotSide',1,'colspan=2 class=NotSide','',$Book_Colours);
            } else {
              echo "<td class=NotSide>Booking State:" . help('YearState') . "<td class=NotSide>" . $Book_States[$Sidey['YearState']];
              echo fm_hidden('YearState',$Sidey['YearState']);
          }
        } else {
           echo fm_hidden('YearState',$Sidey['YearState']);
        }

        $old = 0;
        if (!isset($Sidey['Contracts'])) $Sidey['Contracts']=0;
        switch ($Sidey['YearState']) {
          case $Book_State['Booked']:
            echo "<tr><td><a href=ViewContract.php?sidenum=$snum&Y=$YEAR&ctype=0>View Contract</a>";
            if ($Sidey['Contracts'] >= 1) $old = $Sidey['Contracts'];
            break;
          case $Book_State['Contract Ready']:
            echo "<tr><td><a href=ViewContract.php?sidenum=$snum&Y=$YEAR&ctype=0>View Proposed Contract</a>";
            if ($Sidey['Contracts'] >= 1) $old = $Sidey['Contracts'];
            break;
          case $Book_State['Booking']:
            echo "<tr><td><a href=ViewContract.php?sidenum=$snum&Y=$YEAR&ctype=0>View DRAFT Contract</a>";
            if ($Sidey['Contracts'] >= 1) $old = $Sidey['Contracts'];
            break;
          default:
            break;
          }
        if ($old) {
          echo "<td colspan=2>View earlier contract" . ($old>1?'s':'') . ": ";
          for ($i=1;$i<=$old;$i++) {
            echo "<a href=ViewContract.php?sidenum=$snum&I=$i&ctype=0>#$i</a> ";
          } 
        }
        switch ($Sidey['YearState']) {
          case $Book_State['Booked']:
            echo "<td>Contract Confirmed " .$ContractMethods[$Sidey['ContractConfirm']] . " on " . date('d/m/y',$Sidey['ContractDate']) . "\n";
            break;
          case $Book_State['Contract Ready']:
            $Mess = Contract_Check($snum);
            if (!$Mess) {
              if ($Mode) {
                echo "<td colspan=2><input type=submit id=greensubmit name=Contract value='Confirm Contract by Receipt of Confirmation Email'>";
                    echo fm_hidden('ContractDate',time());
                echo "<td colspan=2><input type=submit id=redsubmit name=Decline value='Decline Contract by Clicking Here'>";
              } else {
                echo "<td colspan=2><input type=submit id=greensubmit name=Contract value='Confirm Contract by Clicking Here'>";
                echo fm_hidden('ContractDate',time());
                echo "<td colspan=2><input type=submit id=redsubmit name=Decline value='Decline Contract by Clicking Here'>";
              }
            } else {
              echo "<td colspan=3>";
              if ($Mess && $Mode) { 
                echo "<span class=red>" . $Mess . "</span>"; 
                } else { 
                echo "The contract is not yet complete, and hence can not be confirmed";
              };
            }
            break;
          case $Book_State['Booking']:
            $Mess = Contract_Check($snum);
            if (!$Mess) {
              echo "<td colspan=3>";
              if ($Mess && $Mode) { echo "<span class=red>" . $Mess . "</span>"; }
              else { echo "The contract is not yet complete, and hence can not be confirmed"; };
            }
            break;
    
          default:
            break;
        }
        if ($Mode) {
          echo "<tr class=NotCSide>" . fm_textarea('Additional Riders',$Sidey,'Rider',2,1,'class=NotCSide') ."\n";
        } else if (isset($Sidey['Rider']) && strlen($Sidey['Rider']) > 5) {
          echo "<tr>" . fm_textarea('Additional Riders',$Sidey,'Rider',2,1,'','disabled') ."\n";
        }
      }

      echo "<tr>";
        if ($Mstate) {
          echo "<td colspan=3 $Imp>Select insurance file to upload:";
          echo "<input type=file $ADDALL name=InsuranceForm id=InsuranceForm onchange=document.getElementById('InsuranceButton').click()>";
          echo "<input hidden type=submit name=Action value=Insurance id=InsuranceButton>";

          if ($Mode){
            echo "<td class=NotCSide colspan=2>" . fm_radio('Insurance',$InsuranceStates,$Sidey,'Insurance','',0);
            if (isset($Sidey['Insurance']) && $Sidey['Insurance']) {
              $files = glob("Insurance/$YEAR/Sides/$snum.*");
              if ($files) {
                $Current = $files[0];
                $Cursfx = pathinfo($Current,PATHINFO_EXTENSION );
                echo " <a href=ShowFile.php?l=Insurance/$YEAR/Sides/$snum.$Cursfx>View</a>";
              }
            }
          } else {
            $tmp['Ignored'] = $Sidey['Insurance'];
            echo "<td>" . fm_checkbox('Insurance Uploaded',$tmp,'Ignored','disabled');
            echo fm_hidden('Insurance',$Sidey['Insurance']);
            }

          if ($Mess && $Action == 'Insurance') echo "<td colspan=2>$Mess\n"; 
        } else {
            echo "<td>Insurance:<td colspan=3>You will be able to upload your Insurance here in $YEAR\n";
        }

/*
    // Overlaps...  With, Type, Days
      if ($Mstate && 0) {
        echo "<tr><td>Overlaps:" . help('Overlaps');
          for ($i=1;$i<=4;$i++) {
            $type = $Sidey["OverlapType$i"];
            echo "<td colspan=6 id=Olap$i>" . fm_hidden("OverlapType$i",$type);
          }
        echo "<td colspan=6>" . fm_select(Sides_All($snum),$Sidey,'Overlap1',1);
      }
*/
      echo "<tr>" . fm_textarea('Notes',$Sidey,'YNotes',8,2);
    }

  if ($Mode) echo "<tr>" . fm_textarea('Private Notes',$Sidey,'PrivNotes',8,2,'class=NotSide','class=NotSide');
    
  echo "</table>\n";
}

//******************************************************* Music YEAR ***********************************************
function Show_Music_Year($snum,$Sidey,$year=0,$CatT='Act',$Mode=0) { // if Cat blank look at data to determine type.  Mode=0 for public, 1 for ctte
  global $YEAR,$CALYEAR,$PLANYEAR,$MASTER,$Invite_States,$Coming_States,$Mess,$Action,$ADDALL,$Invite_Type;
  global $DayList,$Book_States,$Book_State,$Book_Colours,$ContractMethods,$CurYear;
  include_once('ProgLib.php');

  if ($year==0) $year=$YEAR;

  if ($CatT == '') {
    $CatT = ($Side['IsASide'] ? 'Side' : $Side['IsAnAct'] ? 'Act' : 'Other');
  } else {
    if ($CatT == 'Side') $Side['IsASide']=1;
    if ($CatT == 'Act') $Side['IsAnAct']=1;
    if ($CatT == 'Other') $Side['IsOther']=1;
  }
  $Side=Get_Side($snum);

  Set_Side_Year_Help();
  if ($CatT != 'Side') Add_Act_Year_Help();

  $Mstate = ($PLANYEAR >= $CALYEAR && $PLANYEAR == $YEAR);

  $Adv = '';
  $Imp = '';
  if ($year < $PLANYEAR) { // Then it is historical - no changes allowed
      fm_addall('disabled readonly');
  } else if ($Mode == 0) {
    $Adv = 'class=Adv';
  }

  if ($Mode == 0 && (!isset($Sidey['YearState']) || $Sidey['YearState'] == 0)) {
    if ($YEAR >= $PLANYEAR) echo "<h2><a href=MusicRequest.php?sidenum=$snum&Y=$YEAR>Request Invite for $PLANYEAR</a></h2>";
  } else {
    
//var_dump($Sidey);var_dump($Invite_Type);
    $Self = $_SERVER{'PHP_SELF'};
    if ($year > $CurYear) {
      if ($Mode && isknown($snum,$CurYear)) 
        echo "<div class=floatright><h2><a href=$Self?sidenum=$snum&Y=$CurYear>$CurYear</a></h2></div>";  
      echo "<h2>Details in $year</h2>";
    } else if ($year == $PLANYEAR) {
      if ($Mode && isknown($snum,$CurYear-1)) 
        echo "<div class=floatright><h2><a href=$Self?sidenum=$snum&Y=" . ($CurYear-1) . ">" . ($CurYear-1) . "</a></h2></div>";  
      echo "<h2>Details in $year</h2>";
    } else {
      if ($Mode) echo "<div class=floatright><h2><a href=$Self?sidenum=$snum>$PLANYEAR</a></h2></div>"; 
      echo "<h2>Details in $year</h2>";
    }
  
    echo fm_hidden('Year',$year);
    echo fm_hidden('Y',$year);
    if (isset($Sidey['ActId']) && $Sidey['ActId']) echo fm_hidden('ActId',$Sidey['ActId']);

    echo "<table width=90% border class=SideTable>\n";
/* General Controls */

    if ($Mode) {
      include_once('DocLib.php');
      $AllMU = Get_AllUsers4Sect('Music',$Sidey['BookedBy'],'Other');
      echo "<tr>";  // all NotSide (for now) invite coming, Booked by - list default current user
        echo "<td class=NotSide>Booked By: " . fm_select($AllMU,$Sidey,'BookedBy',1);
        Contract_State_Check($Sidey,0);
        if (1 || Access('SysAdmin')) {
          echo fm_radio("Booking State",$Book_States,$Sidey,'YearState','class=NotSide',1,'colspan=2 class=NotSide','',$Book_Colours);
        } else {
          echo "<td class=NotSide>Booking State:" . help('YearState') . "<td class=NotSide>" . $Book_States[$Sidey['YearState']];
          echo fm_hidden('YearState',$Sidey['YearState']);
        }
      if (Access('Committee','Music') || Access('Committee','Finance')) {
        echo "<tr>". fm_number1('Fee',$Sidey,'TotalFee','class=NotCSide');
        echo fm_text('Other payments',$Sidey,'OtherPayment',3,(isset($Sidey['OtherPayment']) && strlen($Sidey['OtherPayment'])>1?'class=NotCSide':'class=NotCSide'));
        echo "<td class=NotSide>" . fm_checkbox("Allow Camping",$Sidey,'EnableCamp');
        echo "<td" . ((isset($Sidey['EnableCamp']) && $Sidey['EnableCamp'])?"":" class=NotSide") . ">Camping " . fm_checkbox('Fri',$Sidey,'CampFri') . 
                fm_checkbox('Sat',$Sidey,'CampSat') . fm_checkbox('Sun',$Sidey,'CampSun');
        echo "<tr class=NotCSide>" . fm_textarea('Additional Riders',$Sidey,'Rider',2,1,'class=NotCSide') ."\n";
      }
    } else {
      echo "<tr><td>Fee:<td>&pound;" . $Sidey['TotalFee'];
      echo fm_hidden('YearState',$Sidey['YearState']);
      if ($Sidey['OtherPayment']) echo fm_text('Other payments',$Sidey,'OtherPayment',1,'','readonly disabled');
      if ($Sidey['EnableCamp']) { 
        echo fm_hidden('EnableCamp',$Sidey['EnableCamp']);
        echo "<td>Camping " . fm_checkbox('Fri',$Sidey,'CampFri') . fm_checkbox('Sat',$Sidey,'CampSat') . fm_checkbox('Sun',$Sidey,'CampSun');
      }
      echo fm_hidden('YearState',$Sidey['YearState']);
      if (isset($Sidey['Rider']) && strlen($Sidey['Rider']) > 5)  echo "<tr>" . fm_textarea('Additional Riders',$Sidey,'Rider',2,1,'','disabled') ."\n";
    }
// Events - RO to Act, RW to ctte
  $Evs = Get_Events4Act($snum,$year);
  $HasPark = '';
  $ParkedLocs = array();
  if ($Evs) {
    $Venues = Get_Real_Venues(1);
    $ETs = Get_Event_Types();
    echo "<tr><td colspan=5>Click on the Event Names below for more detailed information." . ($Mode==2?" Direct editing of some fields will be possible soon":"");
    echo "<tr><td>Event Name<td>Type<td>Date<td>On Stage at<td>Start<td>Duration (mins)<td colspan=3>Where\n";
    foreach($Evs as $e) {
      $Detail = ($Mode?"EventAdd.php":"EventShow.php");
      $vv = $e['Venue'];
      if ($e['SubEvent'] < 0) { $End = $e['SlotEnd']; } else { $End = $e['End']; };
      if (($e['Start'] != 0) && ($End != 0) && ($e['Duration'] == 0)) $e['Duration'] = timeadd2real($End, - $e['Start']);
      echo "<tr><td><a href=$Detail?e=" . $e['EventId'] . ">" . $e['SName'] . "</a>";
      echo "<td>" . $ETs[$e['Type']];
      echo "<td>" . $DayList[$e['Day']] . " " . ($MASTER['DateFri']+$e['Day']) ."th June $YEAR";
      echo "<td>" . ($e['Start']? ( timecolon(timeadd2($e['Start'],- $e['Setup']) )) : "TBD" ) ;
      echo "<td>" . ($e['Start']?timecolon($e['Start']):"TBD");
      echo "<td>" . ($e['Duration']?$e['Duration']:"TBD"); 
      echo "<td colspan=3>" . ($vv?("<a href=VenueShow.php?v=$vv>" . SName($Venues[$vv]) . "</a>"):"TBD") . "\n";
      if ($vv && $Venues[$vv]['Parking']) {
        if (!isset($ParkedLocs[$vv])) {
          if ($HasPark) $HasPark .= ", ";
          $ParkedLocs[$vv]++;
          $HasPark .= SName($Venues[$vv]);
        }
      }
    } 
    echo "<tr><td colspan=7>&nbsp;";

// Contract - RO to Act, Confirmed ACT only
/* Mode 0 - IF Booked - View Contract, IF Contract Ready - View Contract, Confirm Contract, IF Other & EVs - View DRAFT contract
                If old contracts, link to old contracts and link to diff old/current, Confirm button -> conf by click
   Mode 1 - If Booked - View Contract, Else view DRAFT Contract
                If Contract Ready - Confirm by Email radio button
                If old contracts, link to old contracts and link to diff old/current
*/
    $old = 0;
    if (!isset($Sidey['Contracts'])) $Sidey['Contracts']=0;
    switch ($Sidey['YearState']) {
      case $Book_State['Booked']:
        echo "<tr><td><a href=ViewContract.php?sidenum=$snum&Y=$YEAR>View Contract</a>";
        if ($Sidey['Contracts'] >= 1) $old = $Sidey['Contracts'];
        break;
      case $Book_State['Contract Ready']:
        echo "<tr><td><a href=ViewContract.php?sidenum=$snum&Y=$YEAR>View Proposed Contract</a>";
        if ($Sidey['Contracts'] >= 1) $old = $Sidey['Contracts'];
        break;
      case $Book_State['Booking']:
        echo "<tr><td><a href=ViewContract.php?sidenum=$snum&Y=$YEAR>View DRAFT Contract</a>";
        if ($Sidey['Contracts'] >= 1) $old = $Sidey['Contracts'];
        break;
      default:
        break;
      }
    if ($old) {
      echo "<td colspan=2>View earlier contract" . ($old>1?'s':'') . ": ";
      for ($i=1;$i<=$old;$i++) {
        echo "<a href=ViewContract.php?sidenum=$snum&I=$i>#$i</a> ";
      } 
    }
    switch ($Sidey['YearState']) {
      case $Book_State['Booked']:
        echo "<td>Contract Confirmed " .$ContractMethods[$Sidey['ContractConfirm']] . " on " . date('d/m/y',$Sidey['ContractDate']) . "\n";
        break;
      case $Book_State['Contract Ready']:
        $Mess = Contract_Check($snum);
        if (!$Mess) {
          if ($Mode) {
            echo "<td colspan=2><input type=submit id=greensubmit name=Contract value='Confirm Contract by Receipt of Confirmation Email'>";
                echo fm_hidden('ContractDate',time());
            echo "<td colspan=2><input type=submit id=redsubmit name=Decline value='Decline Contract by Clicking Here'>";
          } else {
            echo "<td colspan=2><input type=submit id=greensubmit name=Contract value='Confirm Contract by Clicking Here'>";
            echo fm_hidden('ContractDate',time());
            echo "<td colspan=2><input type=submit id=redsubmit name=Decline value='Decline Contract by Clicking Here'>";
          }
        } else {
          echo "<td colspan=3>";
          if ($Mess && $Mode) { 
            echo "<span class=red>" . $Mess . "</span>"; 
            } else { 
            echo "The contract is not yet complete, and hence can not be confirmed";
          };
        }
        break;
      case $Book_State['Booking']:
        $Mess = Contract_Check($snum);
        if (!$Mess) {
          echo "<td colspan=3>";
          if ($Mess && $Mode) { echo "<span class=red>" . $Mess . "</span>"; }
          else { echo "The contract is not yet complete, and hence can not be confirmed"; };
        }
        break;

      default:
        break;
    }
    echo "<td>" . fm_checkbox('Radio Wimborne',$Sidey,'RadioWimborne');
  }

/*
  echo "<tr><td>Parking:";
  if ($HasPark) echo fm_text("For $HasPark",$Sidey,'Parking','','colspan=2');
  echo fm_text('QE Car Park Tickets',$Sidey,'CarPark');
*/

// Notes - As Sides
  echo "<tr>" . fm_textarea('Notes',$Sidey,'YNotes',8,2);
  if ($Mode) echo "<tr>" . fm_textarea('Private Notes',$Sidey,'PrivNotes',8,2,'class=NotSide','class=NotSide');

  }
    
  echo "</table>\n";
}

/* View Contract, Agree to contract, This has summary.  Booking summary - ctte can add/change event - it has a year, may have venue & times.
   Automatic sound check events for some venues
   Music Programming - Venues, acts not placed, errors etc.
*/


?>
