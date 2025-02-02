import {
    select2TemplateOptions
} from "../../application";
import {
    _datatableClearSelectedValues,
    axiosCustom,
    convertApplicationXWwwFormUrlencodedToJson,
    dateTimeFormat,
    downloadFile,
    formValidationSetErrorMessages,
    initDataTablesCheckBoxes,
    initDataTableSearch,
    initDragableModal,
    initMaxLength,
    initselect2AjaxCustomOption,
    loadingProcess,
    MsgBox,
    refactorErrorMessages,
    showBlockUIElement,
    showProgressButton
} from '../../general';

let url = null;
let users = [];

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
            "url": `${_baseURL}/dt/basics/approval-sets`,
            "type": "GET",
            "data": function (d) {},
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
                "data": "name",
            },
            {
                "data": "count",
                "className": "dt-center",
                "orderable": false,
                "searchable": false,
            },
            {
                "data": "created_by",
                "orderable": false,
                "searchable": false,
                "render": function (data, type, row, meta) {
                    return `<div class="d-flex flex-column">
                               <small>${row.created_user.name}</small>
                               <small class="text-muted text-xs">${dateTimeFormat(row.created_at)}</small>
                            </div>`;
                },
            },
            {
                "data": "updated_by",
                "orderable": false,
                "searchable": false,
                "render": function (data, type, row, meta) {
                    if (!row.updated_by) return '';

                    return `<div class="d-flex flex-column">
                               <small>${row.updated_user.name}</small>
                               <small class="text-muted text-xs">${dateTimeFormat(row.updated_at)}</small>
                            </div>`;
                },
            },
            {
                "data": "last_used",
                "orderable": false,
                "searchable": false,
                "render": function (data, type, row, meta) {
                    if (!row.last_used) return '';

                    return `<small>${row.last_used}</small>`;
                },
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
            name: {
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
    initselect2AjaxCustomOption("#users", "Select", `${_baseURL}/options/configs/users`, 2, select2TemplateOptions.userResult, select2TemplateOptions.userSelection, false, true, true, _limit, false);
    $('#userSetTable tbody').sortable({
        handle: '.approval-set-handle-order',
        invertSwap: true,
        group: 'list',
        animation: 200,
        ghostClass: 'ghost',
        onSort: approvalSetTableRender,
    });

    // events
    document.getElementById('addUserSet').addEventListener('click', function () {
        approvalSetAdd($('#users').select2('data'));
    });

    approvalSetTableRender();
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
    // check if users is empty
    if (users.length <= 0) {
        MsgBox.HtmlNotification('Users is empty');
        return;
    }

    // show progress
    showProgressButton(true, '#save');
    showBlockUIElement('#formInput');

    // prepare url
    url = `${_baseURL}/basics/approval-sets`;

    // if _action is edit then add suffix
    if (_action === 'edit') url += `/${_id}`;

    // decide method
    const method = _action === 'create' ? 'POST' : 'PUT';

    // prepare data
    _data2Send = convertApplicationXWwwFormUrlencodedToJson($('#formInput').serialize(), false);
    _data2Send['users'] = users;

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
    const response = await axiosCustom(`${_baseURL}/basics/approval-sets/${_id}`, 'GET');

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
    $('#name').val(data.name);
    users = data.users;
    approvalSetTableRender();

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
    const response = await axiosCustom(`${_baseURL}/basics/approval-sets`, "DELETE", _data2Send);

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

async function exportData() {
    const confirmation = await MsgBox.Confirm('Are you sure?').catch(err => {
        if (err) console.log(err)
    });
    if (!confirmation) return;
    loadingProcess(true, 'Please wait', 'Processing data...');

    // get data
    const response = await axiosCustom(`${_baseURL}/basics/approval-sets/exports`, 'POST', null, 'application/json');

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
    users = [];
    approvalSetTableRender();
}

function approvalSetAdd(selectedUsers) {
    if (!selectedUsers) return;

    selectedUsers.forEach(user => {
        // check if user already exist
        if (users.find(u => u.id === user.id)) return;

        // add user
        users.push({
            id: user.id,
            name: user.name,
            email: user.email,
            order: users.length + 1
        })
    });

    approvalSetTableRender();
}

function approvalSetTableRender() {
    // clear events
    $('.approval-set-remove').off('click');

    if (users.length <= 0) {
        $('#userSetTable tbody').html(`<tr>
                                        <td scope="row" colspan="5" class="text-center">
                                            <span class="p-5">Not found</span>
                                        </td>
                                       </tr>`);
        return;
    }

    // reordering
    approvalsetReorder();

    let html = '';
    users.forEach((user, index) => {
        html += `<tr class="approval-set-handle-order" data-id="${user.id}">
                    <td><i class="fas fa-arrows-alt-v"></i></td>
                    <td>${user.order}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger approval-set-remove" data-id="${user.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
    });

    $('#userSetTable tbody').html(html);

    // init events
    $('.approval-set-remove').on('click', approvalSetRemove);

    // clear input
    $('#users').val(null).empty().trigger('change');
}

function approvalSetRemove() {
    const id = $(this).data('id');
    const data = users.find(item => item.id == id);
    if (!data) return;

    // filter data to remove
    users = users.filter(item => item.id != id);

    approvalSetTableRender();
}

function approvalsetReorder() {
    // get orders from tr data-id
    const orderIds = $('#userSetTable tbody').sortable('toArray');

    let order = 1;
    orderIds.forEach((id, index) => {
        // get which index is data stored
        const dataIndex = users.findIndex(item => item.id == id);

        // if not found
        if (dataIndex < 0) return;

        // reassign order
        users[dataIndex].order = order++;
    });

    // sort
    users.sort((a, b) => a.order - b.order);
}

document.addEventListener("DOMContentLoaded", function () {
    initDataTable();
    initFormValidation();
    initOtherElements();
    initMaxLengthForm();
    initActions();
});
