<?php

namespace Tdt\Multisite\Facades;

use Illuminate\Support\Facades\URL as BaseURL;
use Tdt\Multisite\Controllers\BaseController;

class URL extends BaseURL
{

    /**
     * Create an adjusted link to a URL taken the site name into consideration
     *
     * @return array
     */
    public static function to($url)
    {
        // Don't redirect when assets are called
        $pieces = explode('/', $url);

        $assets = array('img', 'fonts', 'js', 'packages', 'css');

        if (in_array(array_shift($pieces), $assets) || !BaseController::$STRIP_SLUG) {
            return parent::to($url);
        }

        $root = \Request::root();

        $fullUrl = \Request::fullUrl();

        $segments = str_replace($root, '', $fullUrl);

        $segments = ltrim($segments, '/');

        $pieces = explode('/', $segments);

        $site = array_shift($pieces);

        $url = $site . '/' . $url;

        return parent::to($url);
    }
}
