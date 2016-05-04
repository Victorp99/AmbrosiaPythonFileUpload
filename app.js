//wait until the page has been loaded before doing anything
$(document).ready(function(){
//when button is clicked,
$("form#uploadForm").submit(function(){
  /*
  $("#uploadPython").button('loading').delay(1000).queue(function() {
     // $(this).button('reset');
  });
  */
    var formData = new FormData($(this)[0]);

    $.ajax({
        url: "upload.php",//window.location.pathname,
        method: 'POST',
        data: formData,
        async: false,
        success: function (data) {
            $("#pythonOutput").html(data); //replaceWith doesn't allow new images to be outputted
        },
        cache: false,
        contentType: false,
        processData: false
    });

    return false;
});

});
