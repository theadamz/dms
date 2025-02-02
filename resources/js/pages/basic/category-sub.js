import {
    _datatableClearSelectedValues,
    axiosCustom,
    boolValue,
    convertApplicationXWwwFormUrlencodedToJson,
    downloadFile,
    formValidationSetErrorMessages,
    initDataTablesCheckBoxes,
    initDataTableSearch,
    initDragableModal,
    initMaxLength,
    loadingProcess,
    MsgBox,
    refactorErrorMessages,
    showBlockUIElement,
    showProgressButton
} from '../../general';

let url = null;
let _formValidationImport = null;

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
            "url": `${_baseURL}/dt/basics/category-subs`,
            "type": "GET",
            "data": function (d) {
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
                "data": "category_name",
                "name": "c.name"
            },
            {
                "data": "name",
                "name": "cs.name"
            },
            {
                "data": "is_active",
                "name": "cs.is_active",
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
            category: {
                required: true,
            },
            name: {
                required: true,
            },
        },
        submitHandler: function (form, e) {
            e.preventDefault();

            if ($(form).valid()) saveData();
        }
    });

    _formValidationImport = $(document.querySelector('#formImport')).validate({
        rules: {
            file: {
                required: true,
                accept: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                extension: "xlsx",
                filesize: 2048,
            },
        },
        submitHandler: importData
    });
}

function initOtherElements() {
    // FormInput
    $('#modalFormInput').on('hidden.bs.modal', formInputClear);
    initDragableModal('#modalFormInput');
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

    if (_permissions.export) {
        document.querySelector('#export').addEventListener('click', exportData);
    }
}

function initMaxLengthForm() {
    initMaxLength('#name');
}

async function saveData() {
    // show progress
    showProgressButton(true, '#save');
    showBlockUIElement('#formInput');

    // prepare url
    url = `${_baseURL}/basics/category-subs`;

    // if _action is edit then add suffix
    if (_action === 'edit') url += `/${_id}`;

    // decide method
    const method = _action === 'create' ? 'POST' : 'PUT';

    // prepare data
    _data2Send = convertApplicationXWwwFormUrlencodedToJson($('#formInput').serialize(), false);
    _data2Send['is_active'] = $('#is_active').is(':checked');

    // send request
    const response = await axiosCustom(url, method, _data2Send, "application/json");

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
    const response = await axiosCustom(`${_baseURL}/basics/category-subs/${_id}`, 'GET');

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
    $('#category option[value="' + data.category_id + '"]').prop('selected', true).change();
    $('#name').val(data.name);
    $('#is_active').prop('checked', boolValue(data.is_active))

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
    const response = await axiosCustom(`${_baseURL}/basics/category-subs`, "DELETE", _data2Send);

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

async function importData() {
    // confirmation
    const confirmation = await MsgBox.Confirm('Are you sure?').catch(err => {
        if (err) console.log(err)
    });
    if (!confirmation) return;

    // clear
    $('#logs').val(null);

    // show progress
    showProgressButton(true, '#upload');
    loadingProcess(true);

    // prepare data
    _data2Send = new FormData($('#formImport')[0]);

    // send request
    const response = await axiosCustom(`${_baseURL}/basics/category-subs/imports`, "POST", _data2Send, null);

    // if status not created
    if (![201].includes(response.status)) {
        // hide progress
        showProgressButton(false, '#upload');
        loadingProcess(false);

        // show message
        MsgBox.HtmlNotification(refactorErrorMessages(response.data), `${response.status} - ${response.statusText}`)

        // set error in form
        formValidationSetErrorMessages(response.data.errors, _formValidationImport);

        // set logs
        $('#logs').val(response.data.logs);
        return;
    }

    // hide progress
    showProgressButton(false, '#upload');
    loadingProcess(false);

    // show notif
    Toast.fire({
        icon: 'success',
        title: response.statusText,
        text: response.data.message
    });

    // set logs
    $('#logs').val(response.data.logs);

    // clear input
    formImportClear();

    // refresh table
    reloadDataTable(false);

    // hide input
    $('#modalFormInput').modal('hide');
}

async function exportData() {
    const confirmation = await MsgBox.Confirm('Are you sure?').catch(err => {
        if (err) console.log(err)
    });
    if (!confirmation) return;
    loadingProcess(true, 'Please wait', 'Processing data...');

    // get data
    const response = await axiosCustom(`${_baseURL}/basics/category-subs/exports`, 'POST', {
        is_active: $('#filter_is_active').val(),
    }, 'application/json');

    // if status not OK
    if (![200].includes(response.status)) {
        MsgBox.HtmlNotification(refactorErrorMessages(response.data), `${response.status} - ${response.statusText}`);
        return;
    }

    // download data
    downloadFile(response.data.url);

    loadingProcess(false);
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
    $('#category').prop('selectedIndex', 0).trigger('change');
    $('#is_active').prop('checked', true);
}

function formFilterReset() {
    $('#filter_is_active option[value=""]').prop('selected', true).change();
    $('#formFilter')[0].reset();
}

function formImportClear() {
    _data2Send = null;
    _formValidationImport.resetForm();
    $('#file').next().html('Choose file');
    $('#file').val('');
}

document.addEventListener("DOMContentLoaded", function () {
    initDataTable();
    initFormValidation();
    initOtherElements();
    initMaxLengthForm();
    initActions();
});
