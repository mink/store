<?php

namespace X;

use Illuminate\Support\{Collection,HigherOrderCollectionProxy};

class Store extends Collection
{
    /**
     * The parent Store if the instance has one.
     *
     * @var null|Store
     */
    protected $parent;

    /**
     * Create a new Store instance.
     *
     * @param array $items
     * @param Store|null $parent
     */
    public function __construct($items = [], Store $parent = null)
    {
        $this->parent = $parent;

        $items = $this->getArrayableItems($items);

        foreach($items as $key => $value)
        {
            $this->createItem($key, $value);
        }

    }

    /**
     * Creates an item, either a child instance or the value given.
     *
     * @param $key
     * @param array $value
     * @return array|Store
     */
    private function createItem($key, $value = [])
    {
        return $this->items[$key] = (is_array($value) || is_object($value)) ? $this->createNestedItem($value)
            : $value;
    }

    /**
     * Creates a child Store instance.
     *
     * @param array $items
     * @return Store
     */
    private function createNestedItem($items = []): Store
    {
        return new self($items, $this);
    }

    /**
     * Get an item from the collection by key.
     *
     * @param mixed $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if ($this->offsetExists($key))
        {
            return array_get($this->items, $key, $default);
        }

        return value($default);
    }

    /**
     * Gets an item from the store or creates it if it does not exist.
     *
     * @param $key
     * @return array|mixed|Store
     */
    private function getItem($key)
    {
        return array_get($this->items, $key) ?? $this->createItem($key);
    }

    /**
     * Get the parent store if it exists.
     *
     * @return null|Store
     */
    public function parent()
    {
        return $this->parent;
    }

    /**
     * Set an item in the store by key.
     * An alias of Collection's put method.
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function set($key, $value): Store
    {
        return $this->put($key, $value);
    }

    /**
     * Get an item at a given offset.
     *
     * @param mixed $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->getItem($key);
    }

    /**
     * Set the item at a given offset.
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function offsetSet($key, $value): void
    {
        !$key ? $this->items[] = $value : $this->createItem($key, $value);
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param mixed $key
     * @return bool
     */
    public function offsetExists($key): bool
    {
        return !empty(array_get($this->items, $key));
    }

    /**
     * Unset the item at a given offset.
     *
     * @param string $key
     */
    public function offsetUnset($key): void
    {
        unset($this->items[$key]);
    }

    /**
     * Dynamically access store proxies and items.
     *
     * @param string $key
     * @return HigherOrderCollectionProxy|mixed
     */
    public function __get($key)
    {
        if (in_array($key, static::$proxies))
        {
            return new HigherOrderCollectionProxy($this, $key);
        }
        
        return $this->getItem($key);
    }

    /**
     * Set items in the store directly.
     *
     * @param $key
     * @param $value
     */
    public function __set($key, $value): void
    {
        $this->createItem($key, $value);
    }

    /**
     * Determine if the key is set or not.
     *
     * @param $key
     * @return bool
     */
    public function __isset($key): bool
    {
        return isset($this->items[$key]);
    }

    /**
     * Unset a key from the store.
     *
     * @param $key
     */
    public function __unset($key): void
    {
        unset($this->items[$key]);
    }
}