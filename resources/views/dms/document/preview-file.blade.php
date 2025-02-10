@if (empty($urlFile))
    <div class="d-flex flex-grow-1 justify-content-center p-5">
        No access to document or not found
    </div>
@else
    {{ $urlFile }}
@endif
