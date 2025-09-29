<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Welcome</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            color: #333;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        p {
            font-size: 1.2rem;
        }

        a {
            text-decoration: none;
            color: #007BFF;
            margin: 0 10px;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Welcome to Multi-Tenant Inventory</h1>
    <p>
        <a href="{{ route('login') }}">Login</a> | 
        <a href="{{ route('register') }}">Register</a>
    </p>
</body>
</html>
