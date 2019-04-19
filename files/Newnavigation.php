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
  if (isset($_COOKIE{'WMFF2'}) || isset($USER{'AccessLevel'})) {
    $Bars = 2;
    $UserName = $USER['Login'];
  }
//   $Bars = 1; 

// text=>link or text=>[submenu] (recuresive)
// 1st char 0f text * - not selectable, ! Icon, ? Only Dance, # Not Dance, = Get Tickets
// 1st char of link ! - external
  $Menus = [
    'Public'=> [
      '<Home'=>'',
      'Line-up'=>[
        'Dance'=>'LineUp.php?T=Dance',
        'Music'=>'LineUp.php?T=Music', 
        'Comedy'=>'LineUp.php?T=Comedy',
        'Family'=>'LineUp.php?T=Family',
        ],
      "What's On"=>[
        'By Venue'=>'WhatsOnWhere.php',
        'By Time'=>'WhatsOnWhen.php',
        '*Now'=>'WhatsOnNow.php',
        'Dancing'=>'int/ShowDanceProg.php?Cond=1&Pub=1&Y=$YEAR',
        'Music'=>'Sherlock.php?t=Music',
//        'Special Events'=>'Sherlock.php?t=Special',
        'Family'=>'Sherlock.php?t=Family',
        'Ceilidhs'=>'Sherlock.php?t=Ceilidh',
        'Workshops and Talks'=>'Sherlock.php?t=Workshop',
        'Comedy'=>'Sherlock.php?t=Comedy',
        'Sessions'=>'Sherlock.php?t=Session',
        'Religion'=>'Sherlock.php?t=Religion',
        'Traders'=>'int/TradeShow.php',
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
        'Trade Stand Applications'=>'info/trade', // TODO make like the rest
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
      '!images/icons/Facebook.png'=>'!http://facebook.com/WimborneFolk',
      '!images/icons/Twitter.png'=>'!http://twitter.com/WimborneFolk',
      '!images/icons/Instagram.png'=>'!http://instagram.com/WimborneFolk',
      '=Get Tickets'=>'Tickets.php',
      ],
    'Private'=> [  
      'Staff Tools'=>'int/Staff.php',
      '-Documents'=>'int/Dir.php',
      '-Time Line'=>'int/TimeLine.php?Y=$YEAR',
      "Logout $UserName"=>'int/Login.php?ACTION=LOGOUT',
      ],
    'Perf'=>[
      'Edit Data'=>"int/AddPerf.php?sidenum=$USERID",
      '-Public view'=>"int/ShowDance.php?sidenum=$USERID",
      '?Dance FAQ'=>'int/DanceFAQ.php',
      '#Performer T&amp;Cs'=>'MusicFAQ.php',    
      ],
    'Trade'=>[
      'Edit Trader Info'=>'int/TraderPage.php?id=$USERID',
      '-Public view of Trader'=>'int/ShowTrade.php?id=$USERID',
      'Trade FAQ'=>'int/TradeFAQ.php',
      ], 

    'Testing'=>[
      'Staff Tools'=>'int/Staff.php',
      ],         
  ];
 
function Show_Bar(&$Bar,$level=0) { 
  global $USERID,$host;
  $host= "https://" . $_SERVER['HTTP_HOST'];
//  echo "<ul class=MenuLevel$level>";
  foreach ($Bar as $text=>$link) {
    $Minor = 0;
    $xtra = '';
    if (!$text) continue;
    switch (substr($text,0,1)) {
      case '*' : 
        echo "<a class='NotYet MenuMinor2'>" . substr($text,1);
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
        $Minor = 1;
        $xtra = "id=MenuGetTicket";
        $text = substr($text,1);
        break;
      case '<' :
        $Minor = 2;
        $text = substr($text,1);
        break;
      case '?' :
        $Side = Get_Side($USERID);
        if (!$Side['IsASide']) continue;
        $text = substr($text,1);
        break;
      case '#' :
        $Side = Get_Side($USERID);
        $NotD = 0;
        foreach ($PerfTypes as $p=>$d) if (($d[0] != 'IsASide') && $Side[$d[0]]) $NotD = 1;
        if (!$NotD) continue;
        $text = substr($text,1);
        break;
      default:
    }
    if (is_array($link)) {
      echo "<div class='dropdown MenuMinor$Minor' $xtra onmouseover=NoHoverSticky(event)>";
      echo "<a onclick=NavStick(event)>$text</a>";
      echo "<div class=dropdown-content>";
      Show_Bar($link,$level+1);
      echo "</div></div>";
    } elseif (substr($link,0,1) == "!") {
      echo "<a class='MenuMinor$Minor headericon' $xtra href='" . substr($link,1) . "' target=_blank>$text</a>";
    } else {
      echo "<a href='$host/$link' class='MenuMinor$Minor' $xtra onmouseover=NoHoverSticky(event)>$text</a>";
    }
  }
}



/* START HERE */

  echo "<header class=main-header>"; 
  echo "<a href=/>";
    echo "<img src=" . $MASTER_DATA['WebsiteBanner2'] . "?V=$V class='header-logo head-white-logo'>";
    echo "<img src=" . $MASTER_DATA['WebSiteBanner'] . "?V=$V class='header-logo head-coloured-logo'>";
  echo "</a>";
  echo "<div class=MenuIcon><img src=/images/icons/MenuIcon.svg onmouseover=ShowHoverMenu(0) onclick=ShowHoverMenu(1)></div>";
  echo "<div id=MenuBars>";
  echo "<div id=PublicBar$Bars class=navigation align=right>";
  echo Show_Bar($Menus['Public']);
  echo "</div>";
  
  if ($Bars == 2) {
    echo "<div id=PrivateBar class=navigation align=right>";
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
        Show_Bar($Menus['Testing']);
      }
    } else if (isset($_COOKIE{'WMFF2'})) {
      Show_Bar($Menus['Private']);
    } 
    echo "</div>";
  }
  echo "</div></header>";
  
?>

