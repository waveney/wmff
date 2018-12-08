<h2>Festival Software Admin Guide</h2>

This is intended for the sysadmin people looking after a festival website, not for general committee/staff members.<p>

<h2>Installation and initial setup</h2>

<ul>
<li>git clone https://github.com/waveney/wmff
<li>Make sure the sites document root is in the php_include_path (it wont work without this).  Also make the maximum file uploads at least 16M (highly desirable).
<li>Set up a mysql or mariadb user (may include a database)
<li>Run int/Initialise.php (from the web interface) - it will first time run prompt for the database user (above), the database name and its host.  
These should be stored, should you need to run again. 
<li>It will create many directories and permissions it needs 
<li>Initialise the database, create the tables and pre-load critical data into a few tables
<li>It will then prompt for a SysAdmin user and password to be user to administer the site (others can be added later)
<li>If sucessful it should eventually launch the staff page.  All other configuration can be setup from this.
<li>

This is under development.  In theory if it fails it can be re-run<p>

<h2>Setting up users</h2>
Under "Users" there is "Add User", it is also at the bottom of the user list.<p>

<table border>
<tr><td>What<td>Description
<tr><td>Name<td>Full name of person
<tr><td>Abrev<td>Used for timeline only - very short 2/3 letters to identify who, only used for timeline
<tr><td>Email<td>Real email address of the person, needed for all
<tr><td>Phone<td>Only shown on the user list, just makes committee life easier if all email/phone numbers are in one place
<tr><td>Festival Email<td>Festival email address for that person, usually forwarded to their email above.  Just put fred - the @yourfestival.org will be added on public pages as appropriate.<p>
The setting up of this forwarding is done outside of the festival system on your servers using server admin tools.
<tr><td>Login<td>Login username - required for all
<tr><td>Roll<td>What does that person do?  Shown on contacts page if that person is listed
<tr><td>No Tasks<td>Set this for test users, these wont be listed as a timeline person or on normal user lists
<tr><td>Access Level<td>Select as appropriate:<br>
<ul>
<li>SysAdmin - this is for people who manage the site, these can see and change everything
<li>Committee - your inner group managing the festival - these can see (almost) everything.  Financial information may be restricted to those who need it (optional feature).
<li>Staff - A slightly less important person on the festival, these can see a lot
<li>Steward - For Stewards, Sound engineers etc to see information related to them alone
<li>Upload - Not currently used
<li>Participant - Used as a temporary status by Dance Sides / Musical Acts / Traders / Volunteers etc updating their own data.  Do not use this for a login level
<li>System/Internal - internal workings only - not for outside use
</ul>
<tr><td>Image<td>Picture to appear on contacts page if appropriate - optional
<tr><td>Show on Contacts Page<td>Yes - they are listed<br>No - not listed<br>Role only - the email address is shown and what they do, but not their name
<tr><td>Change areas<td>For each area what level of access does the person have.  meaningfull for committee, staff and steward level.<br>No - read, but not change<br>Edit - Make changes<br>
Edit and Report - can make changes, also get an email when others make changes to that area (may no longer work).  See below for list of areas
<tr><td>Change Sent, Access Key<td>These are for internal workings, dont change
</table><p>

<h3>Control areas</h3>
Currently these are hard coded, but the list can be extended relatively easily (list at start of fest.php and add to FestUsers table) - dont remove from the list, gaps are fine.<p>
<table border>
<tr><td>Area<td>What
<tr><td>Docs<td>Admin control of document storage - can change other peoples files
<tr><td>Dance<td>Ability to setup dance sides, edit their details, schedual dance spots
<tr><td>Trade<td>Ability to setup traders, send them quotes, accept/cancel their bookings
<tr><td>Users<td>Setup and manage users - currently not meaningful as only sysadmin can do it, however may allow setup of staff/stewards later
<tr><td>Venues<td>Setup venues and events programme events and similar activities.  Note if someone with this privalige level sets up dance spots/times, 
then the actual use of them is upto the people with dance control.
<tr><td>Music<td>Ability to setup acts, edit their details, setup contracts etc
<tr><td>Sponsors<td>Setup and administer sponsors and advertisers
<tr><td>Finance<td>See financial data on all performers and traders, set and administer budgets, mark invoices as paid, issue credit notes etc
<tr><td>Craft<td>Not yet used
<tr><td>Other<td>Ability to setup other acts, edit their details, setup contracts etc - for performers who dont come under dance/music - eg some Childrens performers
<tr><td>TLine<td>Ability to administer the timeline and change tasks not just their own
<tr><td>Bugs<td>Can administer the bug list, also if set to edit and report get emails when new ones are made.
<tr><td>Photos<td>Can use the Photo upload and gallery management tools
<tr><td>Comedy<td>Ability to setup comedy acts, edit their details, setup contracts etc (for near future enhancement)
<tr><td>Family<td>Ability to setup Family/Childrens, edit their details, setup contracts etc (for near future enhancement)
<tr><td>News<td>Ability to edit Articles and News (for near future enhancement)
</table><p>

