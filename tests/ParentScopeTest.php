<?php

use PHPUnit\Framework\TestCase;
use X\Store\Store;

final class ParentScopeTest extends TestCase
{
    public function testParentFromChild(): void
    {
        $parent = new Store([
            'child' => [
                'some', 'data', 'here'
            ]
        ]);

        $this->assertEquals($parent, $parent['child']->parent());
    }
    
    public function testChildFromParentFromChild(): void
    {
        $parent = new Store([
            'child' => [
                'some', 'data', 'here'
            ]
        ]);

        $this->assertEquals($parent->child, $parent->child->parent()->child);
    }
    
    public function testGrandparentFromGrandchild(): void
    {
        $parent = new Store([
            'child' => [
                'grand_child' => [
                    'i am the grandchild'
                ]
            ]
        ]);
        
        $this->assertEquals($parent, $parent['child']->grand_child->parent()->parent());
    }
}