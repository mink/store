<?php

namespace X\Store\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Collection;
use X\Store\StoreCollection;

class JsonStoreCast implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return new StoreCollection(json_decode($value, true));
    }

    public function set($model, $key, $value, $attributes)
    {
        return ($value instanceof Collection) ? $value->toJson() : json_encode($value);
    }
}
