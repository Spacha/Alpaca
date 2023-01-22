<?php

namespace App\Framework\Libs;

use App\Framework\Exceptions\InternalException;

/**
* Handles http request specific stuff. Instance of this class is passed
* to every method that uses post data.
*/
class Request
{
    protected $postData = [];

    public function __construct()
    {
        if ($this->method() == 'POST')
            $this->setPostData();
    }

    /**
     * Get a piece or all of the data stored to object's $data property
     *
     * @param sting $name   Name or key of the data piece
     * @return mixed        Value of the data or an associative array of them
     */
    public function data(string $name = '')
    {
        if (!strlen($name))
            return $this->postData;

        if (array_key_exists($name, $this->postData))
            return $this->postData[$name];

        return null;
    }

    /**
     * Set an array of parameters to object's data property
     * Setting a single value is easy: $this->setData(['key' => 'value'])
     *
     * @param array $data
     * @return void
     */
    public function injectPostData(array $data)
    {
        if (is_array($data)) {
            $this->postData += $data;
        }
    }

    /**
     * Sets the current post data as the object's property
     *
     * @return void
     **/
    protected function setPostData()
    {
        $this->postData = $_POST;
    }


    /*------------
    * Static tools
    *-----------*/


    /**
     * Returns the current uri.
     *
     * @return string
     */
    public static function uri() : string
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Returns the current request method
     *
     * @return string
     */
    public static function method() : string
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
