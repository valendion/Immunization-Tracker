<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    @include('layouts.style')
    @include('layouts.sweetalert2')
    @livewireStyles
</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        {{-- @include('layouts.navbar')
        @include('layouts.sidebar') --}}
        <livewire:navbar />
        <livewire:side-bar />
        {{ $slot }}
        <livewire:footer />
        {{-- @include('layouts.footer') --}}

        @include('layouts.script')
        {{-- <livewire:sweet-alert /> --}}
        @livewireScripts
    </div>

    <script>
        < /html>
