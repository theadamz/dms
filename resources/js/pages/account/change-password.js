import {
    axiosCustom,
    formValidationSetErrorMessages,
    initMaxLength,
    MsgBox,
    refactorErrorMessages,
    showProgressButton,
} from '../../general';

const formElement = document.querySelector('#formInput');

function initFormValidation() {
    _formValidation = $(formElement).validate({
        rules: {
            password_old: {
                required: true,
            },
            password: {
                required: true,
                minlength: 8,
            },
            password_confirmation: {
                required: true,
                minlength: 8,
                equalTo: "#password"
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.fv-row').append(error);
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

function initLengthInput() {
    initMaxLength('#password_old');
    initMaxLength('#password');
    initMaxLength('#password_confirmation');
}

function initOtherElements() {
    document.getElementById('showPasswordOld').addEventListener('click', (e) => handleShowPassword('showPasswordOld'));
    document.getElementById('showPasswordNew').addEventListener('click', (e) => handleShowPassword('showPasswordNew'));
    document.getElementById('showPasswordNewConfirm').addEventListener('click', (e) => handleShowPassword('showPasswordNewConfirm'));
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

function saveData() {
    MsgBox.Confirm('Are you sure want to change?').then(result => {
        showProgressButton(true, '#save');
        _data2Send = $('#formInput').serialize();
        axiosCustom(`${_baseURL}/change-password`, 'POST', _data2Send).then((response) => {
            if ([200].includes(response.status)) {
                Toast.fire({
                    icon: 'success',
                    title: response.statusText,
                    text: response.data.message
                });
                $('#password_old').val(null);
                $('#password').val(null);
                $('#password_confirmation').val(null);
            } else {
                MsgBox.HtmlNotification(refactorErrorMessages(response.data), `${response.status} - ${response.statusText}`);
                formValidationSetErrorMessages(response.data.errors);
            }
            showProgressButton(false, '#save');
        }).catch((err) => {
            MsgBox.Notification(err.toString());
            showProgressButton(false, '#save');
        });
    }).catch(err => {
        if (err) console.log(err);
    });
}

document.addEventListener("DOMContentLoaded", function () {
    initLengthInput();
    initFormValidation();
    initOtherElements();
});
