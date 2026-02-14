@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            Import Preview - {{ count($preview) }} Routes
        </div>

        <div class="card-body">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Origin</th>
                        <th>Destination</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($preview as $row)
                        <tr class="{{ $row['valid'] ? 'table-success' : 'table-danger' }}">
                            <td>{{ $row['name'] }}</td>
                            <td>{{ $row['origin'] }}</td>
                            <td>{{ $row['destination'] }}</td>
                            <td>{{ $row['valid'] ? '✅ Valid' : '❌ ' . $row['message'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <form action="{{ route('route-pattern.import.confirm', $id) }}" method="POST">
                @csrf

                <button class="btn btn-primary" type="submit">Confirm Import</button>

                <a href="{{ route('route-pattern.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
