$(function() { 
  var submit = $("#submit");
  var contactForm = $("#contact-form");

  submit.on("click", function(e) {
    resetErrors();
    //Prevent the form from submitting
    e.preventDefault();
    //Make a call to ajax to error check the form and submit if no errors
    $.ajax({
      type: "POST",
      url: "PHP/save_inquiry.php",
      data: contactForm.serialize(),
      success: function(result) {
      console.log(result);
        
        //Iterate through the JSON result and place errors next to the appropriate field
       /* $.each(result, function(k,v) {
          var errorMsg = "<label for='" + k + "'class='form-error'>" + v + "</label>";
          $("input[name='" + k + "']").addClass("input-error").after(errorMsg);
        });*/
      }
    });
  });
});

/**
 *
 *resetErrors()
 *
 *Removes the error related css and dynamic elements
 *
 *
 */
function resetErrors()
{
  $(".form-error").remove();
  $('input').removeClass("input-error");
}