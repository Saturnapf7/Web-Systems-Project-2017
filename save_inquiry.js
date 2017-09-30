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
       //Turn the result into an object
        result = JSON.parse(result);
        //Get the number of values in the object
        var resultLength = Object.values(result).length;
        
        //If the array isn't empty, iterate through the JSON object and place errors next to the appropriate field
        if (resultLength <= 4) {
          $.each(result, function(k,v) {
            var errorMsg = "<label for='" + k + "'class='form-error'>" + v + "</label>";
            $("input[name='" + k + "']").addClass("input-error").after(errorMsg);
          });
        }
        else
        {
          //Display success message
          submit.before(result);
        }
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
