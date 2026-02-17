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

    <div class="wrapper">

        <livewire:navbar />
        <livewire:side-bar />
        {{ $slot }}
        <livewire:footer />


        @include('layouts.script')

        @livewireScripts
    </div>

    <script>
        < /html>
