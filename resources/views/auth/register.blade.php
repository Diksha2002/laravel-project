<x-guest-layout>
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .register-wrapper {
            max-width: 480px;
            margin: 3rem auto;
            padding: 2.5rem;
            background-color: #f9fafb;
            border-radius: 0.75rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
        }

        .register-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            color: #374151;
        }

        input[type="text"],
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

        input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }

        .error-message {
            color: #dc2626;
            font-size: 0.85rem;
            margin-top: 0.25rem;
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
    </style>

    <div class="register-wrapper">
        <h2 class="register-title">Create Your Account</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Shop Name -->
            <div class="form-group">
                <label for="shop_name">Shop Name</label>
                <input id="shop_name" name="shop_name" type="text" value="{{ old('shop_name') }}" required autofocus />
                @error('shop_name') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <!-- Name -->
            <div class="form-group">
                <label for="name">Full Name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required />
                @error('name') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required />
                @error('email') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required autocomplete="new-password" />
                @error('password') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required />
            </div>

            <div class="form-group">
                <button type="submit" class="primary-btn">Register</button>
            </div>
        </form>
    </div>
</x-guest-layout>
