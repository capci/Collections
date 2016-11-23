<?php
namespace Capci\Collections;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-11-21 at 16:31:59.
 */
class ArrayListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ArrayList
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        require '../src/Capci/Collections/autoload.php';
        $this->object = new ArrayList();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testCount() {
        $this->assertSame(0, $this->object->count());
        
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->assertSame(3, $this->object->count());
    }
    
    public function testIsEmpty() {
        $this->assertTrue($this->object->isEmpty());
        
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->assertFalse($this->object->isEmpty());
    }
    
    public function testClear() {
        $this->object->clear();
        $this->assertSame(0, $this->object->count());
        
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->clear();
        $this->assertSame(0, $this->object->count());
    }
    
    public function testToArray() {
        $this->assertSame([], $this->object->toArray());
        
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->assertSame([1, 2, 3], $this->object->toArray());
    }
    
    public function testGetIterator() {
        $expectedElements = [];
        foreach ($this->object as $i => $e) {
            $this->assertSame($expectedElements[$i], $e);
        }
        
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $expectedElements = [1, 2, 3];
        foreach ($this->object as $i => $e) {
            $this->assertSame($expectedElements[$i], $e);
        }
    }
    
    public function testAdd() {
        $this->object->add(null);
        $this->assertSame([null], $this->object->toArray());
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->assertSame([1, 2, 3], $this->object->toArray());
    }
    
    public function testAddAll() {
        $c = new ArrayList();
        
        $this->object->clear();
        $c->clear();
        $this->object->addAll($c);
        $this->assertSame([], $this->object->toArray());
        
        $this->object->clear();
        $c->clear();
        $c->add(1);
        $c->add(2);
        $c->add(3);
        $this->object->addAll($c);
        $this->assertSame([1, 2, 3], $this->object->toArray());
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $c->clear();
        $c->add(4);
        $c->add(5);
        $c->add(6);
        $this->object->addAll($c);
        $this->assertSame([1, 2, 3, 4, 5, 6], $this->object->toArray());
    }
    
    public function testInsert() {
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        $this->object->insert(0, null);
        $this->assertSame([null, 1, 2, 3, 4, 5], $this->object->toArray());
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        $this->object->insert(2, null);
        $this->assertSame([1, 2, null, 3, 4, 5], $this->object->toArray());
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        $this->object->insert(5, null);
        $this->assertSame([1, 2, 3, 4, 5, null], $this->object->toArray());
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        try {
            $this->object->insert(-1, null);
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, 3, 4, 5], $this->object->toArray());
        }
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        try {
            $this->object->insert(6, null);
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, 3, 4, 5], $this->object->toArray());
        }
    }
    
    public function testInsertAll() {
        $c = new ArrayList();
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        $c->clear();
        $c->add('foo');
        $c->add(null);
        $c->add('bar');
        $this->object->insertAll(0, $c);
        $this->assertSame(['foo', null, 'bar', 1, 2, 3, 4, 5], $this->object->toArray());
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        $c->clear();
        $c->add('foo');
        $c->add(null);
        $c->add('bar');
        $this->object->insertAll(2, $c);
        $this->assertSame([1, 2, 'foo', null, 'bar', 3, 4, 5], $this->object->toArray());
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        $c->clear();
        $c->add('foo');
        $c->add(null);
        $c->add('bar');
        $this->object->insertAll(5, $c);
        $this->assertSame([1, 2, 3, 4, 5, 'foo', null, 'bar'], $this->object->toArray());
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        $c->clear();
        $c->add('foo');
        $c->add(null);
        $c->add('bar');
        try {
            $this->object->insertAll(-1, $c);
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, 3, 4, 5], $this->object->toArray());
            $this->assertSame(['foo', null, 'bar'], $c->toArray());
        }
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        $c->clear();
        $c->add('foo');
        $c->add(null);
        $c->add('bar');
        try {
            $this->object->insertAll(6, $c);
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, 3, 4, 5], $this->object->toArray());
            $this->assertSame(['foo', null, 'bar'], $c->toArray());
        }
    }
    
    public function testGet() {
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        $this->assertSame(1, $this->object->get(0));
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        $this->assertSame(3, $this->object->get(2));
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        $this->assertSame(5, $this->object->get(4));
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        try {
            $this->object->get(-1);
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, 3, 4, 5], $this->object->toArray());
        }
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        try {
            $this->object->get(5);
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, 3, 4, 5], $this->object->toArray());
        }
    }
    
    public function testSet() {
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        $this->assertSame(1, $this->object->set(0, null));
        $this->assertSame([null, 2, 3, 4, 5], $this->object->toArray());
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        $this->assertSame(3, $this->object->set(2, null));
        $this->assertSame([1, 2, null, 4, 5], $this->object->toArray());
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        $this->assertSame(5, $this->object->set(4, null));
        $this->assertSame([1, 2, 3, 4, null], $this->object->toArray());
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        try {
            $this->object->set(-1, null);
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, 3, 4, 5], $this->object->toArray());
        }
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        try {
            $this->object->set(5, null);
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, 3, 4, 5], $this->object->toArray());
        }
    }
    
    public function testRemove() {
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        $this->assertSame(1, $this->object->remove(0));
        $this->assertSame([2, 3, 4, 5], $this->object->toArray());
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        $this->assertSame(3, $this->object->remove(2));
        $this->assertSame([1, 2, 4, 5], $this->object->toArray());
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        $this->assertSame(5, $this->object->remove(4));
        $this->assertSame([1, 2, 3, 4], $this->object->toArray());
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        try {
            $this->object->remove(-1);
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, 3, 4, 5], $this->object->toArray());
        }
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);
        try {
            $this->object->remove(5);
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, 3, 4, 5], $this->object->toArray());
        }
    }
    
    public function testOffsetExists() {
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(null);
        $this->object->add(4);
        $this->object->add(5);
        $this->assertTrue(isset($this->object[0]));
        $this->assertTrue(isset($this->object[3]));
        $this->assertTrue(isset($this->object[4]));
        $this->assertFalse(isset($this->object[-1]));
        $this->assertFalse(isset($this->object[5]));
        $this->assertFalse(isset($this->object[2]));
    }
}
