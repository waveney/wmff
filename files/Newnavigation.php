<?php
  global $Access_Type,$USER,$USERID,$YEAR,$FESTSYS,$YEARDATA,$NEXTYEARDATA,$Months;
  Set_User();
  
  // Header bar  
  // Icon
  // Public bar
  // Private bar (may be zero height)

  $V=$FESTSYS['V'];  
  $Bars = 1;
  $UserName = '';
  if (isset($_COOKIE{'WMFF2'}) || isset($USER{'AccessLevel'})) {
    $Bars = 2;
    $UserName = (isset($USER['Login'])? $USER['Login'] : "");
  }
//   $Bars = 1; 

// text=>link or text=>[submenu] (recuresive)
// 1st char 0f text * - not selectable, ! Icon, ? Only Dance, # Not Dance, = Get Tickets
// 1st char of link ! - external
  $Menus = [
    'Public'=> [
      '<Home'=>'',
      'Line-Up'=>[
        'Dance'=>'LineUp?T=Dance',
        'Music'=>'LineUp?T=Music', 
        'Comedy'=>'LineUp?T=Comedy',
        'Family'=>'LineUp?T=Family',
        'Traders'=>'int/TradeShow',
        'Art'=>'InfoArt',
        ],
      "Timetable"=>[
        'By Venue'=>'WhatsOnWhere',
        'By Time'=>'WhatsOnWhen',
        'Now'=>'WhatsOnNow',
        'Dancing'=>"int/ShowDanceProg?Cond=1&Pub=1&Y=$YEAR",
        'Concerts'=>'Sherlock?t=Concert',
        'Music'=>'Sherlock?t=Music',
        'Special Events'=>'Sherlock?t=Special',
        'Family'=>'Sherlock?t=Family',
        'Ceilidhs'=>'Sherlock?t=Ceilidh',
        'Workshops and Talks'=>'Sherlock?t=Workshop',
        'Art'=>'Sherlock?t=Art',
        'Comedy'=>'Sherlock?t=Comedy',
        'Sessions'=>'Sherlock?t=Session',
        'Religion'=>'Sherlock?t=Religion',
        ],
      'Information'=>[
        'Festival Map'=>'Map',
        'Camping'=>'InfoCamping',
        'Parking'=>'InfoParking',
        'Travel'=>'InfoGettingHere',
        'Mailing List'=>'InfoMailingList',
        'Contact Us'=>'contact',
        'Road Closures'=>'RoadClosure',
        'Data Privacy'=>'InfoData',
        'Festival Software'=>'InfoSoftware',
        ],
      '-Get Involved'=>[
        'Volunteer'=>'InfoStewards',
        'Sponsorship'=>'InfoSponsors',
        'Trade Stand Applications'=>'InfoTrade', 
        'Live and Loud'=>'LiveNLoud',
        'Buskers Bash'=>'BuskersBash',
         ],
      '-Gallery'=>[
        '2019 Photos'=>'gallery/gallery2019',
        '2018 Photos'=>'gallery/gallery2018',
        '2017 Photos'=>'int/ShowGallery?g=2',
        '2016 Photos'=>'gallery/2016',
        '2015 Photos'=>'gallery/2015',
        '2014 Photos'=>'gallery/2014',
        '2013 Photos'=>'gallery/2013',
        '2017 Laugh out Loud Photos'=>'int/ShowGallery?g=3',
       ],
      '!/images/icons/Facebook.png'=>'!http://facebook.com/WimborneFolk',
      '!/images/icons/Twitter.png'=>'!http://twitter.com/WimborneFolk',
      '!/images/icons/Instagram.png'=>'!http://instagram.com/WimborneFolk',
      '=Buy Tickets'=>'Tickets',
      '%Donate'=>'Donate',
      ],
    'Private'=> [  
      'Staff Tools'=>'int/Staff',
      '-Documents'=>'int/Dir',
      '-Time Line'=>"int/TimeLine?Y=$YEAR",
      "Logout $UserName"=>'int/Login?ACTION=LOGOUT',
      ],
    'Perf'=>[
      'Edit Your Data'=>"int/AddPerf?sidenum=$USERID",
      '-Public view'=>"int/ShowDance?sidenum=$USERID",
      '?Dance Loc Map'=>'/Map?F=3',
      '?Dance FAQ'=>'int/DanceFAQ',
      '#Performer T&amp;Cs'=>'int/MusicFAQ',    
      ],
    'Trade'=>[
      'Edit Trader Info'=>"int/TraderPage?id=$USERID",
      'Trade FAQ'=>'int/TradeFAQ',
      ], 

    'Testing'=>[
      'Staff Tools'=>'int/Staff',
      ],         
  ];

