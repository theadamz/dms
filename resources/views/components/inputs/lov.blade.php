<div class="form-group">
    <label class="form-label font-weight-normal mb-1">{{ $label }} @if ($isRequired)
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="input-group fv-row">
        <input
               type="text"
               class="d-none"
               id="{{ $hiddenElementId }}"
               name="{{ $hiddenElementName }}"
               value="{{ $hiddenElementValue }}" readonly />
        <input
               type="text"
               placeholder="{{ $placeholder }}"
               id="{{ $elementId }}"
               name="{{ $elementName }}"
               value="{{ $elementValue }}"
               class="form-control rounded-left font-weight-normal bg-white"
               autocomplete="off"
               readonly />
        <div class="input-group-append rounded-right">
            <button type="button" class="btn btn-outline-secondary input-group-text @if (!$clearButtonShow) rounded-right @endif" id="{{ $searchButtonName }}" name="{{ $searchButtonName }}">
                <i class="fas fa-search"></i>
            </button>
            @if ($clearButtonShow)
                <button type="button" class="btn btn-outline-secondary input-group-text" id="{{ $clearButtonName }}" name="{{ $clearButtonName }}">
                    <i class="fas fa-times"></i>
                </button>
            @endif
        </div>
    </div>
    @if (!empty($textHelper))
        <small class="text-muted">{{ $textHelper }}</small>
    @endif
    @if (!empty($columnNameForDescription))
        <small class="text-muted" id="columnNameForDescription"></small>
    @endif
</div>

@push('scripts')
    <script type="module">
        import("{{ $data['jsFile'] }}").then(module => {
            let {{ $hiddenElementName }} = null;

            document.getElementById(`{{ $searchButtonName }}`).addEventListener('click', {{ $searchButtonName }});
            @if ($clearButtonShow)
                document.getElementById(`{{ $clearButtonName }}`).addEventListener('click',
                    {{ $clearButtonName }});
            @endif

            function {{ $searchButtonName }}() {
                @if (!empty($dependencyElement))
                    if (!$(`{{ $dependencyElement }}`).val()) return;
                @endif

                module.loadingProcess();
                $('#_dynamic_content').load(`{{ $url }}`, () => {
                    module.loadingProcess(false);
                    $('#_modal_lov').modal('show');
                    $(".modal-dialog").addClass(`{{ $modalSize }}`);

                    $('#_modal_lov').on('hide.bs.modal', function() {
                        if ($resultFromLOV.result) {
                            if ({{ $hiddenElementName }} == $resultFromLOV.data.{{ $hiddenElementColumnName }}) return;
                            {{ $hiddenElementName }} = $resultFromLOV.data.{{ $hiddenElementColumnName }};

                            $(`#{{ $hiddenElementId }}`).val($resultFromLOV.data.{{ $hiddenElementColumnName }});
                            $(`#{{ $elementId }}`).val($resultFromLOV.data.{{ $elementColumnName }});
                            @if (!empty($columnNameForDescription))
                                $(`#columnNameForDescription`).text($resultFromLOV.data.{{ $columnNameForDescription }});
                            @endif
                        }
                    });
                });
            }

            @if ($clearButtonShow)
                function {{ $clearButtonName }}() {
                    $(`#{{ $hiddenElementId }}`).val(null)
                    $(`#{{ $elementId }}`).val(null);
                    @if (!empty($columnNameForDescription))
                        $(`#columnNameForDescription`).text(null);
                    @endif
                }
            @endif
        });
    </script>
@endpush
