//Uploading and Image stuff

var CC;
$(document).ready(function() {
  if (!$('#aspectRatio')) return;
  var asprat = $('#aspectRatio').val();
  CC = ($('#image').cropper({ 
        aspectRatio: asprat,
        viewMode:1,
        autoCropArea:1,
  }));

  document.getElementById('crop_button').addEventListener('click', function(){

    var DD = $('#image').cropper('getCroppedCanvas');

    DD.toBlob(function (blob) {
      var form = document.getElementById('cropform');
      var formData = new FormData(form);

      var fred = formData.append('croppedImage', blob,'croppedImage');

      $.ajax('/int/PhotoManage', {
          method: "POST",
          data: formData,
          processData: false,
          contentType: false, 
          success: function (resp) { 
            //console.log(resp); 
            //document.getElementById('Feedback').innerHTML = resp; 
            var src = $('#image').attr('src');
            src += '?' + Date.now();
            $('#croptool').hide();
            $('#cropresult').html('<img src=' + src + '><br><h2>Image cropped and saved</h2>');
            var finalloc = $('#FinalLoc').html();
            $('#NewImage').html(finalloc);
            },
          error: function (resp) { console.log(resp); document.getElementById('Feedback').innerHTML = resp; },
          });
        });
    });
})

$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});
