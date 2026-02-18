<?php
namespace App\Constants;

class AppConstants
{
    // Contoh string global
    public const DATA_VACCINE  = 'DATA VACCINE';
    public const DATA_FACILITY = 'DATA FACILITY';
    public const DATA_CHILD    = 'DATA CHILD';

    public const DASHBOARD = 'DASHBOARD';

    public const CREATE = 'CREATE';

    public const UPDATE = 'UPDATE';

    public const DELETE = 'DELETE';

    // Gender Options
    const GENDERS = [
        'male'   => 'Male',
        'female' => 'Female',
    ];

    // Pagination Options
    const PAGINATIONS = [
        10  => 10,
        25  => 25,
        50  => 50,
        100 => 100,
    ];

}
