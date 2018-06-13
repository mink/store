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
    
    public function testDotSyntax()
    {
        $store = new Store([
            'form' => [
                'register' => [
                    'enabled' => true,
                    'username' => [
                        'min_length' => 2,
                        'max_length' => 16,
                        'regex' => '^[a-zA-Z0-9]+$'
                    ],
                    'email' => [
                        'allow invalid' => false,
                        'allow_idns' => true,
                    ]
                ]
            ]
        ]);
        
        // dot syntax access
        $this->assertEquals(true, $store->get('form.register.enabled'));
        
        // array access after dot syntax
        $this->assertEquals('^[a-zA-Z0-9]+$', $store->get('form.register.username')['regex']);
        
        // property access after dot syntax
        $this->assertEquals(16, $store->get('form.register.username')->max_length);
        
        // dot syntax access after dot syntax
        $this->assertEquals(false, $store->get('form.register')->get('email.allow_invalid'));
        
        // dot syntax access after array access
        $this->assertEquals(true, $store['form']['register']->get('email.allow_idns'));
        
        // dot syntax access after property access
        $this->assertEquals(true, $store->form->get('register.email.allow_idns'));
        
        // dot syntax access after array & property access
        $this->assertEquals(2, $store->form['register']->get('username.min_length'));
    }
}