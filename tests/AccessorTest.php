<?php

use PHPUnit\Framework\TestCase;
use Store\Store;

final class AccessorTest extends TestCase
{
    public function testArrayAccess()
    {
        $store = new Store([
            'name' => 'James',
            'fruits' => ['apple', 'banana', 'orange'],
            'resources' => [
                'wood' => 1200,
                'food' => 200,
                'stone' => 650,
                'gold' => 750
            ]
        ]);
        
        // array access
        $this->assertEquals('James', $store['name']);
        
        // array access first index 
        $this->assertEquals('apple', $store['fruits'][0]);
        
        // Collection method via array access
        $this->assertEquals('orange', $store['fruits']->last());
        
        // nested array access via array access
        $this->assertEquals(650, $store['resources']['stone']);
        
        // nested property access via array access
        $this->assertEquals(200, $store['resources']->food);
    }
    
    public function testPropertyAccess()
    {
        $store = new Store([
            'name' => 'James',
            'fruits' => ['apple', 'banana', 'orange'],
            'resources' => [
                'wood' => 1200,
                'food' => 200,
                'stone' => 650,
                'gold' => 750
            ]
        ]);

        // property access
        $this->assertEquals('James', $store->name);
        
        // array index via property access
        $this->assertEquals('banana', $store->fruits[1]);
        
        // Collection method via property access
        $this->assertEquals('apple', $store->fruits->first());
        
        // nested property access via property access
        $this->assertEquals(200, $store->resources->food);
        
        // nested array access via property access
        $this->assertEquals(1200, $store->resources['wood']);
    }
}