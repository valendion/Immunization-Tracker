<?php
namespace App\Constants;

class AppConstants
{
    // Contoh string global
    public const DATA_VACCINE  = 'DATA VACCINE';
    public const DATA_FACILITY = 'DATA FACILITY';
    public const DATA_CHILD    = 'DATA CHILD';

    public const DATA_IMMUNIZATION_RECORD      = 'DATA IMMUNIZATION RECORD';
    public const DATA_IMMUNIZATION_RECORD_VIEW = 'DATA IMMUNIZATION RECORD VIEW';
    public const DASHBOARD                     = 'DASHBOARD';
    public const TOTAL_IMMUNIZATION_RECORD     = 'TOTAL IMMUNIZATION RECORD';

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

    public const VACCINES_DATA_LIST = [
        'BCG'   => ['name' => 'BCG', 'like' => 'BCG%', 'color' => 'dark', 'icon' => 'syringe'],
        'POLIO' => ['name' => 'POLIO', 'like' => 'Polio%', 'color' => 'primary', 'icon' => 'syringe'],
        'DPT'   => ['name' => 'DPT', 'like' => 'DPT%', 'color' => 'secondary', 'icon' => 'syringe'],
        'PCV'   => ['name' => 'PCV', 'like' => 'PCV%', 'color' => 'success', 'icon' => 'syringe'],
        'ROTA'  => ['name' => 'ROTA', 'like' => 'Rotavirus%', 'color' => 'danger', 'icon' => 'syringe'],
        'MR'    => ['name' => 'CAMPAK/MR', 'like' => 'Measles-Rubella%', 'color' => 'warning', 'icon' => 'syringe'],
    ];

}
