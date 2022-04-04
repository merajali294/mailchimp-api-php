$(function () {
    // $('#signup-form').validator();
    // When the form is submitted
    $( '#signup-form' ).on('submit', function(){

    } )
        $('#signup-form').on('submit', function (e) {
                e.preventDefault()
                var url = "signup.php";

                var formData = {
                    MERGE0: $("#MERGE0").val(),
                    MERGE1: $("#MERGE1").val(),
                    MERGE2: $("#MERGE2").val(),
                  };

                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    success: function (data)
                    {
    // Get type of the message: success x danger and apply it to the 
                        var messageAlert = 'alert-' + data.type;
                        var messageText = data.message;
    // Let's compose Bootstrap alert box HTML
                        var alertBox = '<div class="alert ' + messageAlert + ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + messageText + '</div>';
                        
                        // If we have messageAlert and messageText
                        if (messageAlert && messageText) {
                            //then inject the alert to .messages div in our form
                            $('#signup-form').find('.messages').html(alertBox);
                            // Empty the form
                            $('#signup-form')[0].reset();
                        }
                    }
                });
                return false;
        })
    });