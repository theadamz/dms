import {
    initDataTableSearch,
    initDragableModal
} from '../../../js/general';

function closeModal() {
    $('#_table_data_lov tbody').off('dblclick');
    $('#_modal_lov').off('hidden.bs.modal');
    _dataTableLOV.destroy();
    $("#_dynamic_content").empty();
}

function createHeaderTable(data) {
    let thead = '';
    $('#_table_data_lov thead').remove();
    thead =
        '<thead><tr class="text-gray-900 fw-bolder align-middle border-top border-top-dashed border-bottom border-bottom-dashed border-secondary">'; // Pembuka
    $.each(data, function (index, value) {
        thead = thead + '<th>' + value + '</th>';
    });
    thead = thead + '<th></th>';
    thead = thead + '</tr></thead>'; // Penutup
    $('#_table_data_lov').append(thead);
}

function initDataTableLOV() {
    // add column to select data
    DT_columns.push({
        "data": null,
        "width": 40,
        "className": "text-center"
    });

    // add column defs to select data
    DT_definitions.push({
        "targets": -1,
        "orderable": false,
        "searchable": false,
        "render": function (data, type, full, meta) {
            return `<button class="btn btn-sm btn-icon btn-flex btn-light btn-active-success datatable-lov-send-values" data-row="${meta.row}" data-toggle="tooltip" data-placement="top" title="Get values"><i class="fa fa-arrow-right"></i></button>`;
        },
    });

    // initialize datatable
    _dataTableLOV = $('#_table_data_lov').DataTable({
        "pageLength": 5,
        "pagingType": "simple",
        "lengthMenu": [5, 10, 20],
        "searchDelay": 500,
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "search": {
            "search": _initSearch
        },
        "select": {
            "items": 'row',
            "style": "os",
            "className": "bg-light"
        },
        "ajax": {
            "url": `${_srcURL}?${_queryParameters}`,
            "type": "GET",
        },
        "columns": DT_columns,
        "columnDefs": DT_definitions,
        "order": DT_orders,
    }).on('xhr.dt', function (e, settings, json, xhr) {
        setTimeout(() => {
            _dataTableLOV.columns.adjust();
        }, 300);
    }).on('draw.dt', function (e, settings, json, xhr) {
        const elements = document.getElementsByClassName('datatable-lov-send-values');
        for (const element of elements) {
            element.addEventListener('click', () => sendValues(element.getAttribute('data-row')));
        }
    });

    // dataTable search
    initDataTableSearch(_dataTableLOV, '[aria-controls="_table_data_lov"]');
}

function sendValues(idx) {
    // check if rows not empty
    if (_dataTableLOV.data().count() <= 0) return;

    // set result
    $resultFromLOV = {
        result: true,
        data: _dataTableLOV.row(idx).data()
    }

    // hide modal
    $('#_modal_lov').modal('hide');
}

function reloadDataTableLOV(resetPaging = true) {
    _dataTableLOVResetFilter = false;
    _dataTableLOV.ajax.reload(null, resetPaging);
}

export function lovCommonInitialize() {
    initDragableModal('#_modal_lov');
    createHeaderTable(DT_headers);
    initDataTableLOV();

    $('#_table_data_lov tbody').on('dblclick', 'tr', function () {
        sendValues(_dataTableLOV.row(this).index());
    });

    $('#_modal_lov').on('hidden.bs.modal', function () {
        closeModal();
    });

    $('#_modal_button_refresh').on('click', function () {
        reloadDataTableLOV();
    });
}
