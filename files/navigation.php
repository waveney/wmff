<?php
  include_once("int/fest.php");
  global $Access_Type,$USER,$YEAR;
  Set_User();
  if (isset($_COOKIE{'WMFF'}) || isset($_COOKIE{'WMFF2'})) {
    echo "<div class=Staff><div class=navigation>";
    echo "<a href=/int/Staff.php onmouseover=NoHoverSticky()>Staff Tools</a>";
    if (!isset($USER{'Subtype'})) {
      echo "<a href=/int/Dir.php onmouseover=NoHoverSticky()>Docs</a>";
      echo "<a href=/int/TimeLine.php?Y=$YEAR onmouseover=NoHoverSticky()>Time Line</a>";
      echo "<a href='/int/Login.php?ACTION=LOGOUT' onmouseover=NoHoverSticky()>Logout " . $USER['Login'] . "</a>\n";
    }
    echo "</div></div>";
  }
  $host= "https://" . $_SERVER['HTTP_HOST'];

  echo "
<div class=navigation>
<a href=$host/ onmouseover=NoHoverSticky()>Home</a>
<!-- <a href=$host/news.php onmouseover=NoHoverSticky()>News</a> -->
<div class=dropdown onmouseover=NoHoverSticky(event)>
  <a onclick=NavStick(event)>Line-up</a>
  <div class=dropdown-content>
     <a href=$host/LineUpDance.php>Dance</a>
     <a href=$host/LineUpMusic.php>Music</a>
     <a href=$host/LineUpOther.php>Family/Comedy/Other</a>
  </div>
</div>
<div class=dropdown onmouseover=NoHoverSticky(event)>
  <a onclick=NavStick(event)>Whats On</a>
  <div class=dropdown-content>
     <a href=$host/WhatsOnWhere.php >By Venue</a>
     <a href=$host/WhatsOnWhen.php >By Time</a>
     <a href=$host/WhatsOnNow.php class=NotNotYet>Now</a>
     <a href=$host/int/ShowDanceProg.php?Cond=1&Pub=1&Y=$YEAR>Dancing</a>
     <a href=$host/Sherlock.php?t=Music >Music</a>
     <a href=$host/Sherlock.php?t=Special>Special Events</a>
     <a href=$host/Sherlock.php?t=Family>Family</a>
     <a href=$host/Sherlock.php?t=Ceilidh >Ceilidhs</a>
     <a href=$host/Sherlock.php?t=Workshop >Workshops</a>
     <a href=$host/Sherlock.php?t=Comedy >Comedy</a>
     <a href=$host/Sherlock.php?t=Session >Sessions</a>
     <a href=$host/Sherlock.php?t=Craft>Craft</a>
     <a href=$host/Sherlock.php?t=Other>Other</a>
     <a href=$host/TraderList.php>Traders</a>
  </div>
</div>
<a href=$host/Tickets.php onmouseover=NoHoverSticky()>Tickets</a>
<div class=dropdown onmouseover=NoHoverSticky(event)>
  <a onclick=NavStick(event)>Info</a>
  <div class=dropdown-content>
     <a href=$host/Map.php>Festival Map</a>
     <a href=$host/InfoCamping.php>Camping</a>
     <a href=$host/InfoParking.php>Parking</a>
     <a href=$host/InfoGettingHere.php>Travel - Trains, Buses and Taxis</a>
     <a href=$host/InfoMailingList.php class=NotYet>Mailing List</a>
     
     <a href=$host/InfoWater.php>Water Bottle Refills</a>
     <a href=$host/InfoSponsors.php>Sponsorship</a>
     <a href=$host/InfoStewards.php>Volunteers</a>
     <a href=$host/info/trade>Trade Stands</a>
     <a href=$host/contact.php>Contact</a>
     <a href=$host/RoadClosure.php >Road Closures</a>
     <a href=$host/info/thanks>With Thanks</a>

     <a href=$host/InfoData.php>Data Records</a>
     <a href=$host/InfoSoftware.php>Software</a>
  </div>
</div>
<div class=dropdown onmouseover=NoHoverSticky(event)>
  <a onclick=NavStick(event)>About</a>
  <div class=dropdown-content>
     <a href=$host/int/ShowArticles.php?w=AboutWMFF class=NotYet>About the Festival</a>
     <a href=$host/int/ShowArticles.php?w=FestHistory class=NotYet>Festival History</a>
     <a href=$host/int/ShowArticles.php?w=DanceStyles>Dance Styles</a>
     <a href=$host/int/ShowArticles.php?w=NWDanceComp >North West Dance Competition</a>
     <a href=$host/int/ShowArticles.php?w=AboutWimborne class=NotYet>About Wimborne</a>
     <a href=$host/int/ShowArticles.php?w=EnjoyWimborne class=NotYet>Enjoy Wimborne</a>
  </div>
