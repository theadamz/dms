import {
    MsgBox,
    showProgressButton
} from "../../general";

const formElement = document.querySelector('#formResetPassword');

function initFormValidation() {
    $(formElement).validate({
        rules: {
            password: {
                required: true,
                minlength: 8
            },
            password_confirmation: {
                required: true,
                minlength: 8,
                // equalTo: "#password"
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: saveData
    });
}

function saveData() {
    MsgBox.Confirm('Continue?').then(result => {
        showProgressButton(true, '#save');
        formElement.submit();
    }).catch(err => {
        if (err) console.log(err);
    });
}

document.addEventListener("DOMContentLoaded", function () {
    initFormValidation();
})
