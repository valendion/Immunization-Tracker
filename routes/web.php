<?php

use Illuminate\Support\Facades\Route;

Route::livewire('login', 'pages::login')
    ->name('login');

Route::middleware(['role:admin'])->group(function () {
    Route::livewire('/', 'pages::dashboard')->name('dashboard');

    Route::livewire('/admin/vaccine', 'pages::admin.vaccine')
        ->name('admin.vaccine.index');
    Route::livewire('/admin/vaccine/create', 'pages::admin.vaccine.create')
        ->name('admin.vaccine.create');
    Route::livewire('/admin/vaccine/{id}/edit', 'pages::admin.vaccine.edit')
        ->name('admin.vaccine.edit');

    Route::livewire('/admin/facility', 'pages::admin.facility')
        ->name('admin.facility.index');
    Route::livewire('/admin/facility/create', 'pages::admin.facility.create')
        ->name('admin.facility.create');
    Route::livewire('admin/facility/{id}/edit', 'pages::admin.facility.edit')
        ->name('admin.facility.edit');

    Route::livewire('/admin/child', 'pages::admin.child')
        ->name('admin.child.index');
    Route::livewire('/admin/child/create', 'pages::admin.child.create')
        ->name('admin.child.create');
    Route::livewire('admin/child/{id}/edit', 'pages::admin.child.edit')
        ->name('admin.child.edit');
    Route::livewire('admin/child/{id}/read', 'pages::admin.child.read')
        ->name('admin.child.read');

    Route::livewire('/admin/immunization-record', 'pages::admin.immunization-record')
        ->name('admin.immunization-record.index');
    Route::livewire('/admin/immunization-record/create', 'pages::admin.immunization-record.create')
        ->name('admin.immunization-record.create');
    Route::livewire('admin/immunization-record/{id}/edit', 'pages::admin.immunization-record.edit')
        ->name('admin.immunization-record.edit');
    Route::livewire('/immunization-record-view', 'pages::immunization-record-view')
        ->name('immunization-record-view');
});
