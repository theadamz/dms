import {
    MsgBox,
    showProgressButton
} from "../../general";

const formElement = document.querySelector('#formResend');

function resend() {
    MsgBox.Confirm('Continue?').then(result => {
        showProgressButton(true, '#resend');
        formElement.submit();
    }).catch(err => {
        if (err) console.log(err);
    });
}

document.addEventListener("DOMContentLoaded", function () {
    document.querySelector('#resend').addEventListener('click', function (e) {
        e.preventDefault();

        // submit
        resend();
    })
})
