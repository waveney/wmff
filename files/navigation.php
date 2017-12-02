<?php
  include_once("int/fest.php");
  global $Access_Type,$USER;
  Set_User();
  if (isset($_COOKIE{'WMFF'}) || isset($_COOKIE{'WMFF2'})) {
    echo "<div class=Staff><div class=navigation>";
    echo "<a href=/int/Staff.php onmouseover=NoHoverSticky()>Staff Tools</a>";
    if (!isset($USER{'Subtype'})) {
      echo "<a href=/int/Dir.php onmouseover=NoHoverSticky()>Docs</a>";
      echo "<a href=/int/TimeLine.php onmouseover=NoHoverSticky()>Time Line</a>";
      if (Access('SysAdmin')) {
        echo "<a href=/admin onmouseover=NoHoverSticky()>Old Admin</a>";
      }
      echo "<a href='/int/Login.php?ACTION=LOGOUT' onmouseover=NoHoverSticky()>Logout " . $USER['Login'] . "</a>\n";
    }
    echo "</div></div>";
  }
?>

<div class=navigation>
<a href=/ onmouseover=NoHoverSticky()>Home</a>
<a href=/news.php onmouseover=NoHoverSticky()>News</a>
<div class=dropdown onmouseover=NoHoverSticky(event)>
  <a href=/line-up>Line-up</a>
  <div class=dropdown-content>
     <a href=/line-up/dance>Dance</a>
     <a href=/line-up/music>Music</a>
     <a href=/line-up/Comedy.php class=NotYet>Comedy</a>
     <a href=/line-up/Traders.php class=NotYet>Traders</a>
     <a href=/line-up/Other.php class=NotYet>Family/Other</a>
  </div>
</div>
<div class=dropdown onmouseover=NoHoverSticky(event)>
  <a href=/Whatson class=NotYet>Whats On</a>
  <div class=dropdown-content>
     <a href=/Whatson/Where.php class=NotYet>By Venue</a>
     <a href=/Whatson/When.php class=NotYet>When</a>
     <a href=/Whatson/DanceProg.php class=NotYet>Dancing</a>
     <a href=/Whatson/MuicProg class=NotYet>Music</a>
     <a href=/Whatson/Special.php class=NotYet>Special Events</a>
     <a href=/Whatson/Family.php class=NotYet>Family</a>
     <a href=/Whatson/Ceildihs.php class=NotYet>Ceildihs</a>
     <a href=/Whatson/Workshops.php class=NotYet>Workshops</a>
     <a href=/Whatson/Comedy.php class=NotYet>Comedy</a>
     <a href=/Whatson/Sessions.php class=NotYet>Sessions</a>
     <a href=/Whatson/Craft.php class=NotYet>Craft</a>
  </div>
</div>
<a href=/Tickets.php onmouseover=NoHoverSticky()>Tickets</a>
<div class=dropdown onmouseover=NoHoverSticky(event)>
  <a href=/info>Info</a>
  <div class=dropdown-content>
     <a href=/Map.php class=NotYet>Festival Map</a>
     <a href=/info/camping>Camping & Parking</a>
     <a href=/info/getting-here>Getting Here</a>
     <a href=/info/sponsorship>Sponsorship</a>
     <a href=/info/stewards>Stewards</a>
     <a href=/info/trade>Trade Stands</a>
     <a href=/RoadClosure.php class=NotYet>Road Closures</a>
     <a href=/info/thanks>With Thanks</a>
     <a href=/RadioWimborne.php>Radio Wimborne</a>
  </div>
</div>
<div class=dropdown onmouseover=NoHoverSticky(event)>
  <a href=/About.php class=NotYet>About</a>
  <div class=dropdown-content>
     <a href=/About.php class=NotYet>About the Festival</a>
     <a href=/Wimborne.php class=NotYet>About Wimborne</a>
     <a href=/FestivalHistory.php class=NotYet>Festival History</a>
  </div>
</div>
<div class=dropdown onmouseover=NoHoverSticky(event)>
  <a href=/gallery>Gallery</a>
  <div class=dropdown-content>
     <a href=/gallery/2017>2017 Photos</a>
     <a href=/gallery/2016>2016 Photos</a>
     <a href=/gallery/2015>2015 Photos</a>
     <a href=/gallery/2014>2014 Photos</a>
     <a href=/gallery/2013>2013 Photos</a>
  </div>
</div>
<div class=dropdown onmouseover=NoHoverSticky(event)>
  <a href=/Also.php class=NotYet>Also</a>
  <div class=dropdown-content>
     <a href=/LiveNLoud.php class=NotYet>Live and Loud</a>
     <a href=/StillLiveNLoud.php class=NotYet>Still Live and Loud</a>
     <a href=/Lol.php class=NotYet>Laugh out loud</a>
<!--     <a href=/BuskersBash.php class=NotYet>Buskers Bash</a> -->
  </div>
</div>
<a href=/contact.php onmouseover=NoHoverSticky()>Contact</a>
<a href=http://facebook.com/WimborneFolk onmouseover=NoHoverSticky() target=_blank><img class=headericon src=/images/icons/Facebook.png alt=Facebook title="Wimborne Minster Folk Festival on  Facebook"></a>
<a href=http://twitter.com/WimborneFolk onmouseover=NoHoverSticky() target=_blank><img class=headericon src=/images/icons/Twitter.png alt=Twitter title="Wimborne Minster Folk Festival on Twitter"></a>
<a href=http://instagram.com/WimborneFolk onmouseover=NoHoverSticky() target=_blank><img class=headericon src=/images/icons/Instagram.png alt="Instagram" title="Wimborne Minster Folk Festival on Instagram"></a>
</div>
<?php 
  global $USERID;
  if ( $USER{'AccessLevel'} == $Access_Type['Participant'] ) {
    echo "<div class=Staff><div class=navigation>";
    switch ($USER{'Subtype'}) {
    case 'Side':
      echo "<a href=/int/DanceEdit.php?sidenum=$USERID onmouseover=NoHoverSticky()>Edit Side</a>";
      echo "<a href=/int/ShowDance.php?sidenum=$USERID onmouseover=NoHoverSticky()>Public view of Side</a>";
      echo "<a href=/int/DanceFAQ.php onmouseover=NoHoverSticky()>Dance FAQ</a>";
      break;
    case 'Act':
      echo "<a href=/int/MusicEdit.php?sidenum=$USERID onmouseover=NoHoverSticky()>Edit Act</a>";
      echo "<a href=/int/ShowMusic.php?sidenum=$USERID onmouseover=NoHoverSticky()>Public view of Act</a>";
      echo "<a href=/int/MusicFAQ.php onmouseover=NoHoverSticky()>Music FAQ</a>";
      break;
    case 'Other':
      echo "<a href=/int/MusicEdit.php?sidenum=$USERID&t=O onmouseover=NoHoverSticky()>Edit Act</a>";
      echo "<a href=/int/ShowMusic.php?sidenum=$USERID&t=O onmouseover=NoHoverSticky()>Public view of Act</a>";
      break;
    case 'Trader':
      echo "<a href=/int/TraderPage.php?id=$USERID onmouseover=NoHoverSticky()>Edit Trader Info</a>";
      echo "<a href=/int/ShowTrade.php?id=$USERID onmouseover=NoHoverSticky()>Public view of Trader</a>";
      echo "<a href=/int/TradeFAQ.php onmouseover=NoHoverSticky()>Trade FAQ</a>";
      break;
    }
    echo "</div></div>\n";
  }
?>

