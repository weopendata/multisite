<?php

namespace Tdt\Multisite\Facades;

use Illuminate\Support\Facades\Redirect as Base;
use Tdt\Multisite\Controllers\BaseController;

class Redirect extends Base
{
    /**
     * Get all of the segments for the request path.
     *
     * @return array
     */
    public static function to(
        $path,
        $status = 302,
        array $headers = array(),
        $secure = null
    ) {

        if (BaseController::$STRIP_SLUG) {

            $root = \Request::root();

            $fullUrl = \Request::fullUrl();

            $segments = str_replace($root, '', $fullUrl);

            $segments = ltrim($segments, '/');

            $pieces = explode('/', $segments);

            $site = array_shift($pieces);

            $path =  $site . '/' . $path;

            return parent::to($path, $status, $headers, $secure);
        }

        return parent::to($path, $status, $headers, $secure);
    }
}
