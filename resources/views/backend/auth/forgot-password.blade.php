@extends('layouts.admin-auth')

@section('content')
    <div class="row">
        <div class="d-flex justify-content-center">
            <div class="auth-header">
                <p class="f-16 mt-2">Forgot your password? No problem. Just let us know your email address and we will email
                    you a password reset link that will allow you to choose a new one.</p>
            </div>
        </div>
    </div>

    <x-admin.auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('backend.password.email') }}">
        @csrf

        <div class="form-floating mb-2 emailDiv">
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required
                autofocus autocomplete="username" placeholder="" />
            <label for="email">Email</label>

            @if ($errors->has('email'))
                <x-admin.form-error :messages="$errors->get('email')" class="mt-2" />
            @endif
        </div>

        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-secondary">Email Password Reset Link</button>
        </div>
    </form>

    <hr />
    <h5 class="d-flex justify-content-center">
        <a href="{{ route('backend.login') }}" class="text-secondary">Sign In</a>
    </h5>
@endsection
