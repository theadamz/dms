import {
    axiosCustom,
    dateTimeFormat,
    formValidationSetErrorMessages,
    initMaxLength,
    MsgBox,
    refactorErrorMessages,
    regExpForUsername,
    showProgressButton,
} from '../../general';

const formElement = document.querySelector('#formInput');
let noPicture = true;

function initFormValidation() {
    _formValidation = $(formElement).validate({
        rules: {
            username: {
                required: true,
                regex: regExpForUsername
            },
            email: {
                required: true,
                email: true
            },
            name: {
                required: true,
            },
        },
        submitHandler: saveData
    });
}

function initLengthInput() {
    initMaxLength('#username');
    initMaxLength('#email');
    initMaxLength('#name');
}

function initOtherElements() {
    getData();

    document.getElementById('pictureRemove').addEventListener('click', function () {
        noPicture = true;
    });
}

function saveData() {
    MsgBox.Confirm('Are you sure want to save the changes?').then(result => {
        showProgressButton(true, '#save');
        _data2Send = new FormData($('#formInput')[0]);
        _data2Send.append('no_picture', noPicture);
        axiosCustom(`${_baseURL}/change-profile`, 'POST', _data2Send, null).then((response) => {
            if ([200].includes(response.status)) {
                Toast.fire({
                    icon: 'success',
                    title: response.statusText,
                    text: response.data.message
                });

                noPicture = false;

                setTimeout(() => {
                    window.location.reload();
                }, 500);
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

function getData() {
    axiosCustom(`${_baseURL}/profile`, 'GET').then((response) => {
        if ([200].includes(response.status)) {
            const data = response.data.data;
            $('#username').val(data.username);
            $('#email').val(data.email);
            $('#name').val(data.name);
            $('#timezone option[value="' + data.timezone + '"]').prop('selected', true).change();
            $('#department_name').val(data.department.name);
            $('#role_name').val(data.role.name);
            $('#last_sign_in').val(dateTimeFormat(data.last_sign_in));
            $('#last_update').val(dateTimeFormat(data.updated_at));
            $('#last_change_password').val(data.last_change_password_at ? dateTimeFormat(data.last_change_password_at) : 'Never');
            if (data.picture) {
                $('#picturePreview').attr('src', data.picture);
                noPicture = false;
            }
        } else {
            MsgBox.HtmlNotification(refactorErrorMessages(response.data), `${response.status} - ${response.statusText}`)
        }
    }).catch(err => {
        if (err) console.log(err);
    });
}

document.addEventListener("DOMContentLoaded", function () {
    initLengthInput();
    initFormValidation();
    initOtherElements();
});
