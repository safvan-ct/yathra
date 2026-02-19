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
                                style="border-radius: 0 12px 12px 0;" value="1234567891" required name="mobile_number">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bus Number</label>
                        <div class="otp-input-container d-flex gap-2">
                            <input type="tel" class="form-control text-center otp-field" maxlength="1"
                                value="1">
                            <input type="tel" class="form-control text-center otp-field" maxlength="1"
                                value="2">
                            <input type="tel" class="form-control text-center otp-field" maxlength="1"
                                value="3">
                            <input type="tel" class="form-control text-center otp-field" maxlength="1"
                                value="4">
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
                    <a href="#" class="text-primary text-decoration-none fw-bold">Contact Admin</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        let PIN = '';
        document.querySelector("form").addEventListener("submit", function() {

            if (PIN == "") {
                document.querySelectorAll(".otp-field").forEach(function(input) {
                    PIN += input.value;
                });
            }

            document.getElementById("pin").value = PIN;
            PIN = "";
        });

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

        document.querySelectorAll('.otp-field').forEach((input, index, inputs) => {

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
