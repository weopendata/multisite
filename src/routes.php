<?php


/**
 * Hijack all of the routes
 */

Route::any('{site}/api/admin', function () {
    return Redirect::to('api/admin/datasets');
})
->where('site', '[^\/]+');

Route::any('{site}/api/admin/{api}', 'Tdt\Multisite\Controllers\BaseController@api')

->where(array('site' => '[^\/]+', 'api' => '.*'));

Route::any('{full_url}', 'Tdt\Multisite\Controllers\BaseController@handle')

->where('full_url', '.*');
