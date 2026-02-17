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

Route::livewire('/superadmin/facility', 'pages::superadmin.facility')
    ->name('superadmin.facility.index');
Route::livewire('/superadmin/facility/create', 'pages::superadmin.facility.create')
    ->name('superadmin.facility.create');
Route::livewire('/superadmin/facility/{id}/edit', 'pages::superadmin.facility.edit')
    ->name('superadmin.facility.edit');
