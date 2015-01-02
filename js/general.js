//Disables form submit button if all the fields are empty
$(document).ready(function() {
    //Disable the add user form on page load
    // $(".form-members").hide();
    $('.show-error').hide();

    //Select only the add user form text, password and submit fields 
    var $submit = $(".form-members input[type=submit]"),
        $inputs = $('.form-members input[type=text], .form-members input[type=password]');

    function checkEmpty() {

        // filter over the empty inputs
        return $inputs.filter(function() {
            return !$.trim(this.value);
        }).length === 0;
    }

    //TO IMPLEMENT -- SHOW AN ERROR BOX ON SUBMIT BUTTON HOVER IF FIELDS ARE EMPTY 
    $inputs.on('keyup blur', function() {
        if (!checkEmpty())
        {
            $submit.attr('disabled', 'disabled');
            $submit.addClass('disabled');
            $('.show-error').show(500);
        }

        else
        {
            $submit.removeAttr('disabled');
            $submit.removeClass('disabled');
            $('.show-error').hide(500);
        }

    }).keyup(); // trigger an initial blur

    //Toggles visibility of add user form
    // $("#add-user").click(function(){
    //     $(".form-members").toggle(1000);
    // });
});

//Opens or closes the pop-up box on display_users.php file
//when the edit image is clicked
function openPopup() {

    //On edit icon click
    $('.registered-users').click(function(){

        //Add class 'not-selected' to every list element
        //except the selected one
        //By this, only one list element (the selected) will be visible
        $('.registered-users').not(this).addClass( 'not-selected' );

        //If the numbering needs to be disabled, the below line does that
        // $('.registered-users').css('list-style-type','none');
        
        //Show the currently selected list element popup box
        $('.registered-users #show-popup').show().index(this);
    });

    //If the browser width is less than 700px
    //Hide the add user form
    if (window.innerWidth < 700)
    {
        document.getElementById('info-right-display').style.display = 'none';
    }
}

function closePopup() {

    //On edit icon click
    $('.registered-users').click(function(){

        //Remove class 'not-selected' from every list element
        //except the selected one
        //By this, every list element will have its initial class structure
        $('.registered-users').not(this).removeClass( 'not-selected' );

        //If the numbering needs to be enabled, the below line does that
        // $('.registered-users').css('list-style-type','decimal');
        
        // Hide the currently selected list element popup box
        $('.registered-users #show-popup').hide().index(this);
    });

    //If the browser width is less than 700px
    //Showw the add user form
    if (window.innerWidth < 700)
    {
        document.getElementById('info-right-display').style.display = 'block';
    }
}
