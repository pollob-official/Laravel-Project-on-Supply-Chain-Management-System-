@extends('layouts.app')

@section('content')
<div class="authentication-bg position-relative">
    <div class="account-pages pt-5 pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-8 col-lg-10">
                    <div class="card overflow-hidden">
                        <div class="row g-0">

                            <!-- LEFT IMAGE -->
                            <div class="col-lg-6 d-none d-lg-block p-2">
                                <img src="{{ asset('assets/images/auth-img.jpg') }}"
                                     class="img-fluid rounded h-100" alt="">
                            </div>

                            <!-- RIGHT SIDE -->
                            <div class="col-lg-6">
                                <div class="d-flex flex-column h-100">

                                    <!-- LOGO -->
                                    <div class="auth-brand p-4 text-center">
                                        <img src="{{ asset('assets/images/logo.png') }}"
                                             style="height:80px;width:200px;">
                                    </div>

                                    <div class="p-4 my-auto">

                                        <!-- LOGIN FORM -->
                                        <div id="loginBox">
                                            <h4 class="fs-20">Sign In</h4>
                                            <p class="text-muted mb-4">
                                                Enter your email and password to access your account.
                                            </p>

                                            <form method="POST" action="{{ route('login') }}">
                                                @csrf

                                                <div class="mb-3">
                                                    <label>Email</label>
                                                    <input type="email" name="email"
                                                           class="form-control" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Password</label>
                                                    <input type="password" name="password"
                                                           class="form-control" required>
                                                </div>

                                                <div class="mb-3 d-flex justify-content-between">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" name="remember">
                                                        <label class="form-check-label">Remember me</label>
                                                    </div>
                                                    <a href="{{ route('password.request') }}"
                                                       class="text-muted fs-13">
                                                        Forgot password?
                                                    </a>
                                                </div>

                                                <button class="btn btn-soft-primary w-100">
                                                    <i class="ri-login-circle-fill me-1"></i> Log In
                                                </button>
                                            </form>

                                            <p class="text-center mt-4">
                                                Don’t have an account?
                                                <a href="javascript:void(0)" onclick="showRegister()"
                                                   class="fw-bold text-decoration-underline">
                                                    Sign up
                                                </a>
                                            </p>
                                        </div>

                                        <!-- REGISTER FORM -->
                                        <div id="registerBox" style="display:none;">
                                            <h4 class="fs-20">Create Account</h4>
                                            <p class="text-muted mb-4">
                                                Create your account in less than a minute.
                                            </p>

                                            <form method="POST" action="{{ route('register') }}">
                                                @csrf

                                                <div class="mb-3">
                                                    <label>Name</label>
                                                    <input type="text" name="name"
                                                           class="form-control" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Email</label>
                                                    <input type="email" name="email"
                                                           class="form-control" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Password</label>
                                                    <input type="password" name="password"
                                                           class="form-control" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Confirm Password</label>
                                                    <input type="password" name="password_confirmation"
                                                           class="form-control" required>
                                                </div>

                                                <button class="btn btn-soft-success w-100">
                                                    <i class="ri-user-add-fill me-1"></i> Register
                                                </button>
                                            </form>

                                            <p class="text-center mt-4">
                                                Already have an account?
                                                <a href="javascript:void(0)" onclick="showLogin()"
                                                   class="fw-bold text-decoration-underline">
                                                    Sign in
                                                </a>
                                            </p>
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

<!-- SIMPLE TOGGLE SCRIPT -->
<script>
    function showRegister() {
        document.getElementById('loginBox').style.display = 'none';
        document.getElementById('registerBox').style.display = 'block';
    }

    function showLogin() {
        document.getElementById('registerBox').style.display = 'none';
        document.getElementById('loginBox').style.display = 'block';
    }
</script>
@endsection
