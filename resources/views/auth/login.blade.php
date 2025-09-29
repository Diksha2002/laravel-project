<x-guest-layout>
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-wrapper {
            max-width: 420px;
            margin: 3rem auto;
            padding: 2.5rem;
            background-color: #f9fafb;
            border-radius: 0.75rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
        }

        .login-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        input[type="email"],
        input[type="password"] {
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.95rem;
            background-color: #ffffff;
            width: 100%;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .primary-btn {
            display: inline-block;
            background-color: #4f46e5;
            color: white;
            padding: 0.75rem 1.25rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            width: 100%;
        }

        .primary-btn:hover {
            background-color: #4338ca;
            box-shadow: 0 4px 12px rgba(67, 56, 202, 0.3);
        }

        .form-footer-text {
            text-align: center;
            margin-top: 1.75rem;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .form-footer-text a {
            color: #4f46e5;
            text-decoration: underline;
            margin-left: 0.25rem;
        }

        .form-footer-text a:hover {
            color: #3730a3;
        }
    </style>

    <div class="login-wrapper">
        <h2 class="login-title">Sign in to your account</h2>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="form-group">
                <x-input-label for="email" :value="__('Email address')" />
                <x-text-input id="email" class="block mt-1"
                              type="email" name="email"
                              :value="old('email')" required
                              autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="form-group">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1"
                              type="password" name="password"
                              required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me + Forgot Password -->
            <div class="remember-forgot">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                           name="remember">
                    <span class="ms-2 text-sm text-gray-600">Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-indigo-600 hover:text-indigo-800 underline"
                       href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" class="primary-btn">
                    Log in
                </button>
            </div>

            <!-- Register Link -->
            <p class="form-footer-text">
                New here?
                <a href="{{ route('register') }}">Create an account</a>
            </p>
        </form>
    </div>
</x-guest-layout>
