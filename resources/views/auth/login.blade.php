@include('partials.head')

<style>
    body {
        background-color: #FDFDFD;
        font-family: 'Poppins', sans-serif;
    }

    .login-image {
        margin-right: 40px;
    }

    .login-card {
        border-radius: 25px;
        max-width: 510px;
        width: 100%;
        min-height: 410px;
        padding: 40px 35px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-group {
        padding-top: 10px;
        padding-bottom: 5px;
    }

    .form-control {
        height: 52px;
        font-size: 16px;
    }

    .btn-login {
        height: 50px;
        font-size: 17px;
    }

    .icon-input {
        position: absolute;
        left: 15px;
        top: 55%;
        transform: translateY(-50%);
        color: gray;
    }

    .input-with-icon {
        padding-left: 45px !important;
    }

    .btn-login {
        background-color: #256343 !important;
        border-color: #256343 !important;
    }

    .btn-login:hover {
        background-color: #1f5237 !important;
        border-color: #1f5237 !important;
    }

    .form-control:focus {
        border-color: #256343 !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 67, 0.25) !important;
    }

    @media (max-width: 768px) {
        .container {
            flex-direction: column !important;
            text-align: center;
            padding-top: 40px;
            padding-bottom: 40px;
        }

        .login-image {
            margin-right: 0 !important;
            margin-bottom: 20px;
            max-width: 60% !important;
        }

        .login-card {
            margin-top: -30px;
            width: 90% !important;
            padding: 25px 22px !important;
        }

        .login-card h3 {
            font-size: 24px !important;
        }

        .login-card p {
            font-size: 12px !important;
        }

        .icon-input {
            top: 60% !important;
        }
    }
</style>



<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-lg-6 d-flex justify-content-center align-items-center mb-4 mb-lg-0">
            <img src="{{ asset('assets/images/login-image.png') }}" class="img-fluid login-image" style="max-width: 80%;">
        </div>
        <div class="login-card bg-white shadow-lg d-flex align-items-center">
            <div class="w-100">
                <h3 class="text-center fw-bold" style="font-size: 32px;">Selamat Datang!</h3>
                <p class="text-center text-muted mb-4" style="font-size: 13px;">Silakan masukan email dan password yang
                    sudah diberikan</p>
                <form method="POST" action="">
                    @csrf
                    <div class="mb-3 position-relative form-group">
                        <i class="icon-input bi bi-person-circle fs-4"></i>
                        <input type="email" name="email" placeholder="Email"
                            class="form-control input-with-icon bg-light" required>

                        @error('email')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-4 position-relative form-group">
                        <i class="icon-input bi bi-lock-fill fs-4"></i>
                        <input type="password" name="password" id="password" placeholder="Password"
                            class="form-control input-with-icon bg-light" required>

                        <button type="button" onclick="togglePassword()"
                            class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-0">
                            <i id="eyeOpen" class="bi bi-eye fs-5 text-muted"></i>
                            <i id="eyeClosed" class="bi bi-eye-slash fs-5 text-muted d-none"></i>
                        </button>
                    </div>
                    @if (session('loginError'))
                    <div class="text-danger mb-2 mt-2 text-center">
                        {{ session('loginError') }}
                    </div>
                    @endif
                    <button class="btn btn-login w-100 fw-semibold text-white">Masuk</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById("password");
            const eyeOpen = document.getElementById("eyeOpen");
            const eyeClosed = document.getElementById("eyeClosed");

            if (input.type === "password") {
                input.type = "text";
                eyeOpen.classList.add("d-none");
                eyeClosed.classList.remove("d-none");
            } else {
                input.type = "password";
                eyeOpen.classList.remove("d-none");
                eyeClosed.classList.add("d-none");
            }
        }
    </script>
</body>

</html>