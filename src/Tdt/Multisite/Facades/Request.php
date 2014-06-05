<?php

namespace Tdt\Multisite\Facades;

use Illuminate\Support\Facades\Request as BaseRequest;

class Request extends BaseRequest
{

    /**
     * Get all of the segments for the request path.
     *
     * @return array
     */
    public static function segment($index)
    {
        return parent::segment($index + 1);
    }
}
