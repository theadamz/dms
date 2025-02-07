import {
    boolValue,
    dateFormat,
    elementIsInPage,
    initDataTableSearch
} from '../../../general';

import {
    components,
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
        "ordering": false,
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
        "ajax": {
            "url": `${_baseURL}/dt/documents`,
            "type": "GET",
            "data": function (d) {
                d.category = $('#filter_category').val();
                d.category_sub = $('#filter_category_sub').val();
            },
        },
        "columns": [{
                "data": "file_name",
                "name": "df.file_origin_name",
                "render": function (data, type, row, meta) {
                    return components.cardDocumentFile(row, false);
                },
            },
            {
                "data": "owner_name",
                "name": "u.name",
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
                "data": "doc_no",
                "name": "d.doc_no",
            },
            {
                "data": "publish_at",
                "className": "dt-center",
                "searchable": false,
                "render": function (data, type, row, meta) {
                    return `${dateFormat(data)}`;
                },
            },
            {
                "data": "is_reviewed",
                "className": "dt-center",
                "orderable": false,
                "searchable": false,
                "render": function (data, type, row, meta) {
                    if (row.is_review_required) return 'Not required';
                    if (boolValue(row.is_reviewed)) {
                        return '<span class="badge font-weight-normal badge-success">Yes</span>';
                    } else {
                        return '<span class="badge font-weight-normal badge-secondary">No</span>';
                    }
                },
            },
            {
                "data": "is_acknowledged",
                "className": "dt-center",
                "orderable": false,
                "searchable": false,
                "render": function (data, type, row, meta) {
                    if (row.is_acknowledgement_required) return 'Not required';
                    if (boolValue(row.is_acknowledged)) {
                        return '<span class="badge font-weight-normal badge-success">Yes</span>';
                    } else {
                        return '<span class="badge font-weight-normal badge-secondary">No</span>';
                    }
                },
            },
            {
                "data": null,
                "className": "dt-center",
                "orderable": false,
                "searchable": false,
                "render": function (data, type, row, meta) {
                    return `<button type="button" class="btn btn-sm btn-outline-secondary" title="Download ${row.file_name}">
                                <i class="fas fa-download d-inline"></i>
                            </button>`;
                },
            },
        ],
    }).on('xhr.dt', function (e, settings, json, xhr) {
        setTimeout(() => {
            _dataTable.columns.adjust();
        }, 300);
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

function reloadDataTable(resetPaging = true) {
    _dataTableResetFilter = false;
    _dataTable.ajax.reload(null, resetPaging);
}

function formFilterReset() {
    $('#filter_category option[value=""]').prop('selected', true).change();
    $("#filter_category_sub").val(null).empty().trigger("change");
    $('#formFilter')[0].reset();
}

document.addEventListener("DOMContentLoaded", function () {
    initOtherElements();
    initDataTable();
    initActions();
});
