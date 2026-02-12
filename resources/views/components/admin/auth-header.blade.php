@props(['header', 'subheader'])

<div class="row">
    <div class="d-flex justify-content-center">
        <div class="auth-header">
            @if (isset($header))
                <h2 class="text-secondary mt-5"><b>{{ $header }}</b></h2>
            @endif

            @if (isset($subheader))
                <p class="f-16 mt-2">{{ $subheader }}</p>
            @endif
        </div>
    </div>
</div>
