@extends('layouts.admin-auth')

@section('content')
    <div class="row">
        <div class="d-flex justify-content-center">
            <div class="auth-header">
                <h2 class="text-secondary mt-3"><b>Hi, Welcome Back</b></h2>
                <p class="f-16 mt-2">Enter your credentials to continue</p>
            </div>
        </div>
    </div>

    @if ($errors->get('email'))
        <x-admin.alert type="error" :message="$errors->first('email')" />
    @endif

    <form method="POST" action="{{ route('backend.login') }}">
        @csrf

        <div class="form-floating mb-2 emailDiv">
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required
                autofocus autocomplete="username" placeholder="" autocomplete="new-password"/>
            <label for="email">Email Address / Username</label>

            @if ($errors->has('email'))
                <x-admin.form-error :messages="$errors->get('email')" class="mt-2" />
            @endif
        </div>

        <div class="form-floating mb-2 passwordDiv">
            <input type="password" class="form-control" id="password" name="password" required
                autocomplete="current-password" placeholder="" />
            <label for="password">Password</label>

            @if ($errors->has('password'))
                <x-admin.form-error :messages="$errors->get('password')" class="mt-2" />
            @endif
        </div>

        <div class="d-flex mt-1 justify-content-between">
            <div class="form-check">
                <input class="form-check-input input-primary" type="checkbox" id="remember" name="remember" />
                <label class="form-check-label text-muted" for="remember">Remember me</label>
            </div>

            {{-- <h5 class="text-secondary">
                <a href="{{ route('backend.password.request') }}" class="text-secondary">Forgot Password?</a>
            </h5> --}}
        </div>

        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-secondary">Sign In</button>
        </div>
    </form>

    <hr />
    {{-- <h5 class="d-flex justify-content-center">
        <a href="{{ route('backend.register') }}" class="text-secondary">Don't have an account?</a>
    </h5> --}}
@endsection
