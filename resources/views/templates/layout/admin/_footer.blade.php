<footer class="main-footer py-2">
    @if (!auth()->user()->hasVerifiedEmail())
        <div class="row bg-danger rounded py-3">
            <div class="d-flex flex-column flex-grow-1 justify-content-center align-items-center">
                You email has not been verified
                <a href="{{ route('verification.notice') }}" class="text-primary">Click here to see details.</a>
            </div>
        </div>
    @endif
    <div class="float-left d-none d-sm-block">
        {{ Date::now()->translatedFormat('l, j F Y') }}
    </div>
    <div class="float-right">
        &copy; 2025 &copy;
        <a class="text-muted" href="mailto:theadamz91@gmail.com">
            {!! config('setting.general.copyright') !!}
        </a>
    </div>
</footer>
