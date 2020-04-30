<?php

namespace X\Store\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Collection;
use X\Store\StoreCollection;

class JsonStoreCast implements CastsAttributes
{
    /**
     * Transform the JSON string into a StoreCollection.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return StoreCollection
     */
    public function get($model, $key, $value, $attributes): StoreCollection
    {
        return new StoreCollection(json_decode($value, true));
    }

    /**
     * Transform the attribute into a JSON string.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes): string
    {
        return ($value instanceof Collection)
            ? $value->toJson()
            : json_encode($value);
    }
}
