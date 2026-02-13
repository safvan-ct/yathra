@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            Import Preview - {{ count($preview) }} Districts of {{ $state->name }}
        </div>

        <div class="card-body">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($preview as $row)
                        <tr class="{{ $row['valid'] ? 'table-success' : 'table-danger' }}">
                            <td>{{ $row['data']['name'] }}</td>
                            <td>{{ $row['data']['code'] }}</td>
                            <td>
                                @if ($row['valid'])
                                    ✅ Valid
                                @else
                                    ❌ {{ $row['duplicate'] ? 'Duplicate' : implode(', ', $row['errors']) }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <form action="{{ route('district.import.confirm', $state->id) }}" method="POST">
                @csrf

                <button class="btn btn-primary" type="submit">Confirm Import</button>

                <a href="{{ route('district.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
