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
    @if (!empty($elementNameForDescription))
        <small class="text-muted" id="{{ $elementNameForDescription }}"></small>
    @endif
</div>
