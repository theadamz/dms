"use strict";

const formElement = document.querySelector('#formInput');

function initFormValidation() {
	_formValidation = $(formElement).validate({
		rules: {
			username: {
				required: true,
				regex: _regExpForUsername
			},
			email: {
				required: true,
				email: true,
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
	initSelect2('#timezone');
}

function saveData() {
	MsgBox.Confirm('Are you sure want to update your profile?', 'Confirm').then(result => {
		showProgressButton(true, '#save');
		_data2Send = $('#formInput').serialize();
		axiosCustom(`${_baseURL}${_module}/${_controller}/update`, 'POST', _data2Send).then((response) => {
			if ([200].includes(response.status)) {
				MsgBox.HtmlNotification(response.data.message.toString(), `${response.statusText}`, 'success');
				getData();
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
	showBlockUIElement('#formInput');
	axiosCustom(`${_baseURL}profile/me`, 'GET', null).then((response) => {
			if ([200].includes(response.status)) {
				const data = response.data.data;
				$('#last_update').val(moment(data.updated_at).format(_jsDateTimeFormat));
				$('#username').val(data.username);
				$('#email').val(data.email);
				$('#name').val(data.name);
				$('#role').val(`${data.role_code} - ${data.role_name}`);
				$('#timezone option[value="' + data.timezone + '"]').prop('selected', true).change();
				$('#bu_name').val(`${data.bu_code} - ${data.bu_name}`);
				$('#last_login').val(data.last_login ? moment(data.last_login).format(_jsDateTimeFormat) : "");
			} else {
				MsgBox.HtmlNotification(response.message);
			}
			showBlockUIElement('#formInput', false);
		})
		.catch((err) => {
			MsgBox.Notification(err.toString());
			showBlockUIElement('#formInput', false);
		});
}

document.addEventListener("DOMContentLoaded", function () {
	initLengthInput();
	initFormValidation();
	initOtherElements();
	getData();
});
