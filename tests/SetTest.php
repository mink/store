<?php

use PHPUnit\Framework\TestCase;
use X\Store;
use Illuminate\Support\Collection;

final class SetTest extends TestCase
{
    public function testIfEmpty()
    {
        $store = new Store(['key' => 'value', 'empty_array' => []]);
        $emptyStore = new Store();
        
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
        $store = new Store(['key' => 'value', 'empty_array' => []]);
        $emptyStore = new Store();
        
        // a store with data is of course not null
        $this->assertNotNull($store);
        
        // an empty store is not null, it is an instance of Store
        $this->assertNotNull($emptyStore);
        
        // the key empty_array is not considered null, it is transformed
        // into an instance of Store automatically
        $this->assertNotNull($store->empty_array);
    }
    
    public function testIfStore()
    {
        $store = new Store(['key' => 'value', 'empty_array' => []]);
        
        // both are a Store instance, regardless of contained data
        $this->assertInstanceOf(Store::class, $store);
        $this->assertInstanceOf(Store::class, $store->empty_array);
        
        // the key being searched for will be created automatically
        // and instanced as a Store if it does not exist
        $this->assertInstanceOf(Store::class, $store->aaaaaaaa);
        $this->assertInstanceOf(Store::class, $store['testing']->{123}['hello']);
        
        // this key cannot be a Store, it returns a string
        $this->assertNotInstanceOf(Store::class, $store['key']);
        
        // Store extends the Collection of illuminate/support
        $this->assertInstanceOf(Collection::class, $store);
        $this->assertInstanceOf(Collection::class, $store->empty_array);
        $this->assertInstanceOf(Collection::class, $store['doesnt_exist_yet']);
    }
    
    public function testIfSet()
    {
        $store = new Store(['key' => 'value', 'empty_array' => []]);
        $emptyStore = new Store();
        
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