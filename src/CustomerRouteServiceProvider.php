<?php

namespace Samark\CustomRoute;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

/**
 * Class CustomRouteServiceProvider
 * @package App\Providers
 * @author samark@chai
 */
class CustomRouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * @var string
     */
    protected $path = 'routes';

    /**
     * @var array
     */
    protected $routes = array();

    /**
     * @var
     */
    protected $middlewareType;

    /**
     * @var string
     */
    protected $prefix = '';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapWebRoutes();
        $this->mapApiRoutes();
    }

    /**
     * setting mapping route api
     */
    protected function mapApiRoutes()
    {
        $this->middlewareType = 'api';
        $this->path           = 'routes/api';
        $this->namespace      = $this->namespace . '\\Api';
        $this->prefix         = 'api';
        $this->getDirectoryLists();
        $this->registerRoutes();
    }

    /**
     * setting mapping route web
     */
    protected function mapWebRoutes()
    {
        $this->middlewareType = 'web';
        $this->path           = 'routes/web';
        $this->getDirectoryLists();
        $this->registerRoutes();
    }

    /**
     * read file on route directory
     * for mapping route and autoload
     */
    protected function getDirectoryLists()
    {
        foreach (scandir(base_path($this->path)) as $key => $path) {
            $path = ucfirst($path);
            if (is_dir(base_path($this->path) . '/' . $path) && is_file(base_path($this->path) . '/' . $path . '/' . $path . 'Route.php')) {
                $this->routes[$key]['path']      = $this->path . '/' . ucfirst($path) . '/' . ucfirst($path) . 'Route.php';
                $this->routes[$key]['namespace'] = $this->namespace;
            }
        }
    }

    /**
     * register routes
     */
    protected function registerRoutes()
    {
        foreach ($this->routes as $key => $route) {
            Route::middleware($this->middlewareType)
                ->namespace($route['namespace'])
                ->prefix($this->prefix)
                ->group(base_path($route['path']));
        }
    }


}