global $MainBar,$HoverBar,$HoverBar2;
$MainBar = $HoverBar = $HoverBar2 = '';


function Show_Bar(&$Bar,$level=0,$Pval=1) { 
  global $USERID,$host,$PerfTypes,$MainBar,$HoverBar,$HoverBar2,$YEARDATA;
  $host= "https://" . $_SERVER['HTTP_HOST'];
//  echo "<ul class=MenuLevel$level>";
  $P=$Pval*100;
  $Pi = 0;
  foreach ($Bar as $text=>$link) {
    $Minor = 0;
    $xtra = '';
    $Pi++; $P++;
    if (!$text) continue;
    switch (substr($text,0,1)) {
      case '*' : 
        $str = "<a class='NotYet MenuMinor2'>" . substr($text,1);
        $MainBar .= $str;
        $HoverBar .= $str;
        continue 2;
      case '!' :
        $Minor = 1;
        $text = "<img src='" . substr($text,1) . "' class=HeaderIcon>";
        break;
      case '-' :
        $Minor = 1;
        $text = substr($text,1);
        break;
      case '=' :
        if ($YEARDATA['TicketControl'] > 2 || $YEARDATA['TicketControl'] == 0) continue 2;
        $xtra = "id=MenuGetTicket";
        $text = substr($text,1);
        break;
      case '<' :
        $Minor = 2;
        $text = substr($text,1);
        break;
      case '?' :
        include_once("int/DanceLib.php");
        $Side = Get_Side($USERID);
        if (!$Side['IsASide']) continue 2;
        $text = substr($text,1);
        break;
      case '#' :
        include_once("int/DanceLib.php");
        $Side = Get_Side($USERID);
        $NotD = 0;
        foreach ($PerfTypes as $p=>$d) if (($d[0] != 'IsASide') && $Side[$d[0]]) $NotD = 1;
        if (!$NotD) continue 2;
        $text = substr($text,1);
        break;
      case '%' :
        if (!Feature('Donate')) continue 2;
        $xtra = "id=MenuDonate";
        $text = substr($text,1);
        break;
        
      default:
    }
    if (is_array($link)) {
      $MainBar .= "<div class='dropdown MenuMinor$Minor' id=MenuParent$P $xtra onmouseover=NoHoverSticky(event)>";
      $MainBar .= "<a onclick=NavStick(event) onmouseover=NavSetPosn(event,$P)>$text</a>";
      $MainBar .= "<div class=dropdown-content id=MenuChild$P>";
      if ($level == 1) $xtra .= " style='animation-duration: " . (150 * $Pi) . "ms; '";      
      $HoverBar .= "<div class=hoverdown id=HoverParent$P onclick=HoverDownShow($P) $xtra >$text<img class=hoverdownarrow src=/images/icons/Down-arrow.png id=DownArrow$P></div>";
      $HoverBar .= "<div class=hoverdown-content id=HoverChild$P>";
      Show_Bar($link,$level+1,$P);
      $MainBar .= "</div></div>";
      $HoverBar .= "</div>";
    } elseif (substr($link,0,1) == "!") {
      $MainBar .= "<a class='MenuMinor$Minor headericon' $xtra href='" . substr($link,1) . "' target=_blank>$text</a>";
      $HoverBar2 .= "<div class=hoverdown><a class='headericon' $xtra href='" . substr($link,1) . "' target=_blank>$text</a></div>";
    } else {
      if ($level == 1) $xtra .= " style='animation-duration: " . (150 * $Pi) . "ms; '";
      $MainBar .=  "<a href='$host/$link' class='MenuMinor$Minor' $xtra onmouseover=NoHoverSticky(event)>$text</a>";
      $HoverBar .=  "<div class=hoverdown><a href='$host/$link' $xtra >$text</a></div>";
    }
  }
}