Note the first 10 users are reserved for internal use, though only a few are actually used currently.<p>

Looking at a list of users, if you click the "All users" top right, it will show internal users, hidden test users and those with removed access.<p>

The main code controls are:<br>
<li>Access(Level,area,detail) - Level is min access level, area if present is edit area required, detail only used for participants.  Returns false if not allowed.
<li>A_Check(Level,area) - hard check - if failed Error_Page called directly, no return

<h2>Initial Data Setup (beyond the first load above)</h2>

<h3>Master Data</h3>
This is data about the festival that is multi-year.<p>

<table border>
<tr><td>Feild<td>Details
<tr><td>Festival Name<td>The full name of the festival - this is needed
<tr><td>Shortname<td>eg WMFF for Wimborne Minster Folk Festival - this is needed
<tr><td>Version<td>Software version number - currently manually updated for every git pull - this is ESSENTIAL keep it up to date - changing this forces reload of cached data by clients.  
Hope to automate it soon.
<tr><td>Show year/ Plan year<td>Show year is what year's festival the public side defaults to.  Plan year is what year the staff pages default to.  Typically the Plan year moves to the
next year as soon as the festival has finished, the Show year only when material for the comming year is available.
<tr><td>Host URL<td>This is the host the SMTP will use to send outgoing email.
<tr><td>SMTP user/password<td>This is used for the system to send emails
<tr><td>Features<td>Controls Feature availability - see below
<tr><td>Ads - image and link<td>On public pages an advert can be placed on the left/right of the main banner.  Give the image and links to be followed (if any)
<tr><td>Website Banner<td>The image for the main banner
<tr><td>Analytics code<td>The Javascript to provide Analytics on all pages.  (Don't include the &lt;script&gt;,  just the code to go within)
<tr><td>Google Maps API<td>The API key used to provide Directions on Maps - if not present no directions buttons will appear.  
Note for very large festivals there is a potential cost if used more than Google's free allowance. 
</table><p>

<b>Features</b><p>
This controls many features.  Often used to try things out initially, some controls however are ongoing, this list is only for the ongoing ones, anything else will disappear in time:<p>

Format of controls: Control : Value : Comment<p>
<table border>
<tr><td>CampControl<td>0 - no performer camping, 1 Camping can be enabled by festival for this performer but then they fill in how much, 2 Camping entirely controlled by festival
<tr><td>DanceDefaultSlot<td>30 = 30 minute default dance spot, 45 for a 45 minute default.  Gives the default subevent split
<tr><td>DanceComp<td>Show tick box for the dance competition
<tr><td>PaymentTerms<td>30 = 30 day default payment terms on invoices
<tr><td>RestrictFinance<td>If set to 1 it restricts who can see financial information on performers
<tr><td>VolDBS<td>If set to 1, enable DBS input for Volunteers
</table><p>

<H3>General Year Data</h3>
This is festival data for each year.  You can create records as many years in advance as you wish ahead.<p>

<table border>
<tr><td>State of Family/Special<td>See Event types below (same meaning, Family and Special are propeties that any event can add)
<tr><td>State of Trade<td>Not used
<tr><td>Date of Friday<td>Date of the Friday of the festival, it works out everything else from here.
<tr><td>First day/Last Day<td>It can start up to 4 days before the "Friday" (ie Monday), or as late as the Sunday.  It can finish up to 10 days after the "Friday" ie on a Tuesday.
Setting these limits various displays.  Note: Passes and camping don't currently run for this full range of options.  After setting these save changes to refresh the page.
<tr><td>Dates of Price Chnages<td>Their is scope for two different pre festival price hikes, as well ass the on door being different.  These give the price hike dates.
<tr><td>Priced Complete<td>If ticked the ticket page says that all ticketed events for the day are now listed, tick this when the last ticketed event is listed for each day.  
Will probably expand this to other days soon.
<tr><td>Trade Invoice date<td>The date when the final invoices for trade will be sent - when a booking is made near to this date a single invoice is sent rather than a deposit one followed
by the final invoice.
<tr><td>Date Last Trade Payments<td>The date (pre festival) when all payments must be made.  Invoices sent near to this date have shorter payment periods than normal.
<tr><td>Ticket Control<td>Select: "Not Yet" tickets not yet for sale, "Open" tickets are for sale, "Closed" too late for online booking (does not preclude on door sales).
<tr><td>Pass Codes<td>For Weekeend, Friday, Saturday and Sunday Passes: TicketSource Event short code, the Basic price (prior to price hike 1) a price after price hike 2 (if used) 
Descriptive text for the ticket page about the pass.  (Extending the passes is easy and straight forward)
<tr><td>Programme Book<td>Cost of the programme book (Not sure this is actually used anywhere yet)
<tr><td>Booking Fee<td>if set what is the booking fee - examples "&pound;1" and "approximately 3.5%"
<tr><td>Camping<td>There are many variables, the following should allow some flexibility, more can be added
<tr><td>Camping Cost<td>This is the cost to the festival of one night performer camping - automatically accounted for in budgetry calculations
<tr><td>Camping Fess<td>Cost to camp for 1-4 nights
<tr><td>Camping Codes<td>Ticketsource shortevent codes for all camping options available.  If blank that option is not available
</table><p>

If you are looking at the last currently defined year at the bottom will be a link to create the next year.<p>

<h3>Dance/Music Types</h3>
Setup the dance types you wish to categorise.  Give them a relative importance - order they will apear and give the colour to be used.  Not currently used on the public side.<p>

A similar table may appear for music in time.<p>

<h3>Trade Types and base prices</h3>
This can be setup by staff with Trade access.  The first one is the default<p>
<ul>
<li>Setup the trade types you wish to recognise.
<li>Give them a relative order.
<li>A Colour for the trade type.
<li>Give a base price (will say prices from that number), the deposit price, the price can be per day, if so tick the box.
<li>If it is an optional extra element (power) tick additional, (this may not work).
<li>If it is a charity stall tick charity number to enforce the addition of the charity number.
<li>Likewise if it sells food or drink, tick the need local authority.
<li>If insurance is required (it probably is in all cases), tick insurance, likewise risk assesment (mechanism to use this is currently missing).
<li>Artisan Msgs, changes the wording of some trade emails.
<li>Open means submissions are accepted for this type of trade.
<li>The Invoice Code, is the default code specified under Finance to apply to that type of trade, IF the trade location has a code, that will surperced any setting here.
<li>Lastly description is a sentence or two that says what type of trade it is and/or puts restrictions - for example for Wimborne Local Artisan means wiithin 20 miles of Wimborne.
</ul>

<h3>Trade Locations</h3>
This can be setup by staff with Trade access.<p>
<ul>
<li>Setup the locations you MAY wish to recognise
<li>Give it a name
<li>Choose an appropriate prefix to describe it
<li>Tick Power if it is an option for the location
<li>Give number of pitches
<li>Untick "In use" if not used currently
<li>Change Days if not both days
<li>Artisan Msgs, same as above for trade type - either sets the alternative message proformas
<li>Invoice Code - if set overrides the code from the trade type
<li>Notes - for admin use only
</ul><p>

Coming soon: Maps for each location to allow click and drag to position traders on the maps<p>


<h3>Event Types</h3>
This has the big controls for event types. DO NOT HAVE TOO MANY OF THESE.  <p>
<ul>
<li>Name - simple broad categorisation - Dancing, Music, Session etc.  Plurall - what ever to include when listing many of the event
<li>Public - If not set these events will never be public.  Typically used for sound checks.
<li>Has Music/Dance/Other - what broad categories it should appear under - used to create Music/Dance/Other event grids.
<li>Set the Use Imp flag to bring headline particpants to top of an event, they still get bigger fonts.
<li>Set Format to drive EventShow rules 0=All Large, 2=Switch to large at Importance-High, 9+=All Small
<li>State - This is a big control to how and where the events are listed (see also General Year Data for Special/Family):
  <ul>
  <li>Very Early - only seen by staff
  <li>Draft - only seen by staff and participants if they are in that event
  <li>Partial - Seen by the public, but with warnings that not all of this type of event is published
  <li>Provisional - Seen by the public - probably complete
  <li>Complete - seen by the public, no changes are expected
  </ul>
<li>Set Inc Type to indicate event type in description if it is not part of the events name
<li>Set the Not critical flag for sound checks - means that this event type does not have to be complete for contract signing.
<li>Set No Part if event type is valid without any participants
<li>First Year - First year this event type is used at the festival - limits backtracking to show events of previous years beyond this.
</ul>

<h3>Additional Map Points, Map Point Types</h3>
These allow extra information on the festival map, (in addition to the venues)<p>

Map Point Types defines the icons to be used for each additional point.<p>

Additional Map Points:<p>
<table border>
<tr><td>Feild<td>Details
<tr><td>Name<td>Name the map point
<tr><td>What<td>Select type of map point (and hence icon used)
<tr><td>Lat/Long<td>Location of the point - look up locations on Google Maps and record them here (you need at least 3 digits after the decimal points)
<tr><td>Importance<td>This govens the map zoom levels that the feature will be shown at.  The range is from 1 - Whole Earth to 19 very close (20 is street view)<br>
If a single value is used - this is the zoom level when it first appears and it will remain visible however far you zoom in.<br>
If a range is given (eg 14-16) this gives a range of zoom levels that it will be shown
<tr><td>Text Size<td>If set it gives the font size the feature is to be shown at (if not the default)
<tr><td>Not Used<td>If ticked, the Map point will not be shown
<tr><td>Directions<tdd>Tick if you want to enable a directions buttons to appear for this location
<tr><td>Link<td>A link to be used on the map point (if any)
</table>

Example: I use a big text label for Wimborne at low zoom, then switch to individual features when zoomed in.<p>

<h3>Email Proformas</h3>

<h2>Venues and Events</h2>
Venues start from 10, allowing special meaning for a few values: 0 = Info Point, 1 = None, 2-9 unused currently

<h2>
