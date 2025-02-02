import {
    showProgressButton
} from '../../general';

const formElement = document.querySelector('#formSignIn');

function initFormValidation() {
    $(formElement).validate({
        rules: {
            email: {
                required: true,
            },
            password: {
                required: true,
                minlength: 5
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            error.insertAfter(element);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: submitHandler
    });
}

function initOtherElements() {
    document.getElementById('showPassword').addEventListener('click', (e) => handleShowPassword('showPassword'));
}

function handleShowPassword(elName) {
    const element = document.getElementById(elName);
    const elTarget = document.getElementById(element.getAttribute("for"));
    if (elTarget.type === 'password') {
        elTarget.type = 'text';
        element.querySelector(".fa-eye-slash").classList.remove("d-none");
        element.querySelector(".fa-eye").classList.add("d-none");
    } else {
        elTarget.type = 'password';
        element.querySelector(".fa-eye").classList.remove("d-none");
        element.querySelector(".fa-eye-slash").classList.add("d-none");
    }
}

function submitHandler() {
    showProgressButton(true, '#signIn');
    formElement.submit();
}

document.addEventListener("DOMContentLoaded", function () {
    initFormValidation();
    initOtherElements();
})
