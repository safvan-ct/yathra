<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Owner Login | {{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <link href="{{ asset('operator/style.css') }}" rel="stylesheet">

    <link href="{{ asset('operator/css/index.css') }}" rel="stylesheet">
</head>

<body>
    <div class="app-shell">
        <div class="app-header">
            <div class="bus-icon-circle">🚌</div>
            <h3 class="fw-bold mb-1" id="titleText">Bus Owner</h3>
            <p class="small opacity-75" id="subText">Sign in to your dashboard</p>
        </div>

        <div class="login-body">
            <div class="login-card" id="loginScreen">

                @if ($errors->get('email'))
                    <x-admin.alert type="error" :message="$errors->first('email')" />
                @endif

                {{-- <form onsubmit="showOTPScreen(event)"> --}}
                <form action="{{ route('operator.login') }}" method="POST">
                    @csrf

                    {{-- <div class="mb-3">
                        <label class="form-label">Bus Number</label>
                        <input type="text" class="form-control" placeholder="e.g. KL 01 AB 1234" required
                            name="bus_number" value="KL 01 AB 1234">
                    </div> --}}

                    <div class="mb-2">
                        <label class="form-label">Mobile Number</label>

                        <div class="input-group">
                            <span class="input-group-text border-0 bg-light"
                                style="border-radius: 12px 0 0 12px;">+91</span>
                            <input type="tel" class="form-control border-start-0" placeholder="00000 00000"
                                style="border-radius: 0 12px 12px 0;" value="{{ old('phone', '1234567891') }}"
                                required name="phone">
                        </div>

                        @if ($errors->has('phone'))
                            <x-admin.form-error :messages="$errors->get('phone')" class="mt-2" />
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label">PIN Number</label>
                        <div class="otp-input-container d-flex gap-2">
                            <input type="tel" class="form-control text-center otp-field loginPin" maxlength="1"
                                name="l_1" value="{{ old('l_1', '1') }}">
                            <input type="tel" class="form-control text-center otp-field loginPin" maxlength="1"
                                name="l_2" value="{{ old('l_2', '2') }}">
                            <input type="tel" class="form-control text-center otp-field loginPin" maxlength="1"
                                name="l_3" value="{{ old('l_3', '3') }}">
                            <input type="tel" class="form-control text-center otp-field loginPin" maxlength="1"
                                name="l_4" value="{{ old('l_4', '4') }}">
                        </div>

                        @if ($errors->has('pin'))
                            <x-admin.form-error :messages="$errors->get('pin')" class="mt-2" />
                        @endif
                    </div>

                    <input type="hidden" name="pin" id="pin">
                    {{-- <button type="submit" class="btn btn-otp">Get OTP</button> --}}

                    <button type="submit" class="btn btn-otp">Verify & Login</button>

                    <div class="text-center mt-2">
                        <a href="#" class="otp-link">Need help accessing your account?</a>
                    </div>
                </form>
            </div>

            <div class="login-card hidden" id="otpScreen">
                <div class="text-center mb-3">
                    <span class="badge bg-light text-primary p-2">OTP sent to +91 ******4321</span>
                </div>
                <div class="otp-input-container">
                    <input type="tel" class="otp-field" maxlength="1" onkeyup="moveFocus(this, 1)">
                    <input type="tel" class="otp-field" maxlength="1" onkeyup="moveFocus(this, 2)">
                    <input type="tel" class="otp-field" maxlength="1" onkeyup="moveFocus(this, 3)">
                    <input type="tel" class="otp-field" maxlength="1" onkeyup="moveFocus(this, 4)">
                    <input type="tel" class="otp-field" maxlength="1" onkeyup="moveFocus(this, 5)">
                    <input type="tel" class="otp-field" maxlength="1" onkeyup="moveFocus(this, 6)">
                </div>
                <button class="btn btn-primary-custom mt-4" onclick="window.location.href='dashboard.html'">Verify &
                    Login</button>
                <div class="text-center mt-3">
                    <p class="small text-muted mb-0">Didn't receive code?
                        <a href="#" class="text-decoration-none fw-bold small">Resend OTP in <span
                                id="timer">30</span>s</a>
                    </p>
                </div>
                <button class="btn btn-link btn-sm w-100 mt-1 text-secondary" onclick="showLoginScreen()">Change
                    Number</button>
            </div>

            <div class="footer-links">
                <p class="mb-0">
                    New partner?

                    <a href="javascript:void(0);" class="text-primary text-decoration-none fw-bold"
                        data-bs-toggle="modal" data-bs-target="#registerModal">
                        Register
                    </a>
                </p>
            </div>
        </div>
    </div>

    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="addBusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 25px;">
                <div class="modal-header text-white"
                    style="background: var(--primary-gradient); border-radius: 25px 25px 0 0; padding: 25px;">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 rounded-2 p-2 me-3">
                            <i class="bi bi-bus-front fs-4"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold" id="addBusModalLabel">Register as Bus Operator</h5>
                            <p class="small mb-0 opacity-75">Submit your details</p>
                        </div>
                    </div>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <form action="{{ route('operator.register') }}" method="POST" id="registerForm">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary text-uppercase">
                                Registeration Name
                            </label>

                            <input type="text" class="form-control form-control-lg border-2 shadow-sm"
                                placeholder="e.g. KSRTC" style="border-radius: 12px; font-size: 1rem;" required
                                name="name" id="name" value="{{ old('name') }}">

                            @if ($errors->register->has('name'))
                                <x-admin.form-error :messages="$errors->register->get('name')" class="mt-2" />
                            @endif
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Mobile Number</label>

                            <div class="input-group">
                                <span class="input-group-text border-0 bg-light"
                                    style="border-radius: 12px 0 0 12px;">+91</span>
                                <input type="tel" class="form-control border-start-0" placeholder="00000 00000"
                                    style="border-radius: 0 12px 12px 0;" required name="phone"
                                    value="{{ old('phone') }}">
                            </div>

                            @if ($errors->register->has('phone'))
                                <x-admin.form-error :messages="$errors->register->get('phone')" class="mt-2" />
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Login PIN Number</label>
                            <div class="otp-input-container d-flex gap-2">
                                <input type="tel" class="form-control text-center otp-field registerOtp"
                                    max="1" name="r_1" value="{{ old('r_1') }}" required>
                                <input type="tel" class="form-control text-center otp-field registerOtp"
                                    max="1" name="r_2" value="{{ old('r_2') }}" required>
                                <input type="tel" class="form-control text-center otp-field registerOtp"
                                    max="1" name="r_3" value="{{ old('r_3') }}" required>
                                <input type="tel" class="form-control text-center otp-field registerOtp"
                                    max="1" name="r_4" value="{{ old('r_4') }}" required>
                            </div>

                            <input type="hidden" name="register_pin" id="register_pin">

                            @if ($errors->register->has('register_pin'))
                                <x-admin.form-error :messages="$errors->register->get('register_pin')" class="mt-2" />
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Select Operator Type</label>

                            <select name="type" class="form-select">
                                @foreach (\App\Enums\OperatorType::cases() as $type)
                                    <option value="{{ $type->value }}"
                                        {{ $type->value == old('type', 'private') ? 'selected' : '' }}>
                                        {{ ucfirst(strtolower($type->name)) }}
                                    </option>
                                @endforeach
                            </select>

                            @if ($errors->register->has('type'))
                                <x-admin.form-error :messages="$errors->register->get('type')" class="mt-2" />
                            @endif
                        </div>

                        <div class="d-grid gap-2 mt-2">
                            <button type="submit" class="btn btn-lg text-white fw-bold py-3"
                                style="background: var(--primary-gradient); border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);">
                                Register
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-body text-center p-4">
                    <div class="success-icon-wrapper mb-3">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>

                    <h4 class="fw-bold text-dark">Success!</h4>
                    <p class="text-muted small px-3">
                        {{ session('success') }}
                    </p>

                    <button type="button" class="btn btn-success-custom w-100 py-3 mt-2 rounded-3"
                        data-bs-dismiss="modal">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    @if ($errors->register->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
                registerModal.show();
            });
        </script>
    @endif

    @session('success')
        <script>
            const successPopup = new bootstrap.Modal(document.getElementById('successModal'));
            successPopup.show();
        </script>
    @endsession

    <script>
        let PIN = '';

        document.getElementById("registerForm").addEventListener("submit", function() {
            setPinValue('register_pin', 'registerOtp');
        });

        document.querySelector("form").addEventListener("submit", function() {
            setPinValue('pin', 'loginPin');
        });

        function setPinValue(field, otpSelector = null) {
            if (PIN == "") {
                document.querySelectorAll("." + otpSelector).forEach(function(input) {
                    PIN += input.value;
                });
            }

            document.getElementById(field).value = PIN;
            PIN = "";
        }

        function showOTPScreen(e) {
            e.preventDefault();
            document.getElementById('loginScreen').classList.add('hidden');
            document.getElementById('otpScreen').classList.remove('hidden');
            document.getElementById('titleText').innerText = "Verification";
            document.getElementById('subText').innerText = "Enter the 6-digit code";
            startTimer();
        }

        function showLoginScreen() {
            document.getElementById('otpScreen').classList.add('hidden');
            document.getElementById('loginScreen').classList.remove('hidden');
            document.getElementById('titleText').innerText = "Bus Owner";
            document.getElementById('subText').innerText = "Sign in to your dashboard";
        }

        // Auto-focus next input field
        // function moveFocus(current, index) {
        //     if (current.value.length >= 1 && index < 6) {
        //         document.querySelectorAll('.otp-field')[index].focus();
        //     }
        // }

        document.querySelectorAll('.loginPin').forEach((input, index, inputs) => {

            // Allow only numbers
            input.addEventListener('input', () => {
                input.value = input.value.replace(/[^0-9]/g, '');

                if (input.value.length === 1) {
                    input.dataset.value = input.value; // store real value
                    setTimeout(() => {
                        PIN += input.value;
                        input.value = '*'; // mask after short delay

                        if (index < inputs.length - 1) {
                            inputs[index + 1].focus();
                        }
                    }, 200);
                }
            });

            // Show actual value on focus
            input.addEventListener('focus', () => {
                if (input.dataset.value) {
                    input.value = input.dataset.value;
                }
            });

            // Mask when focus lost
            input.addEventListener('blur', () => {
                if (input.dataset.value) {
                    input.value = '*';
                }
            });

            // Backspace support
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace') {
                    input.value = '';
                    input.dataset.value = '';

                    if (index > 0) {
                        inputs[index - 1].focus();
                    }
                }
            });

        });

        function startTimer() {
            let timeLeft = 10;
            const timerElement = document.getElementById('timer');
            const interval = setInterval(() => {
                if (timeLeft <= 0) clearInterval(interval);
                timerElement.innerText = timeLeft--;
            }, 1000);
        }
    </script>
</body>

</html>
