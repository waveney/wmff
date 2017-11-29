function urldecode(url) {
  return decodeURIComponent(url.replace(/\+/g, ' '));
}

function newemailclk(id,type,xtra) {
  $.getJSON("emaillink.php",{id: id, t: type, x:xtra}).done(function(data) {
    debugger;
//    document.getElementById('hidden').innerhtml=data.paste
//    clipboard.copy(document.getElementById('hidden'));
    var txt = data.paste;
    clipboard.copy(txt);
    var lnk=data.link;
    window.open(lnk,'_blank');
  })
}

function emailclk(lnk,cpid) {
  var e = document.getElementById(cpid);
  var stuff = e.innerHTML;
  e.innerHTML = urldecode(stuff);
  clipboard.copy(e);
  e.innerHTML = stuff;
  window.open(lnk,'_blank');
}

function CopyDiv(cpid) {
  var e = document.getElementById(cpid);
  var stuff = e.innerHTML;
  e.innerHTML = urldecode(stuff);
  clipboard.copy(e);
  e.innerHTML = stuff;
}

function Copy2Div(outerdiv,innerdiv) {
  var e = document.getElementById(outerdiv);
  var stuff = e.innerHTML;
  e.innerHTML = urldecode(stuff);
  clipboard.copy(document.getElementById(innerdiv));
  e.innerHTML = stuff;
}
