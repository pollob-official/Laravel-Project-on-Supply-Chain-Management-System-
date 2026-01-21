<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Register | Supply Chain Management App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully responsive admin theme" name="description" />
    <meta content="Techzaa" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Icons css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
</head>

<body class="authentication-bg">

<div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-8 col-lg-10">
                <div class="card overflow-hidden bg-opacity-25">
                    <div class="row g-0">

                        <!-- LEFT IMAGE -->
                        <div class="col-lg-6 d-none d-lg-block p-2">
                            <img src="{{ asset('assets/images/auth-img.jpg') }}"
                                 class="img-fluid rounded h-100" alt="">
                        </div>

                        <!-- RIGHT CONTENT -->
                        <div class="col-lg-6">
                            <div class="d-flex flex-column h-100">

                                <!-- LOGO -->
                                <div class="auth-brand p-3" style="text-align: center;">
                                        <img src="{{ asset('assets/images/logo.png') }}" style="height:65px;width:250px;">
                                </div>

                                <div class="p-3 my-auto" style="color: steelblue">
                                    <h4 class="fs-20">Free Sign Up</h4>
                                    <p class="text-muted mb-3">
                                        Enter your details to create your account.
                                    </p>

                                    <!-- LARAVEL REGISTER FORM -->
                                    <form method="POST" action="{{ route('register') }}">
                                        @csrf

                                        <div class="mb-3">
                                            <label class="form-label">Full Name</label>
                                            <input type="text"
                                                   name="name"
                                                   value="{{ old('name') }}"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   placeholder="Enter your name"
                                                   required autofocus>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Email address</label>
                                            <input type="email"
                                                   name="email"
                                                   value="{{ old('email') }}"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   placeholder="Enter your email"
                                                   required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <input type="password"
                                                   name="password"
                                                   class="form-control @error('password') is-invalid @enderror"
                                                   placeholder="Enter your password"
                                                   required>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Confirm Password</label>
                                            <input type="password"
                                                   name="password_confirmation"
                                                   class="form-control"
                                                   placeholder="Confirm password"
                                                   required>
                                        </div>

                                        <div class="mb-0 d-grid">
                                            <button class="btn btn-primary fw-semibold" type="submit">
                                                Sign Up
                                            </button>
                                        </div>
                                    </form>
                                    <!-- END FORM -->

                                    <!-- LOGIN LINK -->
                                        <div class="row">
                                            <div class="col-12 text-center" style="margin-top: 15px;">
                                                <p class="text-dark-emphasis">
                                                    Already have an account?
                                                    <a href="{{ route('login') }}"
                                                    class="text-dark fw-bold ms-1 text-decoration-underline">
                                                        Log In
                                                    </a>
                                                </p>
                                            </div>
                                        </div>

                                    <!-- SOCIAL SIGN IN -->
                                        <div class="text-center mt-2">
                                            <p class="text-muted fs-16">Sign up with</p>
                                            <div class="d-flex gap-2 justify-content-center mt-3">
                                                <a href="javascript:void(0);" class="btn btn-soft-primary">
                                                    <i class="ri-facebook-circle-fill"></i>
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-soft-danger">
                                                    <i class="ri-google-fill"></i>
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-soft-info">
                                                    <i class="ri-twitter-fill"></i>
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-soft-dark">
                                                    <i class="ri-github-fill"></i>
                                                </a>
                                            </div>
                                        </div>


                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="footer footer-alt fw-medium">
    <span class="text-dark-emphasis">
        <script>document.write(new Date().getFullYear())</script>
        © Velonic - Theme by Techzaa
    </span>
</footer>

<!-- Vendor js -->
<script src="{{ asset('assets/js/vendor.min.js') }}"></script>

<!-- App js -->
<script src="{{ asset('assets/js/app.min.js') }}"></script>

</body>
</html>
