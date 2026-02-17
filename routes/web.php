<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', function () {
//     return view('welcome');
// });

Route::livewire('/', 'pages::dashboard')->name('/');

Route::livewire('/superadmin/vaccine', 'pages::superadmin.vaccine')
    ->name('superadmin.vaccine.index');
Route::livewire('/superadmin/vaccine/create', 'pages::superadmin.vaccine.create')
    ->name('superadmin.vaccine.create');
Route::livewire('/superadmin/vaccine/{id}/edit', 'pages::superadmin.vaccine.edit')
    ->name('superadmin.vaccine.edit');
