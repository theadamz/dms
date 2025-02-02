import {
    dateTimeFormat,
    initDateRangePicker
} from '../../general';

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
            [0, "desc"]
        ],
        "select": {
            "items": "row",
            "style": "single",
            "className": "bg-warning"
        },
        "ajax": {
            "url": `${_baseURL}/dt/sign-in-history`,
            "type": "GET",
            "data": function (d) {
                d.start_date = $('#filter_date').data('daterangepicker').startDate.format('YYYY-MM-DD');
                d.end_date = $('#filter_date').data('daterangepicker').endDate.format('YYYY-MM-DD');
            },
        },
        "columns": [{
                "data": "id",
                "visible": false
            },
            {
                "data": "ip"
            },
            {
                "data": "platform"
            },
            {
                "data": "browser"
            },
            {
                "data": "city",
                "render": function (data, type, row, meta) {
                    return `${row.city} - ${row.country}`
                }
            },
            {
                "data": "created_at",
                "searchable": false,
                "render": function (data, type, row, meta) {
                    return dateTimeFormat(data);
                }
            },
        ],
    }).on('xhr.dt', function (e, settings, json, xhr) {
        setTimeout(() => {
            _dataTable.columns.adjust();
        }, 300);
    });

    // DataTable refresh
    document.querySelector('#refresh').addEventListener('click', () => reloadDataTable(false));
}

function initOtherElements() {
    initDateRangePicker('#filter_date');

    $('#filter_date').on('apply.daterangepicker', function (ev, picker) {
        reloadDataTable(true);
    });
}

function reloadDataTable(resetPaging = true) {
    _dataTableResetFilter = false;
    _dataTable.ajax.reload(null, resetPaging);
}

document.addEventListener("DOMContentLoaded", function () {
    initOtherElements();
    initDataTable();
});
