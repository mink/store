<?php

if(!function_exists('store'))
{
    function store($value = null)
    {
        return new \X\Store($value);
    }
}