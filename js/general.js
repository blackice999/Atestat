//Disables form submit button if all the fields are empty
$(document).ready(function() {
    //Disable the add user form on page load
    // $(".form-members").hide();

    //Select only the add user form text, password and submit fields 
    var $submit = $(".form-members input[type=submit]"),
        $inputs = $('.form-members input[type=text], .form-members input[type=password]');

    function checkEmpty() {

        // filter over the empty inputs
        return $inputs.filter(function() {
            return !$.trim(this.value);
        }).length === 0;
    }

    $inputs.on('keyup blur', function() {
        $submit.prop("disabled", !checkEmpty());
    }).keyup(); // trigger an initial blur

    //Toggles visibility of add user form
    // $("#add-user").click(function(){
    //     $(".form-members").toggle(1000);
    // });

    // $('#test').click(function() {

    //     var text = $('.edit-note').val();
    //     var number = $('.edit-note').index();
    //     console.log(number);
    //           jQuery.ajax({
    //            type: "POST",
    //            data: 'id='+id,     // <-- put on top
    //            url: "view_notifications.php",
    //            cache: false,
    //            success: function(response)
    //            {
    //              alert("Record successfully updated");
    //            }
    //          });
    // });

});

function showNote()
{
    //On edit note icon click
    $('.notes-list').click(function(){
        //Remove the span containing the text
        $(this).children('.edit-note').addClass('not-selected');

        //Show a form with a submit button
        //and a textarea box where the user can insert new text
        $(this).children('.form-update-note').show();
    });
}
//TO FIX -- REMOVE INPUT FIELD ON EDIT NOTE,
//SET BACK THE SPAN CLASS TO CHANGED
// $(document).mouseup(function (e)
// {
//     // var $test2;
//     // $something = $('.edit-note').click(function(){
//     function something()
//     {
//         $('.edit-note').click(function(){

//             $test2 = $(this).text();
//         });
//     }
//     // });
//         $test = $('.edit-note').text();
//         var container = $(".edit-note-input");

//         if (!container.is(e.target) // if the target of the click isn't the container...
//             && container.has(e.target).length === 0) // ... nor a descendant of the container
//         {
//             container.replaceWith(something());
//         }
// });

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

function showNotifications()
{
    //On email address click
    $('.registered-users').click(function()
    {
       //Add class 'not-selected' from every list element
       //except the selected one
       //By this, only one list element (the selected) will be visible
       $('.registered-users').not(this).addClass('not-selected');

        //If the numbering needs to be disabled, the below line does that
        // $('.registered-users').css('list-style-type','none');

       //Show the currently selected list element popup box
       $('.registered-users #show-notifications').show().index(this);
    });

    //If the browser width is less than 700px
    //Hide the add user form
    if (window.innerWidth < 700)
    {
        document.getElementById('info-right-display').style.display = 'none';
    }
}

function closeNotifications ()
{
    //On email address click
    $('.registered-users').click(function(){

        //Remove class 'not-selected' from every list element
        //except the selected one
        //By this, every list element will have its initial class structure
        $('.registered-users').not(this).removeClass('not-selected');

        //If the numbering needs to be enabled, the below line does that
        // $('.registered-users').css('list-style-type','decimal');

        // Hide the currently selected list element popup box
        $('.registered-users #show-notifications').hide().index(this);
    });

     //If the browser width is less than 700px
    //Showw the add user form
    if (window.innerWidth < 700)
    {
        document.getElementById('info-right-display').style.display = 'block';
    }
}