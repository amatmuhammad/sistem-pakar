<!doctype html>
<html lang="id" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Login - Sistem Pakar CBR & CF</title>

    <meta name="description" content="Halaman Login Sistem Pakar CBR & Certainty Factor" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <style>
        body {
            /* 
             * CARA MENAMBAHKAN GAMBAR BACKGROUND:
             * Cukup letakkan file gambar Anda di folder: public/assets/img/layouts/bg-login.jpg
             * Atau ubah nama file pada url('{{ asset("assets/img/layouts/bg-login.jpg") }}') di bawah ini.
             */
            background-image: url('{{ asset("assets/img/layouts/kebun jagung.jpeg") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
        }
        /* Mengubah warna bintik-bintik ornamen latar belakang menjadi kuning */
        .authentication-wrapper.authentication-basic .authentication-inner::before,
        .authentication-wrapper.authentication-basic .authentication-inner::after {
            background: rgb(255, 238, 0) !important;
        }
        .auth-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.12);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.96);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .auth-card:hover {
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.18);
        }
        .brand-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.75rem;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }
        .btn-login {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            border: none;
            font-weight: 600;
            padding: 0.65rem 1.25rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);
            transition: all 0.25s ease;
            color: #fff;
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(16, 185, 129, 0.45);
            background: linear-gradient(135deg, #059669 0%, #065f46 100%);
            color: #fff;
        }
        .btn-login:focus, .btn-login:active {
            background: linear-gradient(135deg, #059669 0%, #065f46 100%) !important;
            box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.4) !important;
            color: #fff !important;
        }
        .text-green-accent {
            color: #059669 !important;
        }
        .form-control:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
        }
        .input-group:focus-within {
            border-color: #10b981;
        }
        .form-check-input:checked {
            background-color: #10b981;
            border-color: #10b981;
        }
        .form-check-input:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
        }
        .input-group-text {
            cursor: pointer;
        }
        .demo-credentials {
            background-color: #ecfdf5;
            border-left: 4px solid #10b981;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
        }
    </style>
</head>

<body>
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner animate__animated animate__fadeInDown">
                <!-- Login Card -->
                <div class="card auth-card">
                    <div class="card-body p-4 p-sm-5">
                        <!-- Logo & Brand Header -->
                        <div class="app-brand justify-content-center mb-4 gap-2">
                            <img src="{{ asset('assets/img/elements/Logo.png') }}" alt="Logo"  style="object-fit: cover; border-radius: 8px; height:100px; width:250px;">
                        </div>
                        <!-- /Logo -->

                        <h4 class="mb-1 text-center fw-bold">Selamat Datang! </h4>
                        <p class="mb-4 text-center text-muted fs-6">Silakan masuk ke akun Anda untuk mengelola sistem pakar.</p>

                        <!-- Flash Message / Alerts -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bx bx-error-circle me-1"></i>
                                @if($errors->count() == 1)
                                    {{ $errors->first() }}
                                @else
                                    <ul class="mb-0 ps-3">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label font-weight-medium">Alamat Email</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                    <input
                                        type="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        id="email"
                                        name="email"
                                        placeholder="Masukkan email Anda"
                                        value="{{ old('email') }}"
                                        autofocus
                                        required />
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Kata Sandi</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                                    <input
                                        type="password"
                                        id="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password"
                                        required />
                                    <span class="input-group-text toggle-password" id="togglePassword" style="cursor: pointer;">
                                        <i class="bx bx-hide" id="toggleIcon"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember-me" name="remember" {{ old('remember') ? 'checked' : '' }} />
                                    <label class="form-check-label" for="remember-me"> Ingat Saya </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-login d-grid w-100" type="submit">
                                    <span class="d-flex align-items-center justify-content-center gap-2">
                                        <i class="bx bx-log-in fs-5"></i> Masuk Sekarang
                                    </span>
                                </button>
                            </div>
                        </form>

                        <div class="divider my-3 text-center position-relative">
                            <span class="px-2 bg-white text-muted small fw-semibold">ATAU</span>
                        </div>

                        <!-- Tombol Akses Guest -->
                        <div class="mb-3">
                            <a href="{{ route('guest.access') }}" class="btn btn-outline-success d-grid w-100 fw-semibold" style="border-width: 2px; border-color: #10b981; color: #047857;">
                                <span class="d-flex align-items-center justify-content-center gap-2">
                                    <i class="bx bx-user-check fs-5"></i> Coba Diagnosa (Akses Tamu / Guest)
                                </span>
                            </a>
                        </div>

                        <!-- Demo Credentials Box -->
                        {{-- <div class="demo-credentials mt-4">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bx bx-info-circle text-green-accent fs-5"></i>
                                <strong class="text-green-accent fs-6">Akun Demo (Default)</strong>
                            </div>
                            <div class="small text-muted">
                                <div><strong>Email:</strong> <code>system@mail.com</code></div>
                                <div><strong>Password:</strong> <code>password</code></div>
                            </div>
                        </div> --}}

                    </div>
                </div>
                <!-- /Login Card -->
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (togglePassword && passwordInput && toggleIcon) {
                togglePassword.addEventListener('click', function () {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    if (type === 'password') {
                        toggleIcon.classList.remove('bx-show');
                        toggleIcon.classList.add('bx-hide');
                    } else {
                        toggleIcon.classList.remove('bx-hide');
                        toggleIcon.classList.add('bx-show');
                    }
                });
            }
        });
    </script>
</body>
</html>
