<?php
  include_once("int/fest.php");

  dohead("Folk Festival Software");
?>
  <h2 class=subtitle>Folk Festival Software</h2>
<h3>BACKGROUND</h3>
A sophisticated database and website system is being developed for Wimborne Minster Folk Festival, using experence and knowledge from providing facilities for many events 
(Folk Festivals and Science
Fiction Conventions) over many years.  It is going to be of considerable use to other festivals and events in the future.<p>

Rather than having lots of non-integrated spreadsheets and lists on many peoples computers to be lost when people leave and difficult to maintain, 
the idea is to keep everything in one place (heavily backed up).  All records are central, with the philosophy that at the committee level, (almost) 
everything is readable by all, but only limited people can update each type of area.<p>

<h3>FRONT END</h3>
Most pages are generated on the fly as needed, showing performers (music, dance, other), tickets and prices, events listed by venue, time, type.<p>

Everything that can be a link is a link.  An event links to venues and participants, venues have list of all events at venue, 
each participant has links to where and when they are on and who they
are with.  Each performer has their own page with details of their programme, who they are and about them.<p>

This is not yet as sophisticated as the back end yet, but is getting there.<p>

<h3>BACK END</h3>
This is where 90%+ of the system is.  It has support for:
<ul>
<li>Common document storage - across the festival committee and staff, with some access control based on function
<li>Timeline management - who is doing what and when, also recuring tasks from year to year 
<li>Music management - records of artists, contracts and their programmes.  (Not as full feature as dance at the moment, but will be expanded this year)
<li>Dance management - many year records of dance sides, their wishes, requests, working out the dance scheduals, 
ensuring they have breaks, dance when they want and handle performer overlaps
<li>Other Performer management (eg Childrens) - Similar to Music
<li>Trade Stand Management - booking, allocating, charging for
<li>Event management - details of venues, events, maps and posters for venues
<li>Finance - invoices, budgets
<li>User management - different users, with capabilities based on user roles and access level
<li>News and front page article management - a Content Management System to manage what appears and when
<li>Galleries - setup, import, crop, caption photos
<li>Multiyear working - Everything from previous years is stored, forward planning can be made as many years in advance as wanted
<li>Many other facilities
</ul>

This sits on top of a database with everything stored in it.<p>

This is potentially available as a number of separate packages, dance, music, documents, timeline, trade etc.<p>

<h3>OTHER FESTIVALS</h3>
Realising that what has been developed for Wimborne, could be of considerable use for other festivals, some work has been done to generalise it so others could use it.<p>

This is an on going development and some parts are still very Wimborne specific, the intention is that it is licenseable in the future, if you are interested in what has been
developed so far and would like to be involved, please contact the <a href=mailto:webmaster@wimbornefolk.co.uk>Webmaster</a>.<p>

Note: The intention is to license the system, but (currently) none of the data about individual dance sides, performers etc.<p> 

The raw system is at <a href=https://github.com/waveney/wmff>Github</a>.<p>


<?php
  dotail();
?>
