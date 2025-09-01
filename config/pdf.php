<?php

return [
    'mode'                  => 'utf-8',
    'format'                => 'A4',
    'author'                => env('APP_NAME'),
    'subject'               => '',
    'keywords'              => '',
    'creator'               => env('APP_NAME'),
    'display_mode'          => 'fullpage',
    'tempDir'               => base_path('../temp/'),
    'font_path' => storage_path('pdf-fonts/'),
    'font_data' => [
        'almarai' => [
            'R' => 'Almarai-Regular.ttf',    // regular font
            //            'B'  => 'ExampleFont-Bold.ttf',       // optional: bold font
            //            'I'  => 'ExampleFont-Italic.ttf',     // optional: italic font
            //            'BI' => 'ExampleFont-Bold-Italic.ttf' // optional: bold-italic font
            'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
            'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
        ],
        // ...add as many as you want.
    ],
    'useOTL' => 0xFF,
    'useKashida' => 75,
    'pdf_a'                 => false,
    'pdf_a_auto'            => false,
    'icc_profile_path'      => '',
    'defaultCssFile'        => false,
    'pdfWrapper'            => 'misterspelik\LaravelPdf\Wrapper\PdfWrapper',
];
