<?php
  include_once("int/fest.php");

  dohead("Parking",[],1);

  include_once("int/MapLib.php");
?>
<div class=TwoCols><script>Register_Onload(Set_ColBlobs,'Blob',5)</script>
<div class=OneCol id=TwoCols1>

<div id=Blob0>
<h2>Festival Parking</h2>
<strong>Allenbourne School</strong> will have daytime parking on their school fields for <strong>&pound;5.00</strong> per vehicle on Saturday &amp; Sunday only, 
with no overnight parking. Access is from East Borough, just off the B3078, Cranborne Road. <button onclick=ShowDirect(1000009)>Directions</button><p>

</div><div id=Blob1><div id=BlobMap>
<div id=MapWrap>
<div id=DirPaneWrap>
<div id=DirPane><div id=DirPaneTop></div><div id=Directions></div></div>
</div><div id=map  style='min-height:300px; max-height:400px'></div>
</div>
<?php    Init_Map(1,4,16,4); ?>

</div></div><div id=Blob4>

<h2>Long Term Car Parks</h2>
The principle long term car parks are:<p>

<div class=tablecont><table class=InfoTable>
<tr><td>Allenview North and West (behind the Allendale Centre) <td> <button onclick=ShowDirect(1000016)>Directions</button>
<tr><td>Westfield Close (Near Green Man/Minster Arms) <td> <button onclick=ShowDirect(1000011)>Directions</button>
<tr><td>Walford Mill <td> <button onclick=ShowDirect(1000010)>Directions</button>
<tr><td>Leigh Road <td> <button onclick=ShowDirect(1000022)>Directions</button>
<tr><td>Poole Road<td> <button onclick=ShowDirect(1000025)>Directions</button>
<tr><td>Old Town Road (Near the Green Man)<td> <button onclick=ShowDirect(1000017)>Directions</button>

</table></div>
</div><div id=Blob2>
<h2>Short Term Car Parks</h2>
The principle short term car parks are:
<ul>
<li>King Street (Near the Minster)
<li>Waitrose
<li>Allenview South (Beside the Allendale Centre)
<li>Crownmead (Co Op)
<li>Hanham Road South (Co Op)
</ul>

</div><div id=Blob3>
<!--<h2 class=subtitle>Bicycle Parking</h2>
There will be extensive Bicycle parking provided in what is normally the Hanham Road car park.<p>

Please don't use the limited cycle racks on the High Street and by the Minster.<p>
-->
<h2>Closed Car Parks</h2>
The High Street carpark will be inaccessable from 5pm on Friday until Sunday evening.<p>

The part of Allenview North Carpark will be closed from Thursday Evening until Monday morning.  For setting up the Microbreweries.

</div></div><div class=OneCol id=TwoCols2></div></div>
<?php
  dotail();
?>
