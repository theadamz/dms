<div class="form-group fv-row">
    <label class="form-label font-weight-normal mb-1">{{ $label }}
        @if ($isRequired)
            <span class="text-danger">*</span>
        @endif
    </label>
    <textarea id="{{ $elementId }}" name="{{ $elementName }}" class="{{ $class }}" placeholder="{{ $placeholder }}"
        rows="{{ $rows }}" @if ($maxLength > 0) maxlength="{{ $maxLength }}" @endif>{{ $value }}</textarea>
    @if (!empty($textHelper))
        <small class="text-muted">{{ $textHelper }}</small>
    @endif
</div>
