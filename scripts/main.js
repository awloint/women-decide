$(document).ready(function() {
    //DOM is fully loaded

    // Capitalize the first letter of First Name
    $('#firstName').on('change', function (e) {
        var $this = $(this),
            val = $this.val();
        regex = /\b[a-z]/g;

        val = val.charAt(0).toUpperCase() + val.substr(1);
    });

    // Capitalize the first letter of Last Name
    $('#lastName').on('change', function (e) {
        var $this = $(this),
            val = $this.val();
        regex = /\b[a-z]/g;

        val = val.charAt(0).toUpperCase() + val.substr(1);
    });

    // change the email to lowercase
    $('#email').on('change', function (e) {
        var $this = $(this),
            val = $this.val();
        val = val.toLowerCase();
    });

    //When form is submitted, grab data
    $('form').submit(function (event) {
        event.preventDefault();

        // put form data into variables
        var firstName = $.trim(document.getElementById('firstName').value);
        var lastName = $.trim(document.getElementById('lastName').value);
        var email = $.trim(document.getElementById('email').value);
        var phone = $.trim(document.getElementById('phone').value);
        var gender = $("input[name=gender]:checked").val();
        var referrer = $("input[name=referrer]:checked").val();

        var postData = 'firstName=' + firstName + '&lastName=' + lastName + '&email=' + email + '&phone=' + phone + '&gender=' + gender + '&referrer=' + referrer;


        // check to see if the user has already registered
        $.ajax({
            type: 'POST',
            url: 'post.php',
            data: postData,
            success: function(result) {
                if(result == 'user_exists') {
                    swal("User Already Exists!", "You have already registered!", "error");
                    setTimeout(function () {
                        window.location = 'https://awlo.org'
                    }, 3000);
                } else {
                    swal("Sucess!", "You have successfully registered!", "success");
                    setTimeout(function () {
                        window.location = 'https://awlo.org'
                    }, 3000);
                }
            }
        });
    });
});