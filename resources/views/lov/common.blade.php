<div class="modal" id="_modal_lov" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content position-absolute">
            <div class="modal-header">
                <h3 class="modal-title">{{ $data['title'] }}</h3>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <table id="_table_data_lov" name="_table_data_lov" class="table display w-100">
                            <thead>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="_modal_button_close"><i
                       class="fas fa-times mr-2"></i> Cancel</button>
                <button type="button" class="btn btn-primary" id="_modal_button_refresh"><i
                       class="fas fa-sync mr-2"></i> Refresh</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var _dataTableLOV = null;
    var _srcURL = "{{ $data['srcURL'] }}";
    var _queryParameters = "{{ isset($data['queryParameters']) ? $data['queryParameters'] : '' }}";
    var _initSearch = "{{ isset($data['initSearch']) ? $data['initSearch'] : '' }}";
    var DT_headers = {{ Js::from($data['columnHeaders']) }};
    var DT_columns = {{ Js::from($data['columns']) }};
    var DT_definitions = {{ Js::from($data['columnDefinitions']) }};
    var DT_orders = {{ Js::from($data['columnOrders'] === null ? [] : $data['columnOrders']) }};
    var tbody = '';
    var _dataTableLOVResetFilter = false;
    var $resultFromLOV = {
        result: false,
        data: null
    };
</script>
@vite($data['jsFile']);
