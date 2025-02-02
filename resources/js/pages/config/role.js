import {
    _datatableClearSelectedValues,
    axiosCustom,
    formValidationSetErrorMessages,
    initDataTablesCheckBoxes,
    initDataTableSearch,
    initDragableModal,
    initMaxLength,
    MsgBox,
    refactorErrorMessages,
    regExpForCode,
    showBlockUIElement,
    showProgressButton
} from '../../general';

let url = null;

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
            "url": `${_baseURL}/dt/configs/roles`,
            "type": "GET",
            "data": function (d) {

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
                "data": "code"
            },
            {
                "data": "name"
            },
            {
                "data": "def_path"
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
}

function initFormValidation() {
    _formValidation = $(document.querySelector('#formInput')).validate({
        rules: {
            code: {
                required: true,
                regex: regExpForCode
            },
            name: {
                required: true,
            },
            def_path: {
                required: true,
            },
        },
        submitHandler: function (form, e) {
            e.preventDefault();

            if ($(form).valid()) saveData();
        }
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
}

function initMaxLengthForm() {
    initMaxLength('#code');
    initMaxLength('#name');
    initMaxLength('#def_path');
}

async function saveData() {
    // show progress
    showProgressButton(true, '#save');
    showBlockUIElement('#formInput');

    // prepare url
    url = `${_baseURL}/configs/roles`;

    // if _action is edit then add suffix
    if (_action === 'edit') url += `/${_id}`;

    // decide method
    const method = _action === 'create' ? 'POST' : 'PUT';

    // send request
    const response = await axiosCustom(url, method, $('#formInput').serialize());

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
    const response = await axiosCustom(`${_baseURL}/configs/roles/${_id}`, 'GET');

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
    $('#code').val(data.code);
    $('#name').val(data.name);
    $('#def_path').val(data.def_path);

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
    const response = await axiosCustom(`${_baseURL}/configs/roles`, "DELETE", _data2Send);

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

function reloadDataTable(resetPaging = true) {
    _dataTableResetFilter = false;
    _dataTable.ajax.reload(null, resetPaging);
}

function formInputClear() {
    _action = 'create';
    _data2Send = null;
    _formValidation.resetForm();
    $('#formInput')[0].reset();
}

document.addEventListener("DOMContentLoaded", function () {
    initDataTable();
    initFormValidation();
    initOtherElements();
    initMaxLengthForm();
    initActions();
});
