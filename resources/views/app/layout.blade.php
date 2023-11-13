<!DOCTYPE html>
<html lang="{{ config('app.locale', 'en') }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel')  }}</title>
    @include('app.favicon')
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    @inertiaHead
</head>
<body class="antialiased scrollbar text-primary-800 bg-white dark:bg-primary-900 dark:text-primary-200">
@inertia
</body>
</html>
