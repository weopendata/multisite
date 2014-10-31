<?php

namespace Tdt\Multisite\Facades;

use Illuminate\Support\Facades\Request as BaseRequest;
use Tdt\Multisite\Controllers\BaseController;

class Request extends BaseRequest
{

    /**
     * Get all of the segments for the request path.
     *
     * @return array
     */
    public static function segment($index)
    {
        if (BaseController::$STRIP_SLUG) {
            return parent::segment($index + 1);
        }

        return parent::segment($index);
    }

    public static function path()
    {
        if (BaseController::$STRIP_SLUG) {

            $pieces = explode('/', parent::path());

            array_shift($pieces);

            return implode('/', $pieces);
        }

        return parent::path();
    }
}
