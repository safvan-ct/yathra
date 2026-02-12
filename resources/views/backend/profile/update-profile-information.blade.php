<x-admin.card title="Profile Information" subTitle="Update your account's profile information and email address.">

    @if (session('status') === 'profile-updated')
        <x-admin.alert type="success" message="Profile updated successfully." />
    @endif

    <form id="send-verification" method="post" action="{{ route('backend.verification.send') }}">
        @csrf
    </form>

    <form action="{{ route('backend.profile.update') }}" method="post">
        @csrf
        @method('patch')

        <div class="row">
            <div class="col-4">
                <x-admin.input name="name" label="Name" :value="old('name', $user->name)" required error="true" />
            </div>

            <div class="col-4">
                <x-admin.input type="email" name="email" label="Email" :value="old('email', $user->email)" required error="true" />
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
            <div>
                <p class="text-md mt-2">
                    Your email address is unverified.
                    <button form="send-verification" class="btn btn-light">Click here to re-send the verification
                        email.</button>
                </p>

                @if (session('status') == 'verification-link-sent')
                    <x-admin.alert type="success"
                        message="A new verification link has been sent to your email address." />
                @endif
            </div>
        @endif

        <button type="submit" class="btn btn-primary"> Save </button>
    </form>
</x-admin.card>
