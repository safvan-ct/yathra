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
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Pin</th>
                        <th>Operator Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($preview as $row)
                        <tr class="{{ $row['valid'] ? 'table-success' : 'table-danger' }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row['name'] }}</td>
                            <td>{{ $row['phone'] }}</td>
                            <td>{{ $row['pin'] }}</td>
                            <td>{{ $row['type'] }}</td>
                            <td>{{ $row['valid'] ? '✅ Valid' : '❌ ' . $row['message'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <form action="{{ route('bus-operator.import.confirm', $id) }}" method="POST">
                @csrf

                <button class="btn btn-primary" type="submit">Confirm Import</button>

                <a href="{{ route('bus-operator.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
