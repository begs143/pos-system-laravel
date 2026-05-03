<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Signin - Inventory Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">



    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets/site.webmanifest') }}">


    <script type="module" crossorigin src="{{ asset('assets/js/main.js') }}"></script>
    <link rel="stylesheet" crossorigin href="{{ asset('assets/css/main.css') }}">
</head>

<body>

    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="card " style="max-width:420px; width:100%;">
            <div class="card-body p-5">
                <div class="text-center mb-3">
                    <a href="index.html" class="mb-4 d-inline-block"><img
                            src="{{ asset('assets/images/logo.png') }}"
                            alt="" width="50">

                    </a>
                    <h1 class="card-title mb-5 h5">Sign in to your account</h1>

                </div>
                <form class="needs-validation mt-3" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input id="username" name="username" type="text"
                            class="form-control @error('username') is-invalid @enderror"
                            placeholder="Enter your username" value="{{ old('username') }}" required autofocus>

                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label d-flex justify-content-between">
                            <span>Password</span>
                            <a href="{{ route('password.request') }}" class="small link-primary">Forgot Password?</a>
                        </label>

                        <input id="password" name="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="Password" required
                            minlength="6">

                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">Please provide a password (min 6 characters).</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input id="remember" name="remember" class="form-check-input" type="checkbox"
                                {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label small" for="remember">
                                Remember me
                            </label>
                        </div>
                    </div>

                    <button class="btn btn-primary w-100" type="submit">Sign in</button>
                </form>

                <div class="text-center mt-3 small text-muted">
                    Don't have an account? <a href="{{ route('register') }}" class="link-primary">Sign up</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
