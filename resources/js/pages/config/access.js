import {
    _datatableClearSelectedValues,
    axiosCustom,
    formValidationSetErrorMessages,
    initDataTablesCheckBoxes,
    initDataTableSearch,
    initDragableModal,
    initSelect2,
    isObjectEmpty,
    MsgBox,
    refactorErrorMessages,
    showBlockUIElement,
    showProgressButton
} from '../../general';

let data4DataTable = [];
let dataAccess = null;

let _formValidationDuplicate = null

_dataTableSelectColumn = 'code';

function initDataTable() {
    _dataTable = $('#list_datatable').DataTable({
        "iDisplayLength": -1,
        "bLengthChange": false,
        "dom": `<'row'
               <'col-sm-6 d-flex align-items-center justify-conten-start'l>
            >
               <'table-responsive'tr>
            <'row'
               <'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>

            >`,
        "info": true,
        "scrollX": true,
        "scrollY": _dataTableScrollY,
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
        "data": data4DataTable,
        "columns": [{
                "data": "code",
                "width": '30px',
                "orderable": false,
                "searchable": false,
                "defaultContent": '',
                "className": "details-control dt-center",
                "render": function (data, type, row, meta) {
                    return `<div class="d-flex align-items-center">
                           <div class="form-check form-check-custom form-check-sm me-4">
                              <input class="form-check-input datatable-checkable" type="checkbox" value="${data}" ${_dataTableSelectedValues.indexOf(data) > -1 ? 'checked' : ''} data-index="${meta.row}" />
                           </div>
                        </div>`;
                }
            },
            {
                "data": "name",
                "orderable": false,
            },
            {
                "data": "permissions",
                "orderable": false,
                "render": function (data, type, row, meta) {
                    let html = "";
                    for (const [key, value] of Object.entries(data)) {
                        html += value ? `<span class="badge font-weight-normal badge-success mr-1 text-bold">${key}</span>` : `<span class="badge font-weight-normal badge-danger mr-1 text-bold">${key}</span>`;
                    }

                    return html;
                }
            },
        ],
    }).on('draw.dt', function (e, settings) {
        // Datatable checkboxes
        initDataTablesCheckBoxes(_dataTable);

        // Adjust columns
        _dataTable.columns.adjust();
    });

    // DataTable search
    initDataTableSearch(_dataTable, 'input[type="search"]');

    // DataTable refresh
    document.querySelector('#refresh').addEventListener('click', () => retriveRoleAccesses());
}

function checkAccess(e) {
    const flag = $(this).data('flag');
    const allowed = $(this).is(':checked');

    dataAccess[flag] = allowed;
    // console.log('dataAccess', dataAccess);
}

function initFormValidation() {
    _formValidation = $(document.querySelector('#formInput')).validate({
        rules: {
            access_lists: {
                required: true,
            },
        },
        submitHandler: function (form, e) {
            e.preventDefault();

            if ($(form).valid()) saveData();
        }
    });

    _formValidationDuplicate = $(document.querySelector('#formDuplicate')).validate({
        rules: {
            from_role: {
                required: true,
            },
            to_role: {
                required: true,
            },
        },
        submitHandler: function (form, e) {
            e.preventDefault();

            if ($(form).valid()) duplicateData();
        }
    });
}

function initOtherElements() {
    // FormInput
    initSelect2('#access_lists');
    $('#modalFormInput').on('hidden.bs.modal', formInputClear);
    initDragableModal('#modalFormInput');

    // Form filter role
    initSelect2('#role', 'Select', null, false);
    $('#role').on('select2:select', function (e) {
        _dataTable.clear().draw();
        $('#addMoreMenu').prop('disabled', false);
        retriveRoleAccesses();
        $('input[type="search"]').val(null);
    });

    // Init select2 form_duplicate
    $('#modalFormDuplicate').on('hidden.bs.modal', formDuplicateClear);
    initDragableModal('#modalFormDuplicate');
    initSelect2('#from_role');
    initSelect2('#to_role');
    initSelect2('#exclude_accesses');

    // form edit access
    document.querySelector('#saveEditAccess').addEventListener('click', savePermissions);
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
            }).data().code);
        });
    }

    if (_permissions.delete) {
        document.querySelector('#delete').addEventListener('click', deleteData);
    }
}

