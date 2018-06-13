<?php

namespace X;

use Illuminate\Support\{Collection,HigherOrderCollectionProxy};

class Store extends Collection
{
    public function __construct($items = [])
    {
        if(!empty($items))
        {
            foreach($items as $key => $value)
            {
                $this->createItem($key, $value);
            }
        }
    }

    private function createItem($key, $value = [])
    {
        $this->items[$key] = (is_array($value) || is_object($value)) ? $this->createNestedItem($value)
            : $this->createSimpleItem($value);
    }
    
    private function createNestedItem($items = [])
    {
        return new self($items);
    }
    
    private function createSimpleItem($value)
    {
        return $value;
    }
    
    private function createItemIfNotExists($key)
    {
        if (!array_get($this->items, $key))
        {
            $this->createItem($key);
        }
    }
    
    public function offsetGet($key)
    {
        $this->createItemIfNotExists($key);
        return $this->items[$key];
    }
    
    public function offsetSet($key, $value)
    {
        !$key ? $this->items[] = $value : $this->createItem($key, $value);
    }
    
    public function offsetExists($key)
    {
        if(!empty(array_get($this->items, $key)))
        {
            return true;
        }
    }
    
    public function offsetUnset($key)
    {
        if(isset($this->items[$key]))
        {
            unset($this->items[$key]);
        }
    }
    
    public function __get($key)
    {
        if (in_array($key, static::$proxies))
        {
            return new HigherOrderCollectionProxy($this, $key);
        }
        
        $this->createItemIfNotExists($key);
        return array_get($this->items, $key);
    }
    
    public function __set($key, $value)
    {
        $this->createItem($key, $value);
    }
    
    public function __isset($key)
    {
        if(isset($this->items[$key]))
        {
            return true;
        }
    }
    
    public function __unset($key)
    {
        if(isset($this->items[$key]))
        {
            unset($this->items[$key]);
        }
    }
    
    public function get($key, $default = null)
    {
        if ($this->offsetExists($key)) {
            return array_get($this->items, $key, $default);
        }

        return value($default);
    }
    
    public function set($key, $value)
    {
        return $this->put($key, $value);
    }
}