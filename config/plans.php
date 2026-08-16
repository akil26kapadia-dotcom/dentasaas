<?php

return [

    'free' => [
        'patients' => 25,
        'appointments' => 50,
        'invoices' => 10,
        'doctors' => 1,
        'pdf' => false,
        'prescriptions' => false,
        'analytics' => false,
    ],

    'basic' => [
        'patients' => 100,
        'appointments' => 200,
        'invoices' => 50,
        'doctors' => 2,
        'pdf' => true,
        'prescriptions' => true,
        'analytics' => 'basic',
    ],

    'premium' => [
        'patients' => 500,
        'appointments' => 1000,
        'invoices' => 200,
        'doctors' => 5,
        'pdf' => true,
        'prescriptions' => true,
        'analytics' => 'full',
    ],

    'deluxe' => [
        'patients' => -1,
        'appointments' => -1,
        'invoices' => -1,
        'doctors' => -1,
        'pdf' => true,
        'prescriptions' => true,
        'analytics' => 'full',
    ],

];
