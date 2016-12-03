<?php

return [
    'rules' => [
        'bio'                => 'max:500',
        'language_key'       => 'exists:languages,key',
        'country'            => 'exists:countries,key',
        'city'               => 'max:255',
        'gender'             => 'in:male,female',
        'email_notification' => 'boolean',
        'private'            => 'boolean',
    ],

    // This properties only available for owner
    'owner' => [
        'email_notification',
    ],
];