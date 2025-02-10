@use('App\Enums\FileType', 'FileType')
@use('Illuminate\Support\Number', 'Number')

<div class="d-flex flex-column align-items-start">
    <button type="button" class="btn btn-link text-muted font-weight-normal p-0 file-card-info" data-id="{{ $id }}">
        <i class="{{ FileType::tryFrom($fileExt)->getIcon() }}"></i>
        {{ $fileOriginName }}
    </button>
    <div class="d-flex text-muted mt-1">
        <span class="badge badge-primary mr-2">{{ $fileType }}</span>
        <span class="badge badge-info">{{ Number::fileSize($fileSize, precision: 2) }}</span>
    </div>
</div>
