@props(['headers', 'class' => 'table-striped'])

<div class="table-responsive">
    <table class="table table-hover {{ $class }} text-center" id="dataTable">
        <thead class="table-light">
            <tr>
                @foreach ($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
