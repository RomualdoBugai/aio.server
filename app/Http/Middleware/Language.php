<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;

class Language {


    protected $except = [
        "/api/*",
    ];

    protected $app;

    protected $redirector;

    protected $request;

    public function __construct(Application $app, Redirector $redirector, Request $request)
    {
        $this->app = $app;
        $this->redirector = $redirector;
        $this->request = $request;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = $request->segment(1);
        \App::setLocale($locale);

        if ($locale != "api") {

            if ( !array_key_exists($locale, $this->app->config->get('app.locales'))) {
                $segments       = $request->segments();
                $segments[0]    = $this->app->config->get('app.fallback_locale');
                return $this->redirector->to(implode('/', $segments));
            }

            $this->app->setLocale($locale);
            \Lang::setFallback($locale);
        }

        return $next($request);
    }

}
