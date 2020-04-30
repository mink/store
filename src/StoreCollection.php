<?php

namespace X\Store;

use Illuminate\Support\{
    Arr,
    Collection,
    HigherOrderCollectionProxy
};

class StoreCollection extends Collection
{
    /**
     * The parent Store if the instance has one.
     *
     * @var null|StoreCollection
     */
    protected ?StoreCollection $parent;

    /**
     * Create a new StoreCollection instance.
     *
     * @param mixed $items
     * @param StoreCollection|null $parent
     */
    public function __construct($items = [], ?StoreCollection $parent = null)
    {
        $this->parent = $parent;
        $items = $this->getArrayableItems($items);

        foreach ($items as $key => $value) {
            $this->createItem($key, $value);
        }
    }

    /**
     * Creates an item, either a child instance or the value given.
     *
     * @param mixed $key
     * @param mixed $value
     * @return mixed
     */
    private function createItem($key, $value = [])
    {
        return $this->items[$key] = (is_array($value) || is_object($value))
            ? $this->createNestedItem($value)
            : $value;
    }

    /**
     * Creates a child StoreCollection instance.
     *
     * @param mixed $items
     * @return StoreCollection
     */
    private function createNestedItem($items = []): StoreCollection
    {
        return new self($items, $this);
    }

    /**
     * Empty the existing StoreCollection instance.
     * This will not remove the parent.
     *
     * @return StoreCollection
     */
    public function empty(): StoreCollection
    {
        $this->items = [];
        return $this;
    }

    /**
     * Get an item from the collection by key.
     *
     * @param mixed $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->offsetExists($key)
            ? Arr::get($this->items, $key, $default)
            : value($default);
    }

    /**
     * Gets an item from the store or creates it if it does not exist.
     *
     * @param mixed $key
     * @return mixed
     */
    private function getItem($key)
    {
        return Arr::get($this->items, $key) ?? $this->createItem($key);
    }

    /**
     * Get the parent store if it exists.
     *
     * @return null|StoreCollection
     */
    public function parent(): ?StoreCollection
    {
        return $this->parent;
    }

    /**
     * Set an item in the store by key.
     * An alias of Collection's put method.
     *
     * @param mixed $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value): StoreCollection
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
        !$key
            ? $this->items[] = $value
            : $this->createItem($key, $value);
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param mixed $key
     * @return bool
     */
    public function offsetExists($key): bool
    {
        return !empty(Arr::get($this->items, $key));
    }

    /**
     * Unset the item at a given offset.
     *
     * @param mixed $key
     */
    public function offsetUnset($key): void
    {
        unset($this->items[$key]);
    }

    /**
     * Dynamically access store proxies and items.
     *
     * @param mixed $key
     * @return HigherOrderCollectionProxy|mixed
     */
    public function __get($key)
    {
        return in_array($key, static::$proxies)
            ? new HigherOrderCollectionProxy($this, $key)
            : $this->getItem($key);
    }

    /**
     * Set items in the store directly.
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function __set($key, $value): void
    {
        $this->createItem($key, $value);
    }

    /**
     * Determine if the key is set or not.
     *
     * @param mixed $key
     * @return bool
     */
    public function __isset($key): bool
    {
        return isset($this->items[$key]);
    }

    /**
     * Unset a key from the store.
     *
     * @param mixed $key
     */
    public function __unset($key): void
    {
        unset($this->items[$key]);
    }
}
