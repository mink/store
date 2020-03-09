<?php

use PHPUnit\Framework\TestCase;
use X\Store\StoreCollection;
use Illuminate\Support\Collection;

final class SetTest extends TestCase
{
    public function testIfEmpty()
    {
        $store = new StoreCollection(['key' => 'value', 'empty_array' => []]);
        $emptyStore = new StoreCollection();
        
        // store contains data
        $this->assertNotEmpty($store);
        
        // emptyStore does not contain data
        $this->assertEmpty($emptyStore);
        
        // the key 'key' contains the string 'value'
        $this->assertNotEmpty($store->get('key'));
        
        // the key 'empty_array' is empty
        $this->assertEmpty($store['empty_array']);

        // whether the store is empty or not
        // empty() will return false for any php object
        $this->assertFalse(empty($store));
        $this->assertFalse(empty($emptyStore));

        // however, you can check if the store
        // is empty by converting it into an array
        $this->assertFalse(empty($store->toArray()));
        $this->assertTrue(empty($emptyStore->toArray()));
    }
    
    public function testIfNull()
    {
        $store = new StoreCollection(['key' => 'value', 'empty_array' => []]);
        $emptyStore = new StoreCollection();
        
        // a store with data is of course not null
        $this->assertNotNull($store);
        
        // an empty store is not null, it is an instance of StoreCollection
        $this->assertNotNull($emptyStore);
        
        // the key empty_array is not considered null, it is transformed
        // into an instance of StoreCollection automatically
        $this->assertNotNull($store->empty_array);
    }
    
    public function testIfStore()
    {
        $store = new StoreCollection(['key' => 'value', 'empty_array' => []]);
        
        // both are a StoreCollection instance, regardless of contained data
        $this->assertInstanceOf(StoreCollection::class, $store);
        $this->assertInstanceOf(StoreCollection::class, $store->empty_array);
        
        // the key being searched for will be created automatically
        // and instanced as a StoreCollection if it does not exist
        $this->assertInstanceOf(StoreCollection::class, $store->aaaaaaaa);
        $this->assertInstanceOf(StoreCollection::class, $store['testing']->{123}['hello']);
        
        // this key cannot be a StoreCollection, it returns a string
        $this->assertNotInstanceOf(StoreCollection::class, $store['key']);
        
        // StoreCollection extends the Collection of illuminate/support
        $this->assertInstanceOf(Collection::class, $store);
        $this->assertInstanceOf(Collection::class, $store->empty_array);
        $this->assertInstanceOf(Collection::class, $store['doesnt_exist_yet']);
    }
    
    public function testIfSet()
    {
        $store = new StoreCollection(['key' => 'value', 'empty_array' => []]);
        $emptyStore = new StoreCollection();
        
        // the store is set
        $this->assertTrue(isset($store));
        
        // the store is set, just has no data
        $this->assertTrue(isset($emptyStore));
        
        // this key within the store is set
        $this->assertTrue(isset($store->empty_array));
        
        // these keys don't exist yet, they are not set
        $this->assertFalse(isset($store['not-here']));
        $this->assertFalse(isset($store['empty_array']->something));
    }
}