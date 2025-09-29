<?php
return [
    'defaults' => [
        'routes' => [
            'api' => 'api',
            'docs' => 'docs',
        ],
    ],

    'paths' => [
        'docs' => storage_path('api-docs'),
        'docs_json' => storage_path('api-docs/swagger.json'),
    ],

    'securityDefinitions' => [
        'api_key' => [
            'type' => 'apiKey',
            'in' => 'header',
            'name' => 'Authorization',
        ]
    ],

    'info' => [
        'title' => 'My API Documentation',
        'description' => 'This is the API documentation for my app.',
        'version' => '1.0.0',
    ],
];
