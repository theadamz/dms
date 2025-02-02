<div class="form-group fv-row">
    <label class="form-label font-weight-normal mb-1">{{ $label }}</label>
    <div class="form-check">
        <input class="form-check-input" type="checkbox"
               @if (!empty($value)) value="{{ $value }}" @endif id="{{ $elementId }}"
               name="{{ $elementName }}" checked />
        <label class="form-check-label" for="{{ $elementId }}">{{ $checkLabel }}</label>
    </div>
    @if (!empty($textHelper))
        <small class="text-muted">{{ $textHelper }}</small>
    @endif
</div>
