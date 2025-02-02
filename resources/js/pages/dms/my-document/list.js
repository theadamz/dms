import {
    _datatableClearSelectedValues,
    axiosCustom,
    boolValue,
    dateFormat,
    formValidationSetErrorMessages,
    initDataTablesCheckBoxes,
    initDataTableSearch,
    initDateRangePicker,
    MsgBox,
    refactorErrorMessages,
    setDateRangePickerValue,
    showBlockUIElement,
    showProgressButton
} from '../../../general';

import {
    fillOptionsFromAjax
} from '../../../application';

let url = null;
const defStartDate = moment().subtract(365, 'days');

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
            "url": `${_baseURL}/dt/documents/list`,
            "type": "GET",
            "data": function (d) {
                d.is_locked = $('#filter_is_locked').val();
                d.req_review = $('#filter_req_review').val();
                d.req_acknowledge = $('#filter_req_acknowledge').val();
                d.approval_workflow_type = $('#filter_approval_workflow_type').val();
                d.is_reviewed = $('#filter_is_reviewed').val();
                d.is_acknowledged = $('#filter_is_acknowledged').val();
                d.category = $('#filter_category').val();
                d.category_sub = $('#filter_category_sub').val();
                d.status = $('#filter_status').val();
                d.date_start = $('#filter_date').data('daterangepicker').startDate.format('YYYY-MM-DD');
                d.date_end = $('#filter_date').data('daterangepicker').endDate.format('YYYY-MM-DD');
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
                "data": "doc_no",
                "name": "d.doc_no",
            },
            {
                "data": "date",
                "className": "dt-center",
                "width": '80px',
                "searchable": false,
                "render": function (data, type, row, meta) {
                    return `${dateFormat(data)}`;
                },
            },
            {
                "data": "category_name",
                "orderable": false,
                "searchable": false,
                "render": function (data, type, row, meta) {
                    return `<div class="d-flex flex-column">
                               <span>${row.category_name}</span>
                               <small class="text-muted text-xs">${row.category_sub_name}</small>
                            </div>`;
                },
            },
            {
                "data": "owner_name",
                "name": "u.name",
                "orderable": false,
            },
            {
                "data": "approval_workflow_type",
                "orderable": false,
                "searchable": false,
            },
            {
                "data": "is_locked",
                "className": "dt-center",
                "searchable": false,
                "render": function (data, type, row, meta) {
                    if (boolValue(data)) {
                        return '<span class="badge font-weight-normal badge-danger">Yes</span>';
                    } else {
                        return '<span class="badge font-weight-normal badge-success">No</span>';
                    }
                }
            },
            {
                "data": "req_review",
                "className": "dt-center",
                "orderable": false,
                "searchable": false,
                "render": function (data, type, row, meta) {
                    if (boolValue(row.req_review)) {
                        let badge = '<span class="badge font-weight-normal badge-info">Required</span>';
                        badge += `<span class="badge font-weight-normal badge-${boolValue(row.is_reviewed) ? 'success' : 'warning'}">${boolValue(row.is_reviewed) ? 'Yes' : 'No'}</span>`;
                        return badge;
                    } else {
                        return '<span class="badge font-weight-normal badge-secondary">No</span>';
                    }
                }
            },
            {
                "data": "req_acknowledgement",
                "className": "dt-center",
                "orderable": false,
                "searchable": false,
                "render": function (data, type, row, meta) {
                    if (boolValue(row.req_acknowledgement)) {
                        let badge = '<span class="badge font-weight-normal badge-info">Required</span>';
                        badge += `<span class="badge font-weight-normal badge-${boolValue(row.is_acknowledged) ? 'success' : 'warning'}">${boolValue(row.is_acknowledged) ? 'Yes' : 'No'}</span>`;
                        return badge;
                    } else {
                        return '<span class="badge font-weight-normal badge-secondary">No</span>';
                    }
                }
            },
            {
                "data": "status",
                "className": "dt-center",
                "orderable": false,
                "searchable": false,
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

function initOtherElements() {
    // formFilter
    initDateRangePicker('#filter_date');
    setDateRangePickerValue('#filter_date', defStartDate);

    // formFilter events
    $("#filter_category").on("change", function () {
        if ($(this).val()) {
            $("#filter_category_sub").val(null).empty().trigger("change");
            fillOptionsFromAjax("#filter_category_sub", `${_baseURL}/options/basics/category-subs?category=${$(this).val()}`);
        }
    });
    $('#filter_category').on('select2:clear', function (e) {
        $('#filter_category option[value=""]').prop('selected', true).change();
        $("#filter_category_sub").val(null).empty().trigger("change");
    });
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

function reloadDataTable(resetPaging = true) {
    _dataTableResetFilter = false;
    _dataTable.ajax.reload(null, resetPaging);
}

function formFilterReset() {
    $('#filter_is_locked option[value=""]').prop('selected', true).change();
    $('#filter_req_review option[value=""]').prop('selected', true).change();
    $('#filter_req_acknowledge option[value=""]').prop('selected', true).change();
    $('#filter_approval_workflow_type option[value=""]').prop('selected', true).change();
    $('#filter_is_reviewed option[value=""]').prop('selected', true).change();
    $('#filter_is_acknowledged option[value=""]').prop('selected', true).change();
    $('#filter_category option[value=""]').prop('selected', true).change();
    $("#filter_category_sub").val(null).empty().trigger("change");
    $("#filter_status").val(null).trigger("change");
    $('#formFilter')[0].reset();
    setTimeout(() => {
        setDateRangePickerValue('#filter_date', defStartDate);
    }, 500);
}

document.addEventListener("DOMContentLoaded", function () {
    initOtherElements();
    initDataTable();
    initActions();
});
