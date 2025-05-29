<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    |
    | Configuración para el servicio de almacenamiento de imágenes Cloudinary.
    |
    */

    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
    'api_key' => env('CLOUDINARY_API_KEY'),
    'api_secret' => env('CLOUDINARY_API_SECRET'),
    'url' => env('CLOUDINARY_URL'),

    /*
    |--------------------------------------------------------------------------
    | Upload Settings
    |--------------------------------------------------------------------------
    |
    | Configuración por defecto para las subidas de archivos.
    |
    */

    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),
    'secure' => true,
    
    /*
    |--------------------------------------------------------------------------
    | Default Transformations
    |--------------------------------------------------------------------------
    |
    | Transformaciones por defecto aplicadas a las imágenes.
    |
    */

    'transformations' => [
        'quality' => 'auto:good',
        'fetch_format' => 'auto',
    ],

    /*
    |--------------------------------------------------------------------------
    | Folders
    |--------------------------------------------------------------------------
    |
    | Configuración de carpetas para organizar las imágenes.
    |
    */

    'folders' => [
        'posts' => [
            'main' => 'beerfinder/posts/main',
            'additional' => 'beerfinder/posts/additional',
        ],
        'users' => 'beerfinder/users',
        'beers' => 'beerfinder/beers',
        'locations' => 'beerfinder/locations',
    ],
];
