<?php

return [
    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked when loading your views. Of
    | course, the usual Laravel view path has already been registered for
    | you.
    |
    */

    'paths' => [
        resource_path('views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled Path
    |--------------------------------------------------------------------------
    |
    | This option indicates where all of the compiled Blade templates will
    | be stored for your application. Typically, this is within the storage
    | directory. However, as usual, you are free to change this value.
    |
    */

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views'))
    ),

    /*
    |--------------------------------------------------------------------------
    | Namespaces
    |--------------------------------------------------------------------------
    |
    | Here you can register custom view namespaces to organize and group
    | your views the way you want. This gives you the ability to use
    | views like "package::index" instead of "packagename::views.index".
    |
    */

    'namespaces' => [
        'mail' => resource_path('views/mail'),
    ],

];
