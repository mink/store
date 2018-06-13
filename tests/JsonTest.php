<?php

use PHPUnit\Framework\TestCase;
use X\Store;

final class JsonTest extends TestCase
{
    public function testJsonOutput()
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
        
        $this->assertEquals('{"name":"James"}', $store->take(1)->toJson());
        
        $this->assertEquals('["apple","banana","orange"]', $store->fruits->toJson());
        
        $this->assertEquals('{"wood":1200,"food":200,"stone":650,"gold":750}', 
                $store->last()->toJson()
        );
    }
}