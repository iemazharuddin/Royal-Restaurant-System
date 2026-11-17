<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Royal Restaurant') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background:
                linear-gradient(rgba(0, 0, 0, 0.72), rgba(0, 0, 0, 0.82)),
                url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=2000&q=85');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #fff;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 8px;
        }

        .auth-container {
            width: 100%;
            max-width: 225px;
            margin: auto;
        }

        .auth-card {
            width: 100%;
            padding: 9px 11px;
            border-radius: 10px;
            background: rgba(15, 15, 15, 0.94);
            border: 1px solid rgba(212, 175, 55, 0.5);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(12px);
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 8px;
        }

        .auth-logo i {
            color: #d4af37;
            font-size: 15px;
            margin-bottom: 2px;
        }

        .auth-logo h1 {
            color: #d4af37;
            font-family: 'Playfair Display', serif;
            font-size: 15px;
            font-weight: 700;
            margin: 0;
        }

        .auth-logo p {
            color: #aaa;
            font-size: 8px;
            margin-top: 1px;
        }

        .auth-card h2 {
            color: #fff;
            font-family: 'Playfair Display', serif;
        }

        .auth-card label {
            color: #ddd;
            font-size: 9px;
        }

        .auth-card input:not([type="checkbox"]) {
            width: 100%;
            background: #202020;
            color: #fff;
            border: 1px solid #444;
            border-radius: 5px;
            padding: 5px 8px;
            font-size: 10px;
        }

        .auth-card input:focus {
            outline: none;
            border-color: #d4af37;
            box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.12);
        }

        .auth-card button[type="submit"] {
            width: 100%;
            background: #d4af37;
            color: #111;
            border: none;
            border-radius: 5px;
            padding: 6px;
            font-size: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s;
        }

        .auth-card button[type="submit"]:hover {
            background: #f0cc58;
        }

        .auth-card a {
            color: #d4af37;
            text-decoration: none;
        }

        .auth-card a:hover {
            color: #f5d76e;
        }

        .google-button {
            min-height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            width: 100%;
            padding: 4px;
            border: 1px solid #555;
            border-radius: 5px;
            color: #fff !important;
            font-size: 10px;
            background: #222;
        }

        .google-button:hover {
            background: #303030;
        }

        .auth-footer {
            text-align: center;
            margin-top: 8px;
            color: #999;
            font-size: 8px;
        }

        @media (max-width: 480px) {
            .auth-container {
                max-width: 225px;
            }

            .auth-card {
                padding: 9px 11px;
            }

            .auth-wrapper {
                padding: 10px 8px;
            }
        }
    </style>
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-logo">
                    <i class="fas fa-crown"></i>
                    <h1>Royal Restaurant</h1>
                    <p>Fine dining, made with love</p>
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>