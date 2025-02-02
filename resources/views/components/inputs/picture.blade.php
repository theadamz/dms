<div class="form-group @if (!$noLabel) fv-row @endif">
    @if (!$noLabel)
        <label class="form-label font-weight-normal mb-1">{{ $label }}
            @if ($isRequired)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif
    <label name="{{ $elementName }}" class="mb-1">
        <img id="{{ $elementId }}Preview" name="{{ $elementName }}Preview" class="{{ $class }}" src="{{ $pictureSource }}" onerror="this.onerror=null; this.src='{{ $noPictureUrl }}'" style="{{ $imgStyle }}">
        <input type="file" id="{{ $elementId }}" name="{{ $elementName }}" style="display:none" accept="{{ $accept }}">
    </label>
    <div class="d-flex justify-content-end">
        <span class="btn-group">
            <button type="button" id="{{ $elementId }}Remove" name="{{ $elementName }}Remove" class="btn btn-xs btn-default"><i class="fas fa-times"></i> Remove</button>
            <button type="button" id="{{ $elementId }}Pick" name="{{ $elementName }}Pick" class="btn btn-xs btn-default"><i class="fas fa-folder-open"></i> Choose</button>
        </span>
    </div>
    @if (!empty($textHelper))
        <small class="text-muted">{{ $textHelper }}</small>
    @endif
</div>

@push('scripts')
    <script>
        // button control
        document.getElementById('{{ $elementId }}Remove').addEventListener('click', function() {
            $('#{{ $elementId }}Preview').attr('src', '{{ $noPictureUrl }}');
            $('#{{ $elementId }}').val('');
        });
        document.getElementById('{{ $elementId }}Pick').addEventListener('click', function() {
            $('#{{ $elementId }}').click();
        });

        // preview image
        $("#{{ $elementId }}").change(function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#{{ $elementId }}Preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
                reader.onloadend = function() {
                    reader.abort();
                }
            }
        });

        // ensures this works for some older browsers
        MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;

        // the element you want to observe. change the selector to fit your use case
        const img{{ $elementId }}Preview = document.querySelector('#{{ $elementId }}Preview');

        new MutationObserver(function onSrcChange() {

            if (!$('#{{ $elementId }}Preview').attr('src')) {
                $('#{{ $elementId }}Preview').attr('src', '{{ $noPictureUrl }}');
            }

        }).observe(img{{ $elementId }}Preview, {
            attributes: true,
            attributeFilter: ["src"]
        })
    </script>
@endpush
