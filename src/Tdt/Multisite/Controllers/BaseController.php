<?php

namespace Tdt\Multisite\Controllers;

class BaseController extends \Controller
{

    public static $STRIP_SLUG;

    public function handle($url)
    {
        // Split the uri to check for an (optional) extension (=format)
        preg_match('/([^\.]*)(?:\.(.*))?$/', $url, $matches);

        $identifier = $matches[0];

        // Multi-site is only allowed thus far at 1 'uri-identifier'
        // meaning /multi-site-identifier/rest/goes/here
        if (!empty($identifier)) {

            // Check if the domain is mapped onto a site slug
            $site = $this->checkForDomain();

            if (!empty($site)) {
                self::$STRIP_SLUG = false;

                // Fetch the configuration for the site
                $site_config = $site->toArray();

                // Swap the database configuration
                $this->switchDatabase($site_config);

                $base_controller = new \Tdt\Core\BaseController();

                $response = $base_controller->handleRequest($identifier);

                $view = $response->getOriginalContent();

                return $response;
            } else {
                // Try to find the site identifier and look for a match
                $pieces = explode('/', $identifier);

                $site = array_shift($pieces);

                if (empty($site)) {
                    $this->showHomePage();
                } else {
                    self::$STRIP_SLUG = true;

                    // Fetch the configuration for the site
                    $site_config = $this->getSite($site);

                    // Swap the database configuration
                    $this->switchDatabase($site_config);

                    $resource_identifier = implode('/', $pieces);

                    $base_controller = new \Tdt\Core\BaseController();

                    return $base_controller->handleRequest($resource_identifier);
                }
            }
        } else {
            $this->showHomePage();
        }
    }

    /**
     * Handle api requests for a specific site
     *
     * @param string $site The name of the site
     * @param string $api  The api requests
     *
     * @return \Response
     */
    public function api($site, $api = null)
    {
        $http_method = strtolower(\Request::getMethod());

        $site = ltrim($site, '/');

        $site_config = $this->getSite($site);

        $this->switchDatabase($site_config);

        if (empty($api)) {
            $api_aspect = 'datasets';
        } else {
            $api = ltrim($api, '/');

            // API exists out of two parts, the aspect part (dataset, user, ...)
            // and the action part

            $api_pieces = explode('/', $api);

            $api_aspect = array_shift($api_pieces);

            $api_method = array_shift($api_pieces);

            $argument = array_shift($api_pieces);
        }

        if (empty($api_method)) {
            $api_method = 'index';
        }

        $function = '';

        switch($api_aspect) {
            case 'login':
                $controller = \App::make('Tdt\\Core\\Ui\\AuthController');

                $function = $http_method . 'Login';

                if (!empty($argument)) {
                    return $controller->$function($argument);
                }

                return $controller->$function();
                break;
            case 'logout':
                $controller = \App::make('Tdt\\Core\\Ui\\AuthController');

                $function = $http_method . ucfirst($api_aspect);
                break;
            case 'datasets':
                $controller = \App::make('Tdt\\Core\\Ui\\DatasetController');

                $function = $http_method . ucfirst($api_method);

                break;
            case 'users':
                $controller = \App::make('Tdt\\Core\\Ui\\UserController');

                $function = $http_method . ucfirst($api_method);

                break;
            case 'groups':
                $controller = \App::make('Tdt\\Core\\Ui\\GroupController');

                $function = $http_method . ucfirst($api_method);
                break;
        }

        if (!empty($argument)) {
            return $controller->$function($argument);
        }

        return $controller->$function();
    }

    /**
     * Check if the domain is mapped onto a site slug
     *
     * @return null|array MultiSite
     */
    private function checkForDomain()
    {
        $domain = \Request::root();

        // Remove the http:// and https:// scheme's
        $domain = str_replace('http://', '', $domain);
        $domain = str_replace('https://', '', $domain);

        return \MultiSite::where('domain', '=', $domain)->first();
    }

    /**
     * Switch to another database based on the configuration of the site
     *
     * @param array $site_config
     */
    private function switchDatabase($site_config)
    {
        \Config::set('database.connections', array($site_config['sitename'] => $site_config));
        \Config::set('database.default', $site_config['sitename']);
    }

    private function showHomePage()
    {
        \App::abort(404, "No existing multisite has been given, or no resource in that site can be found.");
    }

    /**
     * Get the site configuration, if it doesn't exist, abort
     *
     * @param string $site The name of the site
     */
    private function getSite($site)
    {
        // Fetch the configuration for the site
        $site = \MultiSite::where('sitename', '=', $site)->first();

        if (!empty($site)) {

            return $site->toArray();

        } else {
            $this->showHomePage();
        }
    }
}
