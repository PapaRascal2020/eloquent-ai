<?php

use Antley\EloquentAi\EloquentAi;

/*
 * Package Configuration
 */
return [

    // Providers and supported functions
    'providers' => [
        'OpenAi' => [
            'Completion',  // Chat
            'Audio', // Text To Speech
            'Transcription', // Speech To Text
            'Image' // Image Generation
        ],
        'MinstralAi' => [
            'Completion' // Chat
        ]
    ],

    // This is the default provider that will be used
    'provider' => env('ELOQUENT_AI_PROVIDER', 'OpenAi'),
    'provider_token' => env('ELOQUENT_AI_PROVIDER_TOKEN'),

    // If the provider does not support method called
    // Setting this will allow it to use another provider.

    // If null or not specified it will error.
    'fallback_provider' => env('ELOQUENT_AI_FALLBACK_PROVIDER'),
    'fallback_provider_token' => env('ELOQUENT_AI_FALLBACK_PROVIDER_TOKEN'),
];
