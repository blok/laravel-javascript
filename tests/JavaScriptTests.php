<?php

namespace Tests\Feature;

use Blok\JavaScript\JavaScript;
use Tests\TestCase;

class JavaScriptTests extends TestCase
{

    /**
     * Append a value or update it if already exist
     * @example
     * JavaScript::add(['key' => 'value']);
     * JavaScript::add('key', 'value');
     */
    public function testAddValue()
    {
        // array
        JavaScript::set([
            'key1' => 'value1',
            'key2' => 'value2',
        ]);

        $this->assertEquals(
            JavaScript::get(),
            [
                'key1' => 'value1',
                'key2' => 'value2',
            ]
        );

        // key, value
        JavaScript::set('key3', 'value3');

        $this->assertEquals(
            JavaScript::get(),
            [
                'key1' => 'value1',
                'key2' => 'value2',
                'key3' => 'value3',
            ]
        );

        // dotnotation
        JavaScript::set('key4.subKey1', 'subValue1');

        $this->assertEquals(
            JavaScript::get(),
            [
                'key1' => 'value1',
                'key2' => 'value2',
                'key3' => 'value3',
                'key4' => [
                    'subKey1' => 'subValue1',
                ],
            ]
        );
    }

    /**
     * @example
     * JavaScript::add(function () {
     *     return ['key' => 'value'];
     * });
     */
    /*public function testAddValueViaClosure()
    {
        JavaScript::add(function () {
            return [
                'key' => 'value',
            ];
        });

        $this->assertEquals(
            JavaScript::get(),
            [
                'key' => 'value',
            ]
        );
    }*/

    /**
     * Create or modify a value.
     * @example
     * JavaScript::set(['key' => 'value']);
     * JavaScript::set('key', 'value');
     */
    /*public function testSetValue()
    {
        JavaScript::set('key', 'value');
        JavaScript::set([
            'key' => 'value2',
        ]);

        $this->assertEquals(
            JavaScript::get(),
            [
                'key' => 'value2',
            ]
        );
    }*/

    /**
     * @example
     * JavaScript::render('namespace');
     */
    /*public function testSetNamespace()
    {
        $this->assertEquals(
            JavaScript::render('test'),
            '<script>window.test = null;</script>'
        );
    }*/

    /**
     * @example
     * JavaScript::has('key');
     */
    /*public function testHasValue()
    {
        JavaScript::set('key', 'value');

        $this->assertTrue(JavaScript::has('key'));
    }*/

    /**
     * @example
     * JavaScript::get('key');
     * JavaScript::get('key', 'default');
     */
    /*public function testGetValue()
    {
        JavaScript::set('key', 'value');

        $this->assertEquals(
            JavaScript::get('key'),
            'value'
        );

        $this->assertEquals(
            JavaScript::get('key_x', 'value'),
            'value'
        );
    }*/

    /**
     * @example
     * JavaScript::merge(['key' => 'value']);
     * JavaScript::merge(['key' => 'value'], ['anotherKey' => 'value']);
     */
    public function testMergeValue()
    {

    }

    /**
     * @example
     * JavaScript::only('key');
     * JavaScript::only(['key', 'anotherKey']);
     */
    public function testOnlyValues()
    {

    }

    /**
     * @example
     * JavaScript::pluck('key');
     * JavaScript::pluck('key', 'anotherKey');
     */
    public function testPluckValues()
    {

    }

    /**
     * @example
     * JavaScript::where(function ($value, $key) {
     *     return is_string($value);
     * });
     */
    public function testWhereValue()
    {

    }
}
