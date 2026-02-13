@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            Import Preview
        </div>

        <div class="card-body">

            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Info</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($preview as $row)
                        <tr class="{{ $row['valid'] ? 'table-success' : 'table-danger' }}">
                            <td>{{ $row['data']['name'] }}</td>
                            <td>{{ $row['data']['code'] }}</td>
                            <td>
                                <b>Local Body:</b> {{ $row['data']['local_body'] }},&nbsp;
                                <b>Assembly:</b> {{ $row['data']['assembly'] }},&nbsp;
                                <b>District:</b> {{ $row['data']['district'] }},&nbsp;
                                <b>State:</b> {{ $row['data']['state'] }},&nbsp;
                                <b>Pincode:</b> {{ $row['data']['pincode'] }},&nbsp;
                                <b>Latitude:</b> {{ $row['data']['latitude'] }},&nbsp;
                                <b>Longitude:</b> {{ $row['data']['longitude'] }}
                            </td>
                            <td>
                                @if ($row['valid'])
                                    ✅ Valid
                                @else
                                    ❌
                                    @if ($row['duplicate'])
                                        Duplicate Code
                                    @endif
                                    {{ implode(', ', $row['errors']) }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <form action="{{ route('backend.stop.import.confirm') }}" method="POST">
                @csrf
                @method('POST')
                <button class="btn btn-primary" type="submit">Confirm Import</button>
                <a href="{{ route('backend.stop.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </form>

        </div>
    </div>
@endsection
