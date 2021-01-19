<?php namespace Blok\JavaScript;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class JavaScriptServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function register()
    {
        $this->app->singleton('JavaScript', function ($app) {
            return new JavaScript(config('javascript.namespace'));
        });
    }

    public function boot()
    {
        require_once __DIR__ . '/helpers.php';

        $this->publishes([
            __DIR__ . '/config/javascript.php' => config_path('javascript.php'),
        ], 'config');

        /**
         * @example
         * @javascript($namespace, (optional) $array)
         */
        Blade::directive('javascript', function ($expression) {
            return "<?php echo JavaScript::render({$expression}); ?>";
        });
    }
}
