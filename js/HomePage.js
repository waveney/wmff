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
    if (!Spons) return 0;
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
    return 1;
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

  if (SetupSpons()) {
    window.addEventListener('resize',Resize);
    setTimeout(UpdateSpon,1000);  
  }
});


// Article Display
$(document).ready(function() {
  debugger;
  
  var MinWidth = 450;
  var PadWidth = 10;
  
  var OrigArt = $('#OrigArt');
  if (!OrigArt) return; 
  
  function SetupArts() {
    // Works out columns
    var Show = $('#ShowArt');
    var WorkWidth = Show.width();
    var ColCount = 1;
    var Cols =[];
    
    if (WorkWidth > (2*MinWidth+PadWidth)) ColCount = Math.floor(WorkWidth/(MinWidth+PadWidth));
    var ActColWidth = Math.floor((WorkWidth - (ColCount)*PadWidth)/ColCount);

    // Create columns
    Show.append('<div class=ArtBanner id=ArtBanners></div><div id=ArtCols></div>');
    for(var i=1; i<=ColCount; i++) {
      $('#ArtCols').append('<div id=ArtCol' + i +' class=ArtColClass></div>');
      Cols[i] = $("#ArtCol" +i);
      };
    $('.ArtColClass').width(ActColWidth);

    // go through each Art, create in shortest col - fudge image stats
    var ArtNum = 0;
    var Art;
    while ((Art = document.getElementById('Art' + ArtNum))) {
      var Col = Cols[1];
      for (i=2; i<=ColCount; i++) if (Cols[i].height() < Col.height()) Col = Cols[i];
      var clone = Art.outerHTML;
      clone = clone.replace(/id="Art/g,'id="SArt');
//      if (!clone) continue;
      switch ($(Art).data('format')) {
      case 0: // Large - image display width is actcolwidth, image display height = acthieght*actcolwith/origheight
        var cloneimg = $('#ArtImg' + ArtNum)
        var imgwd = cloneimg.data('width');
        var imght = cloneimg.data('height');
        var newwidth = ActColWidth;
        var newheight = Math.floor(imght*newwidth/imgwd);
        clone = clone.replace(/class="ArtImageL"/,'class="ArtImageL" width=' + newwidth + ' height=' + newheight);
        break;
        
      case 1: // Small- image display width is actcolwidth*.45, image display height = acthieght*actcolwith*.45/(origheight) - img =45%, pad =5% txt =50%
        var cloneimg = $('#ArtImg' + ArtNum)
        var imgwd = cloneimg.data('width');
        var imght = cloneimg.data('height');
        var newwidth = Math.floor(ActColWidth*.45);
        var newheight = Math.floor(imght*newwidth/imgwd);
        clone = clone.replace(/class="ArtImageS"/,'class="ArtImageS" width=' + newwidth + ' height=' + newheight);
        break;
        
      case 2: // text - no actions needed
        break;
        
      case 3: // Banner Image - no image manipulation needed
        Col = $('#ArtBanners');
        break;
              
      case 4: // Banner Text
        Col = $('#ArtBanners');
        break;
        
      }

      Col.append(clone,'<br clear=all>'); // Needs to fix image data
//      Col.append('<br clear=all>'); // Needs to fix image data
      ArtNum++
    }
  }
  
  function ArtResize() {
    $('#ShowArt').empty();
    SetupArts();    
  }

  SetupArts();
  window.addEventListener('resize',ArtResize);
});

// 
