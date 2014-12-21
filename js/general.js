//Disables form submit button if all the fields are empty
$(document).ready(function() {
    $(".form-members").hide();
    var $submit = $("input[type=submit]"),
        $inputs = $('input[type=text], input[type=password]');

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
    $("#add-user").click(function(){
        $(".form-members").toggle(1000);
    });
});