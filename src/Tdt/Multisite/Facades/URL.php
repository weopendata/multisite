<?php

namespace Tdt\Multisite\Facades;

use Illuminate\Support\Facades\URL as BaseURL;

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

        if (in_array(array_shift($pieces), $assets)) {
            return parent::to($url);
        }

        $pieces = explode('/', \Request::path());

        $site = array_shift($pieces);

        $url = $site . '/' . $url;

        return parent::to($url);
    }
}
