<?php

namespace X;

use Illuminate\Support\{Collection,HigherOrderCollectionProxy};

class Store extends Collection
{
    protected $parent;
    
    public function __construct($items = [], self $parent = null)
    {
        $this->parent = $parent;
        
        if($items)
        {
            foreach($items as $key => $value)
            {
                $this->createItem($key, $value);
            }
        }
    }

    private function createItem($key, $value = []): void
    {
        $this->items[$key] = (is_array($value) || is_object($value)) ? $this->createNestedItem($value)
    }
    
    private function createNestedItem($items = []): self
    {
        return new self($items, $this);
    }
    private function createItemIfNotExists($key)
    {
        if (!array_get($this->items, $key))
        {
            $this->createItem($key);
        }
    }
    
    public function parent()
    {
        return $this->parent;
    }
    
    public function offsetGet($key)
    {
        $this->createItemIfNotExists($key);
        return $this->items[$key];
    }
    
    public function offsetSet($key, $value): void
    {
        !$key ? $this->items[] = $value : $this->createItem($key, $value);
    }
    
    public function offsetExists($key): bool
    {
        return !empty(array_get($this->items, $key));
    }
    
    public function offsetUnset($key): void
    {
        unset($this->items[$key]);
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
    
    public function __set($key, $value): void
    {
        $this->createItem($key, $value);
    }
    
    public function __isset($key): bool
    {
        return isset($this->items[$key]);
    }
    
    public function __unset($key): void
    {
        if(isset($this->items[$key]))
        {
            unset($this->items[$key]);
        }
    }
    
    public function get($key, $default = null)
    {
        if ($this->offsetExists($key))
        {
            return array_get($this->items, $key, $default);
        }

        return value($default);
    }
    
    public function set($key, $value)
    {
        return $this->put($key, $value);
    }
}