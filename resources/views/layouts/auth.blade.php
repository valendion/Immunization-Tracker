<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Login' }}</title>

    @include('layouts.style')
    {{-- @include('layouts.sweetalert2') --}}
    @livewireStyles
</head>

<body class="hold-transition login-page">

    {{ $slot }}

    @include('layouts.script')
    @livewireScripts

</body>

</html>
