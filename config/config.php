<?php

use Antley\EloquentAi\EloquentAi;

/*
 * Package Configuration
 */
return [
    // Services
    'config' => [
        'headers' => [
            'open-ai' => [
                'Authorization' => 'Bearer ' . env('ELOQUENT_AI_OPENAI_TOKEN'),
            ],
            'claude-ai' => [
                'anthropic-version' => '2023-06-01',
                'x-api-key' => env('ELOQUENT_AI_CLAUDEAI_TOKEN')
            ],
            'mistral-ai' => [
                'Authorization' => 'Bearer ' . env('ELOQUENT_AI_MISTRALAI_TOKEN'),
            ],
        ],
        'services' => [
            'completions' => [
                'models' => [
                    'open-ai.gpt-3.5-turbo',
                    'open-ai.gpt-4',
                    'mistral-ai.mistral-small-latest',
                    'claude-ai.claude-3-opus-20240229',
                    'claude-ai.claude-3-sonnet-20240229',
                    'claude-ai.claude-3-haiku-20240307'
                ]
            ],
            'audio' => [
                'models' => [
                    'open-ai.tts-1',
                    'open-ai.tts-1-hd'
                ]
            ],
            'image' => [
                'models' => [
                    'open-ai.dall-e-2',
                    'open-ai.dall-e-3'
                ]
            ],
            'transcription' => [
                'models' => [
                    'open-ai.whisper-1'
                ]
            ],
            'embedding' => [
                'models' => [
                    'mistral-ai.mistral-embed',
                    'open-ai.text-embedding-3-small',
                    'open-ai.text-embedding-3-large',
                    'open-ai.text-embedding-ada-002',
                ]
            ],
            'moderate' => [
                'models' => [
                    'open-ai.text-moderation-latest	',
                    'open-ai.text-moderation-stable',
                    'open-ai.text-moderation-007',
                ]
            ]
        ]
    ]
];
