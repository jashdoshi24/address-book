$(function() {
    $('.datepicker').datepicker({
        minDate: new Date(1900, 1, 1),
        maxDate: new Date(),
        yearRange: 25
    });

    $(".file-field").change(function(){
        var file_input = $(this).children().children('input[type=file]');
        console.log(file_input[0].files[0]);
        if(file_input && file_input[0].files[0]){
            var reader = new FileReader();
            reader.readAsDataURL(file_input[0].files[0]);
            reader.onload = imageIsLoaded;
        }
    });

    function imageIsLoaded(e){
        $("#temp_pic").attr('src',e.target.result);
    }

    $("#edit-contact-form").validate({
        rules: {
            first_name: {
                required: true,
                minlength: 2
            },
            last_name: {
                required: true,
                minlength: 2
            },
            telephone: {
                required: true,
                minlength: 10,
                maxlength: 10
            },
            birthdate: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            address: {
                required: true,
                minlength: 5
            },
            pic: {
                required: true
            }
        },
        errorElement: 'div',
        errorPlacement: function(error, element) {
            var placement = $(element).data('error');
            if (placement) {
                $(placement).append(error)
            } else {
                error.insertAfter(element);
            }
        }
    });
});