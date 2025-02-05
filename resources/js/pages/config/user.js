import {
    _datatableClearSelectedValues,
    axiosCustom,
    boolValue,
    formValidationSetErrorMessages,
    initDataTablesCheckBoxes,
    initDataTableSearch,
    initDragableModal,
    initMaxLength,
    MsgBox,
    refactorErrorMessages,
    regExpForUsername,
    showBlockUIElement,
    showProgressButton
} from '../../general';

let url = null;
let noPicture = true;

function initDataTable() {
    _dataTable = $('#list_datatable').DataTable({
        "lengthMenu": [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        "pageLength": 50,
        "numbers_length": 4,
        "searchDelay": 500,
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "stateSave": _dataTableUseState,
        "stateDuration": _dataTableStateDuration,
        "scrollY": _dataTableScrollY,
        "order": [
            [0, "asc"]
        ],
        "select": {
            "items": "row",
            "style": "single",
            "className": "bg-warning"
        },
        "headerCallback": function (thead, data, start, end, display) {
            thead.getElementsByTagName('th')[0].innerHTML = `
            <div class="form-check">
               <input class="form-check-input datatable-group-checkable" type="checkbox" value="" id="groupCheckable" />
					<label class="form-check-label"></label>
            </div>`;
        },
        "createdRow": function (row, data, dataIndex) {
            if (_dataTableSelectedValues.indexOf(data[_dataTableSelectColumn]) > -1) {
                $(row).addClass("bg-light");
            }
        },
        "ajax": {
            "url": `${_baseURL}/dt/configs/users`,
            "type": "GET",
            "data": function (d) {
                d.department = $('#filter_department').val();
                d.role = $('#filter_role').val();
                d.is_active = $('#filter_is_active').val();
            },
        },
        "columns": [{
                "data": "id",
                "width": '30px',
                "orderable": false,
                "searchable": false,
                "render": function (data, type, row, meta) {
                    return `<div class="form-check">
                               <input class="form-check-input datatable-checkable" type="checkbox" value="${data}" ${_dataTableSelectedValues.indexOf(data) > -1 ? 'checked' : ''} data-index="${meta.row}" />
                                        <label class="form-check-label"></label>
                            </div>`;
                },
            },
            {
                "data": "username",
                "name": "u.username"
            },
            {
                "data": "email",
                "name": "u.email"
            },
            {
                "data": "name",
                "name": "u.name"
            },
            {
                "data": "department_name",
                "name": "d.name"
            },
            {
                "data": "role_name",
                "name": "r.name"
            },
            {
                "data": "timezone",
                "name": "u.name"
            },
            {
                "data": "is_active",
                "className": "dt-center",
                "searchable": false,
                "render": function (data, type, row, meta) {
                    if (boolValue(data)) {
                        return '<span class="badge font-weight-normal badge-success">Yes</span>';
                    } else {
                        return '<span class="badge font-weight-normal badge-warning">No</span>';
                    }
                }
            },
        ],
    }).on('xhr.dt', function (e, settings, json, xhr) {
        setTimeout(() => {
            _dataTable.columns.adjust();
        }, 300);
    }).on('draw.dt', function (e, settings, json, xhr) {
        // Datatable checkboxes
        initDataTablesCheckBoxes(_dataTable);
    });

    // DataTable search
    initDataTableSearch(_dataTable, 'input[type="search"]');

    // DataTable refresh
    document.querySelector('#refresh').addEventListener('click', () => reloadDataTable(false));

    // DataTable filter
    document.querySelector('#filterReset').addEventListener('click', formFilterReset);
    document.querySelector('#formFilter').addEventListener('submit', (e) => {
        e.preventDefault();
        reloadDataTable();
        $('#modalFormFilter').modal('hide');
    });
}

function initFormValidation() {
    _formValidation = $(document.querySelector('#formInput')).validate({
        rules: {
            username: {
                required: true,
                regex: regExpForUsername
            },
            email: {
                required: true,
                email: true
            },
            password: {
                minlength: 8
            },
            name: {
                required: true,
            },
            department: {
                required: true,
            },
            role: {
                required: true,
            },
            timezone: {
                required: true,
            },
        },
        submitHandler: function (form, e) {
            e.preventDefault();

            if ($(form).valid()) saveData();
        }
    });
}

function initAdditionalFormValidation() {
    if (_action === 'create') {
        $("#password").rules("add", {
            required: true
        });
    } else {
        $("#password").rules("remove", 'required');
    }
}

function initOtherElements() {
    // FormInput
    $('#modalFormInput').on('hidden.bs.modal', formInputClear);
    $('#modalFormInput').on('show.bs.modal', initAdditionalFormValidation);
    initDragableModal('#modalFormInput');
    document.getElementById('pictureRemove').addEventListener('click', function () {
        if (_action === 'edit') {
            noPicture = true;
        }
    });

    document.getElementById('showPassword').addEventListener('click', (e) => handleShowPassword('showPassword'));
}

function initActions() {
    if (_permissions.edit) {
        document.querySelector('#edit').addEventListener('click', () => {
            const data = _dataTable.row({
                selected: true
            }).data();
            if (typeof data === "undefined") return;
            editData(_dataTable.row({
                selected: true
            }).data().id.trim());
        });
    }

    if (_permissions.delete) {
        document.querySelector('#delete').addEventListener('click', deleteData);
    }
}

function initMaxLengthForm() {
    initMaxLength('#username');
    initMaxLength('#password');
    initMaxLength('#email');
    initMaxLength('#name');
}

async function saveData() {
    // confirmation
    const confirmation = await MsgBox.Confirm('Are you sure?').catch(err => {
        if (err) console.log(err)
    });
    if (!confirmation) return;

    // show progress
    showProgressButton(true, '#save');
    showBlockUIElement('#formInput');

    // prepare url
    url = `${_baseURL}/configs/users`;

    // if _action is edit then add suffix
    if (_action === 'edit') url += `/${_id}`;

    // prepare data
    _data2Send = new FormData($('#formInput')[0]);
    _data2Send.append('is_active', $('#is_active').is(':checked'));
    // append method if edit
    if (_action === 'edit') {
        _data2Send.append('_method', 'PUT');
        _data2Send.append('no_picture', noPicture);
    }

    // send request
    const response = await axiosCustom(url, "POST", _data2Send, null);

    // if response status not 201 or 200
    if (![201, 200].includes(response.status)) {
        // show error
        MsgBox.HtmlNotification(refactorErrorMessages(response.data), `${response.status} - ${response.statusText}`)
        formValidationSetErrorMessages(response.data.errors);

        // hide progress
        showProgressButton(false, '#save');
        showBlockUIElement('#formInput', false);
        return;
    }

    // show message
    Toast.fire({
        icon: 'success',
        title: response.statusText,
        text: response.data.message
    });

    // reset form
    formInputClear();
    reloadDataTable(false);
    $('#modalFormInput').modal('hide');

    // hide progress
    showProgressButton(false, '#save');
    showBlockUIElement('#formInput', false);
}

async function editData(id = null) {
    // set id
    _id = id;

    // show progress
    showBlockUIElement('#list_datatable');

    // send request
    const response = await axiosCustom(`${_baseURL}/configs/users/${_id}`, 'GET');

    // if response status not 200
    if (![200].includes(response.status)) {
        // show error
        MsgBox.HtmlNotification(refactorErrorMessages(response.data), `${response.status} - ${response.statusText}`)

        // hide progress
        showBlockUIElement('#list_datatable', false);
        return;
    }

    // clear form
    formInputClear();

    // set action
    _action = 'edit';

    // get data from response
    const data = response.data.data;

    // fill input
    $('#username').val(data.username);
    $('#email').val(data.email);
    $('#name').val(data.name);
    if (data.picture) {
        $('#picturePreview').attr('src', data.picture);
        noPicture = false;
    }
    $('#department option[value="' + data.department_id + '"]').prop('selected', true).change();
    $('#role option[value="' + data.role_id + '"]').prop('selected', true).change();
    $('#timezone option[value="' + data.timezone + '"]').prop('selected', true).change();
    $('#is_active').prop('checked', boolValue(data.is_active))
    initAdditionalFormValidation();

    // show modal
    $('#modalFormInput').modal('show');

    // hide progress
    showBlockUIElement('#list_datatable', false);
}

async function deleteData() {
    // Check selected values
    if (_dataTableSelectedValues.length <= 0) {
        MsgBox.HtmlNotification("Select 1 atau more data.");
        return;
    }

    // confirmation
    const confirmation = await MsgBox.Confirm(`Are you sure want to delete ${_dataTableSelectedValues.length} data?`).catch(err => {
        if (err) console.log(err)
    });
    if (!confirmation) return;

    // show progress
    showProgressButton(true, '#delete');
    showBlockUIElement('#list_datatable');

    // prepare data
    _data2Send = new URLSearchParams({
        id: JSON.stringify(_dataTableSelectedValues)
    });

    // send request
    const response = await axiosCustom(`${_baseURL}/configs/users`, "DELETE", _data2Send);

    // if response status not 200
    if (![200].includes(response.status)) {
        // show error
        MsgBox.HtmlNotification(refactorErrorMessages(response.data), `${response.status} - ${response.statusText}`)
        formValidationSetErrorMessages(response.data.errors);

        // hide progress
        showProgressButton(false, '#delete');
        showBlockUIElement('#list_datatable', false);
        return;
    }

    // show message
    Toast.fire({
        icon: 'success',
        title: response.statusText,
        text: response.data.message
    });

    // clear selected checkbox and reload datatable
    _datatableClearSelectedValues();
    reloadDataTable(false);

    // hide progress
    showProgressButton(false, '#delete');
    showBlockUIElement('#list_datatable', false);
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

function reloadDataTable(resetPaging = true) {
    _dataTableResetFilter = false;
    _dataTable.ajax.reload(null, resetPaging);
}

function formInputClear() {
    _action = 'create';
    _data2Send = null;
    _formValidation.resetForm();
    $('#formInput')[0].reset();
    $('#department option[value=""]').prop('selected', true).change();
    $('#role option[value=""]').prop('selected', true).change();
    $('#timezone option[value=""]').prop('selected', true).change();
    $('#is_active').prop('checked', true);
    // clear picture
    $('#picturePreview').attr('src', '');
    $('#picture').val('');
    noPicture = true;
}

function formFilterReset() {
    $('#filter_is_active option[value=""]').prop('selected', true).change();
    $('#filter_role option[value=""]').prop('selected', true).change();
    $('#formFilter')[0].reset();
}

document.addEventListener("DOMContentLoaded", function () {
    initDataTable();
    initFormValidation();
    initOtherElements();
    initMaxLengthForm();
    initActions();
});
