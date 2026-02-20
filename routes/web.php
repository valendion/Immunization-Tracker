<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', function () {
//     return view('welcome');
// });

Route::livewire('/', 'pages::dashboard')->name('dashboard');

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
Route::livewire('superadmin/facility/{id}/edit', 'pages::superadmin.facility.edit')
    ->name('superadmin.facility.edit');

Route::livewire('/superadmin/child', 'pages::superadmin.child')
    ->name('superadmin.child.index');
Route::livewire('/superadmin/child/create', 'pages::superadmin.child.create')
    ->name('superadmin.child.create');
Route::livewire('superadmin/child/{id}/edit', 'pages::superadmin.child.edit')
    ->name('superadmin.child.edit');

Route::livewire('/superadmin/immunization-record', 'pages::superadmin.immunization-record')
    ->name('superadmin.immunization-record.index');
Route::livewire('/superadmin/immunization-record/create', 'pages::superadmin.immunization-record.create')
    ->name('superadmin.immunization-record.create');
Route::livewire('superadmin/immunization-record/{id}/edit', 'pages::superadmin.immunization-record.edit')
    ->name('superadmin.immunization-record.edit');
