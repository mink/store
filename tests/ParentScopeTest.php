<?php

use PHPUnit\Framework\TestCase;
use X\Store\StoreCollection;

final class ParentScopeTest extends TestCase
{
    public function testParentFromChild(): void
    {
        $parent = new StoreCollection([
            'child' => [
                'some', 'data', 'here'
            ]
        ]);

        $this->assertEquals($parent, $parent['child']->parent());
    }
    
    public function testChildFromParentFromChild(): void
    {
        $parent = new StoreCollection([
            'child' => [
                'some', 'data', 'here'
            ]
        ]);

        $this->assertEquals($parent->child, $parent->child->parent()->child);
    }
    
    public function testGrandparentFromGrandchild(): void
    {
        $parent = new StoreCollection([
            'child' => [
                'grand_child' => [
                    'i am the grandchild'
                ]
            ]
        ]);
        
        $this->assertEquals($parent, $parent['child']->grand_child->parent()->parent());
    }
}