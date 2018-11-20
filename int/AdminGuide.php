<h2>Festival Software Admin Guide</h2>

This is for the admin people looking after a festival, not for general committee members.<p>

<h2>Installation and initial setup</h2>

Under development<p>

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
<tr><td>No Tasks<td>Set this for test users, these wont be listed as a timeline person
<tr><td>Access Level<td>Select as appropriate:<br>
<ul>
<li>SysAdmin - this is for people like you who manage the site, these can see and change everything
<li>Committee - your inner group managing the festival - these can see (almost) everything.  Financial information is restricted to those who need it.
<li>Staff - A slightly less important person on the festival, these can see a lot 
<li>Steward - For Stewards, Sound engineers to see information related to them alone
<li>Upload - Not used
<li>Participant - Used as a temporary status by Dance Sides/ Musical Acts / Traders etc updating their own data.  Do not use this for a login level
<li>System/Internal - not for outside use
</ul>
<tr><td>Image<td>Picture to appear on contacts page if apprropriate - optional
<tr><td>Show on Contacts Page<td>Yes - they are listed<br>No - not listed<br>Role only - the email address is shown and what they do, but not their name
<tr><td>Change areas<td>For each area what level of access does the person have.  meaningfull for committee, staff and steward level.<br>No - read, but not change<br>Edit - Make changes<br>
Edit and Report - can make changes, also get an emal when others make changes to that area.  See below for list of areas
<tr><td>Change Sent, Access Key<td>Internal workings, dont change
</table><p>

<h3>Control areas</h3>
Currently these are hard coded, but can be extended relatively easily.<p>
<table border>
<tr><td>Area<td>What
<tr><td>Docs<td>Admin control of document storage - can change other peoples files
<tr><td>Dance<td>Ability to setup sides, edit their details, schedual dance spots
<tr><td>Trade<td>Ability to setup traders, send them quotes, accept/cancel their bookings
<tr><td>Users<td>Setup and manage users - currently not meaningful as only sysadmin can do it, however may allow setup of staff/stewards later
<tr><td>Venues<td>Setup venues and events programme events and similar activities.  Note if someone with this privalige level sets up dance spots/times, 
then the actual use of them is upto the people with dance control.
<tr><td>Music<td>Ability to setup acts, edit their details, setup contracts etc
<tr><td>Sponsors<td>Setup and administer sponsors and advertisers
<tr><td>Finance<td>See financial data on all performers and traders, set and administer budgets, mark invoices as paid, issue credit notes etc
<tr><td>Craft<td>Not used yet
<tr><td>Other<td>Ability to setup other acts, edit their details, setup contracts etc - for performers who dont come under dance/music - eg some Childrens performers
<tr><td>TLine<td>Ability to administer the timeline and change tasks not just their own
<tr><td>Bugs<td>Can administer the bug list, also if set to edit and report get emails when new ones are made.
<tr><td>Photos<td>Can use the Photo upload and gallery management tools
</table><p>

Note the first 10 users are reserved for internal use, only a few are actually used currently.<p>

Looking at a list of users, if you click the "All users" top right, it will show internal users, hidden test users and those with removed access.<p>

<h2>Initial Data Setup (beyond the first load above)</h2>

<h3>Master Data</h3>
This is data about the festival that is multi year.<p>

<table border>
<tr><td>Feild<td>Details
<tr><td>Festival Name<td>The full name of the festival - this is needed
<tr><td>Shortname<td>eg WMFF for Wimborne Minster Folk Festival - this is needed
<tr><td>Version<td>Software version number - currently manually updated for every git pull - this is ESSENTIAL keep it up to date - changing this forces reload of cached data.  
Hope to automate it soon.
<tr><td>Show year/ Plan year<td>Show year is what year's festival the public side defaults to.  Plan year is what year the staff pages default to.  Typically the Plan year moves to the
next year as soon as the festival has finished, the Show year only when material for the comming year is available.
<tr><td>Host URL<td>This is the host the SMTP will use to send outgoing email.
<tr><td>SMTP user/password<td>This is used for the system to send emails
<tr><td>Features<td>Controls Feature availability - see below
<tr><td>Ads - image and link<td>On public pages an advert can be placed on the left/right of the banner.  Give the image and links to be followed (if any)
<tr><td>Website Banner<td>The image for the main banner
<tr><td>Analytics code<td>The Javascript to provide Analytics on all pages.  (Don't include the &lt;script&gt;,  just the code to go within)
<tr><td>Google Maps API<td>The API key used to provide Directions on Maps - if not present no directions buttons will appear.  Note for very large festivals there is a potential cost if used
more than Google's free allowance. 
</table><p>

<b>Features</b><p>
This controls many features.  Often used to try things out initially, some controls however are ongoing, this list is only for the ongoing ones, anything else will disappear in time:<p>

Format of controls: Control : Value : Comment<p>
<table border>
<tr><td>CampControl<td>0 - no performer camping, 1 Camping can be enabled by festival for this performer but then they fill in how much, 2 Camping entirely controlled by festival
<tr><td>DanceDefaultSlot<td>30 = 30 minute default dance spot, 45 for a 45 minute default.  Gives the default subevent split
<tr><td>DanceComp<td>Show tick box for the dance competition
<tr><td>PaymentTerms<td>30 = 30 day default payment terms on invoices
</table><p>

<H3>General Year Data</h3>
This is festival data for each year.  You can create records as many years as you wish ahead.<p>

<table border>
<tr><td>State of Family/Special<td>See Event types below
<tr><td>State of Trade<td>Not used
<tr><td>Date of Friday<td>Date of the Friday of the festival, it works out everything else from here
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
This can be setup by staff the Trade access.  The first one is the default<p>
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
This can be setup by staff the Trade access.<p>
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
This has the big controls for event types.<p>
<ul>
<li>Name - simple broad categorisation - Dancing, Music, Session etc.  Plurall - what ever to include when listing many of the event
<li>Public - If not set these events will never be public.  Typically used for sound checks.
<li>Has Music/Dance/Other - what broad categories it should appear under - used to create Music/Dance/Other event grids.
<li>Set the Use Imp flag to bring headline particpants to top of an event, they still get bigger fonts.
<li>Set Format to drive EventShow rules 0=All Large, 2=Switch to large at Importance-High, 9+=All Small
<li>State - This is a big control to how and where the events are listed:
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
<li>First Year - First year this event type is used at te festival - limits backtracking to show events of previous years beyond this.
</ul>

<h3>Map Point Types</h3>

<h3>Email Proformas</h3>



<h2>
