<?php namespace Blok\JavaScript;

use Illuminate\Support\Arr;

/**
 * Class JavaScript
 *
 * @package Blok\JavaScript
 *
 * @see https://github.com/cretueusebiu/laravel-javascript
 * @see https://github.com/laracasts/PHP-Vars-To-Js-Transformer
 */
class JavaScript
{
    /**
     * @var string
     */
    private static $globalNamespace = 'JavaScript';

    /**
     * Globals namespace
     * @var string
     */
    private $namespace = '__app';

    private static $_instances = [];

    /**
     * Selected data
     * @var null
     */
    private $activeData = null;

    /**
     * JavaScript constructor.
     * @param null $namespace
     *
     * @example
     * $js = new JavaScript('__globals');
     * $js->set('foo', 'bar');
     */
    public function __construct($namespace = null)
    {
        if (!is_string($namespace)) {
            $namespace = null;
        }

        if ($namespace) {
            $this->namespace = $namespace;
        }

        if (!isset($GLOBALS[static::$globalNamespace])) {
            $GLOBALS[static::$globalNamespace] = [];
        }

        if (!isset($GLOBALS[static::$globalNamespace][$this->namespace])) {
            $GLOBALS[static::$globalNamespace][$this->namespace] = [];
        }

        self::$_instances[$this->namespace] = $this;
    }

    /**
     * Calling methods statically
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \Exception
     */
    public static function __callStatic($name, $arguments)
    {
        $instance = new self();

        if (method_exists($instance, $name)) {
            return call_user_func_array([$instance, $name], $arguments);
        }

        throw new \Exception('[' . static::class . '::' . $name . '] method is not implemented');
    }

    /**
     * Magic getter
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Magic setter
     * @param $name
     * @param $value
     * @return JavaScript
     */
    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }

    /**
     * Remove one or many array items from a given array using "dot" notation.
     * @param array|string $keys
     * @return $this
     */
    public function forget($keys)
    {
        $data = $this->getActiveData();
        $this->setActiveData(Arr::forget($data, $keys), false);

        return $this;
    }

    /**
     * Check if an item or items exist in an array using "dot" notation.
     *
     * @param  string|array $keys
     * @return bool
     */
    public function has($keys)
    {
        return Arr::has($GLOBALS[static::$globalNamespace][$this->namespace], $keys);
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    public function get($key = null, $default = null)
    {
        $data = $this->getActiveData();

        if (is_null($key)) {
            return $data;
        }

        return Arr::get($data, $key, $default);
    }

    /**
     * Set an array item to a given value using "dot" notation.
     *
     * @param  string $key
     * @param  mixed $value
     * @return $this
     */
    public function set($key, $value = null)
    {
        if (is_null($value)) {
            $key = Arr::wrap($key);

            $this->merge($key);

            return $this;
        } elseif (isset($GLOBALS[static::$globalNamespace][$this->namespace][$key]) && is_array($value)) {
            foreach ($value as $item) {
                if (!in_array($item, $GLOBALS[static::$globalNamespace][$this->namespace][$key])) {
                    $GLOBALS[static::$globalNamespace][$this->namespace][$key][] = $item;
                }
            }
        } else {
            Arr::set($GLOBALS[static::$globalNamespace][$this->namespace], $key, $value);
        }

        return $this;
    }

    /**
     * @alias Javascript::set()
     * @param $key
     * @param $value
     * @return JavaScript
     */
    public function add($key, $value = null){
        return $this->set($key, $value);
    }

    /**
     * Set the namespace
     * @param $namespace
     * @return $this
     */
    public static function namespace($namespace)
    {
        if (!isset(self::$_instances[$namespace])) {
            self::$_instances[$namespace] = new static($namespace);
        }

        return self::$_instances[$namespace];
    }

    /**
     * Flatten a multi-dimensional associative array with dots.
     *
     * @param  string $prepend
     * @return $this
     */
    public function dot($prepend = '')
    {
        $data = $this->getActiveData();
        $this->setActiveData(Arr::dot($data, $prepend));

        return $this;
    }

    /**
     * Get a subset of the items from the given array.
     *
     * @param  array|string $keys
     * @return $this
     */
    public function only($keys)
    {
        $data = $this->getActiveData();
        $this->setActiveData(Arr::only($data, $keys));

        return $this;
    }

    /**
     * Pluck an array of values from an array.
     *
     * @param  string|array|null $value
     * @param null $key
     * @return $this
     */
    public function pluck($value, $key = null)
    {
        $data = $this->getActiveData();
        $this->setActiveData(Arr::pluck($data, $value, $key));

        return $this;
    }

    /**
     * Filter the array using the given callback.
     *
     * @param  callable $callback
     * @return $this
     */
    public function where(callable $callback)
    {
        $data = $this->getActiveData();
        $this->setActiveData(Arr::where($data, $callback));

        return $this;
    }

    /**
     * Merge the given arrays
     *
     * @param  array * $arrays
     * @return $this
     * @throws \Exception
     */
    public function merge()
    {
        $arrays = func_get_args();

        foreach ($arrays as $array) {
            if (!is_array($array)) {
                throw new \Exception('[Hook::merge] all arguments must be Array.');
            }

            foreach ($array as $key => $value) {
                if ($this->has($key)) {
                    $tmp = $this->get($key);

                    if (is_array($tmp)) {
                        $value = Arr::wrap($value);
                        $value = array_merge_recursive($tmp, $value);
                    }
                }

                $this->set($key, $value);
            }
        }

        return $this;
    }

    /**
     * JSON encode
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->get(), true);
    }

    /**
     * Render as Script tag
     * @param null $varName
     * @param array $variables
     * @param bool $scriptTag
     * @return string
     * @internal param null $namespace
     */
    public function render($varName = null, $variables = [], $scriptTag = true)
    {
        if (is_null($varName)) {
            $varName = $this->namespace;
        }

        $this->merge($variables);

        return ($scriptTag?'<script>':'').'window.' . $varName . ' = ' . $this->toJson() . ';'.($scriptTag?'</script>':'');
    }

    public function renderVar($namespace = null, $variables = []){
        return $this->render($namespace, $variables, false);
    }

    /**
     * Retrieve selected data
     * @return mixed|null
     */
    private function getActiveData()
    {
        if (!is_null($this->activeData)) {
            return $this->activeData;
        }

        return $GLOBALS[static::$globalNamespace][$this->namespace];
    }

    /**
     * Set selected data
     * @param $data
     */
    private function setActiveData($data, $force = true)
    {
        if ($force) {
            $this->activeData = $data;
        } else {
            $GLOBALS[static::$globalNamespace][$this->namespace] = $data;
        }
    }
}