</div>
<a href=$host/InfoStewards.php onmouseover=NoHoverSticky()>Volunteer</a>
<div class=dropdown onmouseover=NoHoverSticky(event)>
  <a onclick=NavStick(event)>Gallery</a>
  <div class=dropdown-content>
     <a href=$host/gallery/gallery2018.php>2018 Photos and Videos</a>
     <a href=$host/int/ShowGallery.php?g=2>2017 Photos</a>
     <a href=$host/gallery/2016>2016 Photos</a>
     <a href=$host/gallery/2015>2015 Photos</a>
     <a href=$host/gallery/2014>2014 Photos</a>
     <a href=$host/gallery/2013>2013 Photos</a>
     <a href=$host/int/ShowGallery.php?g=3>2017 Laugh Out Loud</a>
  </div>
</div>

<div class=dropdown onmouseover=NoHoverSticky(event)>
  <a onclick=NavStick(event )>Also</a>
  <div class=dropdown-content>
     <a href=$host/LiveNLoud.php >Live and Loud</a>
     <a href=$host/BuskersBash.php>Buskers Bash</a>
     <a href=$host/OtherFest.php>Other Festivals</a>
  </div>
</div>

<a href=http://facebook.com/WimborneFolk onmouseover=NoHoverSticky() target=_blank>
<img class=headericon src=/images/icons/Facebook.png alt=Facebook title='Wimborne Minster Folk Festival on Facebook'></a>
<a href=http://twitter.com/WimborneFolk onmouseover=NoHoverSticky() target=_blank>
<img class=headericon src=/images/icons/Twitter.png alt=Twitter title='Wimborne Minster Folk Festival on Twitter'></a>
<a href=http://instagram.com/WimborneFolk onmouseover=NoHoverSticky() target=_blank>
<img class=headericon src=/images/icons/Instagram.png alt=Instagram title='Wimborne Minster Folk Festival on Instagram'></a>
<!--<div id=GS>
			<div id=googleSearch>
				<div class=gcse-search></div>
			</div>
		</div>
</div>-->
</div>
";

  global $USERID;
  if ( isset($USER{'AccessLevel'}) && $USER{'AccessLevel'} == $Access_Type['Participant'] ) {
    echo "<div class=Staff><div class=navigation>";
    switch ($USER{'Subtype'}) {
    case 'Side':
      echo "<a href=$host/int/DanceEdit.php?sidenum=$USERID onmouseover=NoHoverSticky()>Edit Side</a>";
      echo "<a href=$host/int/ShowDance.php?sidenum=$USERID onmouseover=NoHoverSticky()>Public view of Side</a>";
      echo "<a href=$host/int/DanceFAQ.php onmouseover=NoHoverSticky()>Dance FAQ</a>";
      break;
    case 'Act':
      echo "<a href=$host/int/MusicEdit.php?sidenum=$USERID onmouseover=NoHoverSticky()>Edit Act</a>";
      echo "<a href=$host/int/ShowMusic.php?sidenum=$USERID onmouseover=NoHoverSticky()>Public view of Act</a>";
      echo "<a href=$host/int/MusicFAQ.php onmouseover=NoHoverSticky()>Music FAQ</a>";
      break;
    case 'Other':
      echo "<a href=$host/int/MusicEdit.php?sidenum=$USERID&t=O onmouseover=NoHoverSticky()>Edit Act</a>";
      echo "<a href=$host/int/ShowMusic.php?sidenum=$USERID&t=O onmouseover=NoHoverSticky()>Public view of Act</a>";
      break;
    case 'Trader':
      echo "<a href=$host/int/TraderPage.php?id=$USERID onmouseover=NoHoverSticky()>Edit Trader Info</a>";
      echo "<a href=$host/int/ShowTrade.php?id=$USERID onmouseover=NoHoverSticky()>Public view of Trader</a>";
      echo "<a href=$host/int/TradeFAQ.php onmouseover=NoHoverSticky()>Trade FAQ</a>";
      break;
    }
    echo "</div></div>\n";
  }
?>

