@extends('layouts.admin-auth')

@section('content')
    <div class="row">
        <div class="d-flex justify-content-center">
            <div class="auth-header">
                <h2 class="text-secondary mt-5"><b>Sign up</b></h2>
                <p class="f-16 mt-2">Enter your credentials to continue</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('backend.register') }}">
        @csrf

        <div class="form-floating mb-2">
            <input type="name" class="form-control" id="name" name="name" value="{{ old('name') }}" required
                autofocus placeholder="" />
            <label for="name">Name</label>

            @if ($errors->has('name'))
                <x-admin.form-error :messages="$errors->get('name')" class="mt-2" />
            @endif
        </div>

        <div class="form-floating mb-2">
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required
                autofocus autocomplete="username" placeholder="" />
            <label for="email">Email Address / Username</label>

            @if ($errors->has('email'))
                <x-admin.form-error :messages="$errors->get('email')" class="mt-2" />
            @endif
        </div>

        <div class="form-floating mb-2 passwordDiv">
            <input type="password" class="form-control" id="password" name="password" required autocomplete="new-password"
                placeholder="" />
            <label for="password">Password</label>

            @if ($errors->has('password'))
                <x-admin.form-error :messages="$errors->get('password')" class="mt-2" />
            @endif
        </div>

        <div class="form-floating mb-2 passwordDiv">
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required
                autocomplete="new-password" placeholder="" />
            <label for="password_confirmation">Confirm Password</label>

            @if ($errors->has('password'))
                <x-admin.form-error :messages="$errors->get('password')" class="mt-2" />
            @endif
        </div>

        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-secondary">Sign Up</button>
        </div>
    </form>

    <hr />
    <h5 class="d-flex justify-content-center">
        <a href="{{ route('backend.login') }}" class="text-secondary">Already have an account?</a>
    </h5>
@endsection