/* START HERE */

  // This generates the info into MainBar and HoverBar

  $MainBar .= "<nav class='PublicBar PublicBar$Bars navigation' align=right>";
  Show_Bar($Menus['Public']);
  $MainBar .= "</nav>";
  
//  echo $MainBar;
  
  if ($Bars == 2) {
    $MainBar .=  "<div class='navigation PrivateBar MenuMinor0' align=right>";
    if ( isset($USER{'AccessLevel'}) && $USER{'AccessLevel'} == $Access_Type['Participant'] ) {
      switch ($USER{'Subtype'}) {
        case 'Perf': 
          Show_Bar($Menus['Perf']);
          break;
        case 'Trader':    
          Show_Bar($Menus['Trade']);
          break;
        default:
          break;
      }
      if (isset($_COOKIE{'WMFF2'})) {
        $MainBar .=  "<div class=MenuTesting>";
        Show_Bar($Menus['Testing']);
        $MainBar .=  "</div>";
      }
    } else if (isset($_COOKIE{'WMFF2'}) && $UserName ) {
      Show_Bar($Menus['Private']);
    }
    $MainBar .= "</div>";
  }
  
//   var_dump($YEARDATA); 
  echo "<div class=main-header>"; 
  $DFrom = ($YEARDATA['DateFri']+$YEARDATA['FirstDay']);
  $DTo = ($YEARDATA['DateFri']+$YEARDATA['LastDay']);
  $DMonth = $Months[$YEARDATA['MonthFri']];
 

  if ($YEARDATA['Years2Show'] > 0) {
    $NFrom = ($NEXTYEARDATA['DateFri']+$NEXTYEARDATA['FirstDay']);
    $NTo = ($NEXTYEARDATA['DateFri']+$NEXTYEARDATA['LastDay']);
    $NMonth = $Months[$NEXTYEARDATA['MonthFri']];
    $NYear = $YEARDATA['NextFest'];
  }
  echo "<a href=/>";
    echo "<img src=" . $FESTSYS['WebsiteBanner2'] . "?V=$V class='header-logo head-white-logo'>";
    echo "<img src=" . $FESTSYS['WebSiteBanner'] . "?V=$V class='header-logo head-coloured-logo'>";
    if ($YEARDATA['Years2Show'] < 2) { // TODO Handle Both
      $Yr = substr($YEAR,0,4);
      echo "<div class=SmallDates>$DFrom - $DTo $DMonth $Yr</div>";
      echo "<div class=FestDates>$DFrom - $DTo<br>$DMonth<br>$Yr</div>";
    } else {
      $NYear = substr($NYear,0,4);
      echo "<div class=SmallDates>$NFrom - $NTo $NMonth $NYear</div>";
      echo "<div class=FestDates>$NFrom - $NTo<br>$NMonth<br>$NYear</div>";    
    }
  echo "</a>";
  echo "<div class=MenuIcon><div id=MenuIconIcon class=MenuMenuIcon onclick=ShowHoverMenu()>Menu<img src=/images/icons/MenuIcon.png></div>";
  echo "<div id=MenuIconClose onclick=CloseHoverMenu() class=MenuMenuClose>Close<img src=/images/icons/MenuClose.png></div>";
  echo "<div id=HoverContainer><div id=HoverMenu>$HoverBar$HoverBar2</div></div></div>";
  echo "<div id=MenuBars>";
  echo $MainBar;

  echo "</div></div>";
  
?>

