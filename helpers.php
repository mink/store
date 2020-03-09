<?php

use X\Store\Store;

if(!function_exists('store'))
{
    function store(array $value = null)
    {
        return new Store($value);
    }
}