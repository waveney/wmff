<?php
//  include_once("int/fest.php");
  global $Access_Type,$USER,$USERID,$YEAR,$MASTER_DATA;
  Set_User();
  
  // Header bar  
  // Icon
  // Public bar
  // Private bar (may be zero height)

  $V=$MASTER_DATA['V'];  
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
        'Dance'=>'LineUp.php?T=Dance',
        'Music'=>'LineUp.php?T=Music', 
        'Comedy'=>'LineUp.php?T=Comedy',
        'Family'=>'LineUp.php?T=Family',
        'Traders'=>'int/TradeShow.php',
        ],
      "Timetable"=>[
        'By Venue'=>'WhatsOnWhere.php',
//        'By Time'=>'WhatsOnWhen.php',
        '*Now'=>'WhatsOnNow.php',
//        'Dancing'=>"int/ShowDanceProg.php?Cond=1&Pub=1&Y=$YEAR",
        'Concerts'=>'Sherlock.php?t=Concert',
//        'Music'=>'Sherlock.php?t=Music',
//        'Special Events'=>'Sherlock.php?t=Special',
        'Family'=>'Sherlock.php?t=Family',
        'Ceilidhs'=>'Sherlock.php?t=Ceilidh',
        'Workshops and Talks'=>'Sherlock.php?t=Workshop',
        'Comedy'=>'Sherlock.php?t=Comedy',
        'Sessions'=>'Sherlock.php?t=Session',
        'Religion'=>'Sherlock.php?t=Religion',
        ],
      'Information'=>[
        'Festival Map'=>'Map.php',
        'Camping'=>'InfoCamping.php',
        'Parking'=>'InfoParking.php',
        'Travel'=>'InfoGettingHere.php',
        'Mailing List'=>'InfoMailingList.php',
        'Contact Us'=>'contact.php',
        'Road Closures'=>'RoadClosure.php',
        'Data Privacy'=>'InfoData.php',
        'Festival Software'=>'InfoSoftware.php',
        ],
      '-Get Involved'=>[
        'Volunteer'=>'InfoStewards.php',
        'Sponsorship'=>'InfoSponsors.php',
        'Trade Stand Applications'=>'InfoTrade.php', 
         ],
      '-Gallery'=>[
        '2018 Photos'=>'gallery/gallery2018.php',
        '2017 Photos'=>'int/ShowGallery.php?g=2',
        '2016 Photos'=>'gallery/2016',
        '2015 Photos'=>'gallery/2015',
        '2014 Photos'=>'gallery/2014',
        '2013 Photos'=>'gallery/2013',
        '2017 Laugh out Loud Photos'=>'int/ShowGallery.php?g=3',
       ],
      '!/images/icons/Facebook.png'=>'!http://facebook.com/WimborneFolk',
      '!/images/icons/Twitter.png'=>'!http://twitter.com/WimborneFolk',
      '!/images/icons/Instagram.png'=>'!http://instagram.com/WimborneFolk',
      '=Buy Tickets'=>'Tickets.php',
      '%Donate'=>'Donate.php',
      ],
    'Private'=> [  
      'Staff Tools'=>'int/Staff.php',
      '-Documents'=>'int/Dir.php',
      '-Time Line'=>"int/TimeLine.php?Y=$YEAR",
      "Logout $UserName"=>'int/Login.php?ACTION=LOGOUT',
      ],
    'Perf'=>[
      'Edit Your Data'=>"int/AddPerf.php?sidenum=$USERID",
      '-Public view'=>"int/ShowDance.php?sidenum=$USERID",
      '?Dance Loc Map'=>'/Map.php?F=3',
      '?Dance FAQ'=>'int/DanceFAQ.php',
      '#Performer T&amp;Cs'=>'int/MusicFAQ.php',    
      ],
    'Trade'=>[
      'Edit Trader Info'=>"int/TraderPage.php?id=$USERID",
      '-Public view of Trader'=>"int/ShowTrade.php?id=$USERID",
      'Trade FAQ'=>'int/TradeFAQ.php',
      ], 

    'Testing'=>[
      'Staff Tools'=>'int/Staff.php',
      ],         
  ];

global $MainBar,$HoverBar,$HoverBar2;
$MainBar = $HoverBar = $HoverBar2 = '';


function Show_Bar(&$Bar,$level=0,$Pval=1) { 
  global $USERID,$host,$PerfTypes,$MainBar,$HoverBar,$HoverBar2;
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
//        $Minor = 1;
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
        if (!$Side['IsASide']) continue;
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
  
  echo "<div class=main-header>"; 
  echo "<a href=/>";
    echo "<img src=" . $MASTER_DATA['WebsiteBanner2'] . "?V=$V class='header-logo head-white-logo'>";
    echo "<img src=" . $MASTER_DATA['WebSiteBanner'] . "?V=$V class='header-logo head-coloured-logo'>";
    echo "<div class=SmallDates>0 - 9 June 2019</div>";
    echo "<div class=FestDates>6 - 9<br>June<br>2019</div>";
  echo "</a>";
  echo "<div class=MenuIcon><div id=MenuIconIcon class=MenuMenuIcon onclick=ShowHoverMenu()>Menu<img src=/images/icons/MenuIcon.png></div>";
  echo "<div id=MenuIconClose onclick=CloseHoverMenu() class=MenuMenuClose>Close<img src=/images/icons/MenuClose.png></div>";
  echo "<div id=HoverContainer><div id=HoverMenu>$HoverBar$HoverBar2</div></div></div>";
  echo "<div id=MenuBars>";
  echo $MainBar;

  echo "</div></div>";
  
?>

