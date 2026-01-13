<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Abjadia Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        window.auth_user = @json($authUser);
    </script>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/dashboard.js'])
</head>

<body class="font-sans antialiased bg-gray-100">
    <div id="app"></div>
</body>

</html>