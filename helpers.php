<?php

use X\Store\StoreCollection;

if(!function_exists('store'))
{
    function store(array $value = null)
    {
        return new StoreCollection($value);
    }
}