async function saveData() {
    if ($('#access_lists').val().length <= 0 || $('#role').val().trim() == '') {
        MsgBox.Notification('Select role and access.');
        return;
    }

    // show progress
    showProgressButton(true, '#save');
    showBlockUIElement('#formInput');

    // prepare data
    _data2Send = new URLSearchParams({
        role: $('#role').val(),
        access_lists: JSON.stringify($('#access_lists').val()),
    });

    // send request
    const response = await axiosCustom(`${_baseURL}/configs/accesses`, 'POST', _data2Send);

    // if response status not 200, 201
    if (![201, 200].includes(response.status)) {
        MsgBox.HtmlNotification(refactorErrorMessages(response.data), `${response.status} - ${response.statusText}`)

        // show progress
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

    // show progress
    showProgressButton(false, '#save');
    showBlockUIElement('#formInput', false);

    // clear form
    formInputClear();

    // retrive data
    retriveRoleAccesses();

    // hide modal
    $('#modalFormInput').modal('hide');
}

async function editData(id = null) {
    // set id
    _id = id;

    // show progress
    showBlockUIElement('#list_datatable');

    // send request
    const response = await axiosCustom(`${_baseURL}/configs/accesses/${$('#role').val()}/${_id}`, 'GET');

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

    // set caption
    $('#editAccess').val(`${data.code} - ${data.name}`);

    // get access
    const access = _accesses.find(item => item.code == data.code);

    // sety dataAccess
    dataAccess = data.permissions;

    // clear element
    $('#editAccessContainer').html(null);

    // clear Check access
    $('.check-access').off('click');

    // loop permission
    let html = "";
    for (const [key, value] of Object.entries(access.permissions)) {
        html += `<div class="form-check mx-5">
                            <input class="form-check-input  check-access" type="checkbox" value="true"
                                    id="edit_access_${value}"
                                    name="edit_access_${value}"
                                    data-flag="${value}"
                                    ${data.permissions[value] ? "checked" : ""}/>
                            <label class="form-check-label" for="edit_access_${value}">${value}</label>
                        </div>`;
    }

    // append
    $('#editAccessContainer').html(html);

    // set event
    $('.check-access').on('click', checkAccess);

    // show modal
    $('#modalFormEdit').modal('show');

    // hide progress
    showBlockUIElement('#list_datatable', false);
}

async function savePermissions() {
    if (isObjectEmpty(dataAccess)) {
        MsgBox.Notification('Please make change before save.');
        return;
    }

    // show progress
    showProgressButton(true, '#saveEditAccess');
    showBlockUIElement('#formEdit');

    // prepare data
    _data2Send = new URLSearchParams({
        code: _id,
        permissions: JSON.stringify(dataAccess),
    });

    // send request
    const response = await axiosCustom(`${_baseURL}/configs/accesses/${$('#role').val()}`, "PUT", _data2Send);

    // if response status not 200
    if (![200].includes(response.status)) {
        // show error
        MsgBox.HtmlNotification(refactorErrorMessages(response.data), `${response.status} - ${response.statusText}`)

        // hide progress
        showProgressButton(false, '#saveEditAccess');
        showBlockUIElement('#formEdit', false);
        return;
    }

    // show message
    Toast.fire({
        icon: 'success',
        title: response.statusText,
        text: response.data.message
    });

    // hide progress
    showProgressButton(false, '#saveEditAccess');
    showBlockUIElement('#formEdit', false);

    // clear
    dataAccess = null;

    // get access
    retriveRoleAccesses();

    // hide modal
    $('#modalFormEdit').modal('hide');
}

async function duplicateData() {
    // show progress
    showProgressButton(true, '#saveDuplicate');
    showBlockUIElement('#formDuplicate');

    // prepare data
    _data2Send = new URLSearchParams({
        from_role: $('#from_role').val(),
        to_role: $('#to_role').val(),
        exclude_accesses: JSON.stringify($('#exclude_accesses').val()),
    });

    // send request
    const response = await axiosCustom(`${_baseURL}/configs/accesses/duplicate`, 'POST', _data2Send);

    // if response status not 201
    if (![201].includes(response.status)) {
        MsgBox.HtmlNotification(refactorErrorMessages(response.data), `${response.status} - ${response.statusText}`)
        formValidationSetErrorMessages(response.data.errors);

        // hide progress
        showProgressButton(false, '#saveDuplicate');
        showBlockUIElement('#formDuplicate', false);

        return;
    }

    // show message
    Toast.fire({
        icon: 'success',
        title: response.statusText,
        text: response.data.message
    });

    // set cation
    _action = 'create';

    // hide modal
    $('#modalFormDuplicate').modal('hide');

    // clear form
    formDuplicateClear();

    // get access
    retriveRoleAccesses();

    // hide progress
    showProgressButton(false, '#saveDuplicate');
    showBlockUIElement('#formDuplicate', false);
}

async function deleteData() {
    // Check selected values
    if (_dataTableSelectedValues.length <= 0) {
        MsgBox.HtmlNotification("Select 1 atau more.");
        return;
    }

    // show progress
    showProgressButton(true, '#delete');
    showBlockUIElement('#list_datatable');

    // prepare data
    _data2Send = new URLSearchParams({
        role: $('#role').val(),
        id: JSON.stringify(_dataTableSelectedValues)
    });

    // send request
    const response = await axiosCustom(`${_baseURL}/configs/accesses`, "DELETE", _data2Send);

    // if response status not 200
    if (![200].includes(response.status)) {
        MsgBox.HtmlNotification(refactorErrorMessages(response.data), `${response.status} - ${response.statusText}`)

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

    // clear checks
    _datatableClearSelectedValues();

    // get access
    retriveRoleAccesses();

    // hide progress
    showProgressButton(false, '#delete');
    showBlockUIElement('#list_datatable', false);
}

function formInputClear() {
    _action = 'create';
    _data2Send = null;
    $('#access_lists').val(null).trigger('change');
    _formValidation.resetForm();
    $('#formInput')[0].reset();
}

function formDuplicateClear() {
    _data2Send = null;
    $('#from_role').val(null).trigger('change');
    $('#to_role').val(null).trigger('change');
    $('#exclude_accesses').val(null).trigger('change');
    _formValidationDuplicate.resetForm();
    $('#formDuplicate')[0].reset();
}

async function retriveRoleAccesses() {
    if (!$('#role').val()) return;

    showBlockUIElement('#list_datatable');

    // ambil data
    const response = await axiosCustom(`${_baseURL}/configs/accesses/${$('#role').val()}`, 'GET', null);

    // jika status bukan OK
    if (![200].includes(response.status)) {
        _dataTable.clear().draw();
        // MsgBox.HtmlNotification(refactorErrorMessages(response.data), `${response.status} - ${response.statusText}`);
        showBlockUIElement('#list_datatable', false);
        return;
    }

    data4DataTable = response.data.data;
    _dataTable.clear();
    _dataTable.rows.add(response.data.data).search($('input[type="search"]').val()).draw();
    _datatableClearSelectedValues();
    showBlockUIElement('#list_datatable', false);
}

document.addEventListener("DOMContentLoaded", function () {
    initDataTable();
    initFormValidation();
    initOtherElements();
    initActions();
});
