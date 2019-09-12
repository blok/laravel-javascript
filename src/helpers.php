<?php

if(!function_exists('JavaScript')){
    /**
     * @return \Blok\JavaScript\JavaScript
     */
    function javascript($key = null, $value = null){

        /**
         * @var $javascript \Blok\JavaScript\JavaScript
         */
        $javascript = app('JavaScript');

        if ($key && $value) {
            return $javascript->set($key, $value);
        } elseif (is_array($key)) {
            return $javascript->set($key);
        } elseif ($key) {
            return $javascript->get($key);
        }

        return $javascript;
    }
}