<?php


/**
 * Hijack all of the routes
 */

Route::any('{site}/api/admin{api}', 'Tdt\Multisite\Controllers\BaseController@api')

->where(array('url' => '[^\/]+', 'api' => '.*'));

Route::any('{full_url}', 'Tdt\Multisite\Controllers\BaseController@handle')

->where('full_url', '.*');
