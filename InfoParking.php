<?php
  include_once("int/fest.php");

  dohead("Parking");

  include_once("int/MapLib.php");
?>
<h2 class="maintitle">Parking</h2>
<div class=venueimg>
<img width=100% src=http://wimbornefolk.co.uk/images/Venues/Allenbourne.jpg>
<p><div id=MapWrap>
<div id=DirPaneWrap><div id=DirPane><div id=DirPaneTop></div><div id=Directions></div></div></div>
<p><div id=map></div></div>
</div>
<?php    Init_Map(1,4,15,0); ?>


<h2 class=subtitle>Festival Parking</h2>
<strong>Allenbourne School</strong> will have daytime parking on their school fields <!--for <strong>&pound;5.00</strong> per vehicle --> on Saturday &amp; Sunday only, 
with no overnight parking. Access is from East Borough, just off the B3078, Cranborne Road. <button onclick=ShowDirect(1000009)>Directions</button><p>


<strong>Meadows Camping</strong> offer all day parking <!--for <strong>&pound;3.00</strong> per vehicle per day,--> which is available for non-camping visitors. 
Vehicles left on site overnight will not be subsequently charged unless taken off site and re-entering. <button onclick=ShowDirect(1000002)>Directions</button><p>

<h2 class=subtitle>Long Term Car Parks</h2>
The principle long term car parks are:
<ul>
<li>Allenview West (behind the Allendale Centre)
<li>Allenview North (behind the Allendale Centre)
<li>Westfield Close (Near Green Man/Minster Arms)
<li>Walford Mill
<li>Leigh Road 
<li>Poole Road
<li>At the Market

</ul>

<h2 class=subtitle>Short Term Car Parks</h2>
The principle short term car parks are:
<ul>
<li>King Street (Near the Minster)
<li>Waitrose
<li>Allenview South (Beside the Allendale Centre)
<li>Crownmead (Co Op)


</ul>

<!--<h2 class=subtitle>Bicycle Parking</h2>
There will be extensive Bicycle parking provided in what is normally the Hanham Road car park.<p>

Please don't use the limited cycle racks on the High Street and by the Minster.<p>
-->
<h2 class=subtitle>Closed Car Parks</h2>
The High Street carpark will be closed from 6pm Thursday to be transformed into a festival venue (its inaccessible for cars anyway during the festival).<p>

The Hanham Road south car park will not be a public car park during the festival. <!-- Part will be used for Bicycle parking (see above), 
part used for the main taxi rank.--><p>

<?php
  dotail();
?>
