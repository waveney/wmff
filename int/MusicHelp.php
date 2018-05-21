<?php
  include_once("fest.php");
  A_Check('Upload');
?>

<html>
<head>
<title>WMFF Staff | Music Help</title>
<?php include_once("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
</head>
<body>
<?php include_once("files/navigation.php"); ?>
<div class="content">
<p>
<h2>Introduction</h2>
Expanding the database to handle Music was a larger task than I originally thought, when I realised that Music is booked in a different way to
how Dance Sides are booked, so rather than try to change the Dance booking for Music I wrote Music Booking as a largely seperate system.  
It is work in progress at this point.<p> 

Headings that are not yet links on the Staff page, will become working features as they are written.<p>

<h2>Basic Operation</h2>
From Music you can Add Acts to the database, generate contracts, get them recorded, confirmed, changed and all details about an Act can be maintained.
In the near future a visual representation of the Music will be presented and possibly manipulated to aid in managing the whole programme.<p>

Under Venues and Events, Venues can be added, and managed (rarely needed).  Also Events can be setup, changed, divided into sub events and checked for consistency.
An Event may be a Concert, a Ceildih, a sound check (or anything else).  A large event such as concert may be divided into sub events to allow for managing the timing
of individual elements.<p>

Most of the material (if not all) for the printed programme, publicity posters, the public side of the website will all come straight out of the database.<p>

<h2>Adding/ Managing Acts</h2>

<H2>Managing Events</h2>

<h2>Problems?</h2><p>
If something goes wrong, or its not obvious or you wish it could do something extra. <p>

Then either contact Richard (07718 511 432, Email: <a href=mailto:richard@wavwebs.com>Richard@wavwebs.com</a>)
or <a href=AddBug.php>Raise a bug or Feature request</a>.<p>


</div>

<?php include_once("files/footer.php");  ?>
</body></html>

