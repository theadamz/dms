import {
    boolValue,
    dateFormat,
    elementIsInPage,
    initDataTablesCheckBoxes,
    initDataTableSearch,
    initDateRangePicker,
    setDateRangePickerValue
} from '../../../general';

import {
    fillOptionsFromAjax
} from '../../../application';

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
                d.approval_workflow_type = $('#filter_approval_workflow_type').val();
                d.category = $('#filter_category').val();
                d.category_sub = $('#filter_category_sub').val();
                d.review_workflow_type = $('#filter_review_workflow_type').val();
                d.is_review_required = $('#filter_is_review_required').val();
                d.is_reviewed = $('#filter_is_reviewed').val();
                d.acknowledgement_workflow_type = $('#filter_acknowledgement_workflow_type').val();
                d.is_acknowledgement_required = $('#filter_is_acknowledgement_required').val();
                d.is_acknowledged = $('#filter_is_acknowledged').val();
                d.is_locked = $('#filter_is_locked').val();
                d.is_public = $('#filter_is_public').val();
                d.status = $('#filter_status').val();
                d.date_start = $('#filter_date').data('daterangepicker').startDate.format('YYYY-MM-DD');
                d.date_end = $('#filter_date').data('daterangepicker').endDate.format('YYYY-MM-DD');
            },
        },
        "columns": [{
                "data": "id",
                "width": '30px',
                "className": "dt-center",
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
                "render": function (data, type, row, meta) {
                    return `<a href="${_baseURL}/documents/list/${row.id}/view">${data}</a>`;
                }
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
                "data": "notes",
                "name": "d.notes",
                "orderable": false,
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
                "data": "is_review_required",
                "className": "dt-center",
                "orderable": false,
                "searchable": false,
                "render": function (data, type, row, meta) {
                    if (boolValue(data)) {
                        return '<span class="badge font-weight-normal badge-warning">Yes</span>';
                    } else {
                        return '<span class="badge font-weight-normal badge-secondary">No</span>';
                    }
                }
            },
            {
                "data": "review_workflow_type",
                "orderable": false,
                "searchable": false,
            },
            {
                "data": "is_reviewed",
                "className": "dt-center",
                "orderable": false,
                "searchable": false,
                "render": function (data, type, row, meta) {
                    if (boolValue(data)) {
                        return '<span class="badge font-weight-normal badge-success">Yes</span>';
                    } else {
                        return '<span class="badge font-weight-normal badge-secondary">No</span>';
                    }
                }
            },
            {
                "data": "is_acknowledgement_required",
                "className": "dt-center",
                "orderable": false,
                "searchable": false,
                "render": function (data, type, row, meta) {
                    if (boolValue(data)) {
                        return '<span class="badge font-weight-normal badge-warning">Yes</span>';
                    } else {
                        return '<span class="badge font-weight-normal badge-secondary">No</span>';
                    }
                }
            },
            {
                "data": "acknowledgement_workflow_type",
                "orderable": false,
                "searchable": false,
            },
            {
                "data": "is_acknowledged",
                "className": "dt-center",
                "orderable": false,
                "searchable": false,
                "render": function (data, type, row, meta) {
                    if (boolValue(data)) {
                        return '<span class="badge font-weight-normal badge-success">Yes</span>';
                    } else {
                        return '<span class="badge font-weight-normal badge-secondary">No</span>';
                    }
                }
            },
            {
                "data": "is_locked",
                "className": "dt-center",
                "orderable": false,
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
                "data": "is_public",
                "className": "dt-center",
                "orderable": false,
                "searchable": false,
                "render": function (data, type, row, meta) {
                    if (boolValue(data)) {
                        return '<span class="badge font-weight-normal badge-warning">Yes</span>';
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
    if (elementIsInPage(document.querySelector('#edit'))) {
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
}

async function editData(id) {
    window.location.href = `${_baseURL}/documents/${id}/edit`;
}

function reloadDataTable(resetPaging = true) {
    _dataTableResetFilter = false;
    _dataTable.ajax.reload(null, resetPaging);
}

function formFilterReset() {
    $('#filter_approval_workflow_type option[value=""]').prop('selected', true).change();
    $('#filter_category option[value=""]').prop('selected', true).change();
    $("#filter_category_sub").val(null).empty().trigger("change");
    $('#filter_review_workflow_type option[value=""]').prop('selected', true).change();
    $('#filter_is_review_required option[value=""]').prop('selected', true).change();
    $('#filter_is_reviewed option[value=""]').prop('selected', true).change();
    $('#filter_acknowledgement_workflow_type option[value=""]').prop('selected', true).change();
    $('#filter_is_acknowledgement_required option[value=""]').prop('selected', true).change();
    $('#filter_is_acknowledged option[value=""]').prop('selected', true).change();
    $('#filter_is_locked option[value=""]').prop('selected', true).change();
    $('#filter_is_public option[value=""]').prop('selected', true).change();
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
