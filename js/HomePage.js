// Sponsors on home page

  // Read sponsors into array
  // Work out width of winow and hence number of icons
  // randomly choose icons
  // setup periodic callback
  // choose random icon and random position to update
  // need record of icons in use
$(document).ready(function() {
  var SponUse = [];
  var SponPos = [];
  var Spons;
  var maxi;
  var recent = -1;

  function SetupSpons() {
    Spons = $('.SponsorsIds');
    SponUse = [];
    SponPos = [];
    var wid = window.innerWidth;
    for(var i=0;i*200<wid;i++) {
      var elem = Math.floor(Math.random() * Spons.length);
      var tries=1;
      while (SponUse[elem] && tries++ <10) elem = Math.floor(Math.random() * Spons.length);
      SponUse[elem]=i+1;
      $('#SponsorRow').append( "<td id=#SponPos" + i + " class=HomePageSponsors>" + Spons[elem].innerHTML );
      SponPos[i] = elem;
      maxi=i;
    }
  }
  
  function UpdateSpon() {
    var pos = Math.floor(Math.random() * (maxi+1));
    if (pos == recent) pos = Math.floor(Math.random() * maxi);
    var elem = Math.floor(Math.random() * Spons.length);
    var tries=1;
    while (SponUse[elem] && tries++ <5) elem = Math.floor(Math.random() * Spons.length);
    SponUse[SponPos[pos]] = 0;
    SponPos[pos] = elem;
    SponUse[elem] = pos+1; 
    var pn = '#SponPos' + pos;
    document.getElementById(pn).innerHTML = Spons[elem].innerHTML;
    setTimeout(UpdateSpon,1000);  
    recent = pos;
  }

  function Resize() {
    $('#SponsorRow').empty();
    SetupSpons();
  };

  SetupSpons();
  window.onresize=Resize;
  setTimeout(UpdateSpon,1000);  
});

