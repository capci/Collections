<?php
namespace Capci\Collections;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-11-21 at 16:31:59.
 */
class AbstractSequenceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractSequence
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        require '../src/Capci/Collections/autoload.php';
        $this->object = new class() extends AbstractSequence {
            
            public $r = [];
            
            public function add($e) {
                $this->r[] = $e;
            }

            public function getIterator(): \Traversable {
                return new \ArrayIterator($this->r);
            }

            public function remove(int $index) {
                if($index < 0 || $index >= count($this->r)) {
                    throw new \OutOfRangeException();
                }
                $ret = $this->r[$index];
                array_splice($this->r, $index, 1);
                return $ret;
            }
        };
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testClear() {
        $this->object->clear();
        $this->assertSame(0, $this->object->count());
        
        $this->object->r = [1, 2, 3];
        $this->object->clear();
        $this->assertSame(0, $this->object->count());
    }
    
    public function testAddAll() {
        $c = clone $this->object;
        
        $this->object->r = [];
        $c->r = [];
        $this->object->addAll($c);
        $this->assertSame([], $this->object->r);
        
        $this->object->r = [];
        $c->r = [1, 2, 3];
        $this->object->addAll($c);
        $this->assertSame([1, 2, 3], $this->object->r);
        
        $this->object->r = [1, 2, 3];
        $c->r = [4, 5, 6];
        $this->object->addAll($c);
        $this->assertSame([1, 2, 3, 4, 5, 6], $this->object->r);
    }
    
    public function testInsert() {
        $this->object->r = [1, 2, 3, 4, 5];
        $this->object->insert(0, null);
        $this->assertSame([null, 1, 2, 3, 4, 5], $this->object->r);
        
        $this->object->r = [1, 2, 3, 4, 5];
        $this->object->insert(2, null);
        $this->assertSame([1, 2, null, 3, 4, 5], $this->object->r);
        
        $this->object->r = [1, 2, 3, 4, 5];
        $this->object->insert(5, null);
        $this->assertSame([1, 2, 3, 4, 5, null], $this->object->r);
        
        $this->object->r = [1, 2, 3, 4, 5];
        try {
            $this->object->insert(-1, null);
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, 3, 4, 5], $this->object->r);
        }
        
        $this->object->r = [1, 2, 3, 4, 5];
        try {
            $this->object->insert(6, null);
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, 3, 4, 5], $this->object->r);
        }
    }
    
    public function testInsertAll() {
        $c = clone $this->object;
        
        $this->object->r = [1, 2, 3, 4, 5];
        $c->r = ['foo', null, 'bar'];
        $this->object->insertAll(0, $c);
        $this->assertSame(['foo', null, 'bar', 1, 2, 3, 4, 5], $this->object->r);
        
        $this->object->r = [1, 2, 3, 4, 5];
        $c->r = ['foo', null, 'bar'];
        $this->object->insertAll(2, $c);
        $this->assertSame([1, 2, 'foo', null, 'bar', 3, 4, 5], $this->object->r);
        
        $this->object->r = [1, 2, 3, 4, 5];
        $c->r = ['foo', null, 'bar'];
        $this->object->insertAll(5, $c);
        $this->assertSame([1, 2, 3, 4, 5, 'foo', null, 'bar'], $this->object->r);
        
        $this->object->r = [1, 2, 3, 4, 5];
        $c->r = ['foo', null, 'bar'];
        try {
            $this->object->insertAll(-1, $c);
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, 3, 4, 5], $this->object->r);
            $this->assertSame(['foo', null, 'bar'], $c->r);
        }
        
        $this->object->r = [1, 2, 3, 4, 5];
        $c->r = ['foo', null, 'bar'];
        try {
            $this->object->insertAll(6, $c);
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, 3, 4, 5], $this->object->r);
            $this->assertSame(['foo', null, 'bar'], $c->r);
        }
    }
    
    public function testGet() {
        $this->object->r = [1, 2, 3, 4, 5];
        $this->assertSame(1, $this->object->get(0));
        
        $this->object->r = [1, 2, 3, 4, 5];
        $this->assertSame(3, $this->object->get(2));
        
        $this->object->r = [1, 2, 3, 4, 5];
        $this->assertSame(5, $this->object->get(4));
        
        $this->object->r = [1, 2, 3, 4, 5];
        try {
            $this->object->get(-1);
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, 3, 4, 5], $this->object->r);
        }
        
        $this->object->r = [1, 2, 3, 4, 5];
        try {
            $this->object->get(5);
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, 3, 4, 5], $this->object->r);
        }
    }
    
    public function testSet() {
        $this->object->r = [1, 2, 3, 4, 5];
        $this->assertSame(1, $this->object->set(0, null));
        $this->assertSame([null, 2, 3, 4, 5], $this->object->r);
        
        $this->object->r = [1, 2, 3, 4, 5];
        $this->assertSame(3, $this->object->set(2, null));
        $this->assertSame([1, 2, null, 4, 5], $this->object->r);
        
        $this->object->r = [1, 2, 3, 4, 5];
        $this->assertSame(5, $this->object->set(4, null));
        $this->assertSame([1, 2, 3, 4, null], $this->object->r);
        
        $this->object->r = [1, 2, 3, 4, 5];
        try {
            $this->object->set(-1, null);
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, 3, 4, 5], $this->object->r);
        }
        
        $this->object->r = [1, 2, 3, 4, 5];
        try {
            $this->object->set(5, null);
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, 3, 4, 5], $this->object->r);
        }
    }
    
    public function testOffsetSet() {
        $this->object->r = [1, 2, 3, 4, 5];
        $this->object[0] = null;
        $this->assertSame([null, 2, 3, 4, 5], $this->object->r);
        
        $this->object->r = [1, 2, 3, 4, 5];
        $this->object[2] = null;
        $this->assertSame([1, 2, null, 4, 5], $this->object->r);
        
        $this->object->r = [1, 2, 3, 4, 5];
        $this->object[4] = null;
        $this->assertSame([1, 2, 3, 4, null], $this->object->r);
        
        $this->object->r = [1, 2, 3, 4, 5];
        $this->object[] = null;
        $this->assertSame([1, 2, 3, 4, 5, null], $this->object->r);
        
        $this->object->r = [1, 2, 3, 4, 5];
        try {
            $this->object[-1] = null;
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, 3, 4, 5], $this->object->r);
        }
        
        $this->object->r = [1, 2, 3, 4, 5];
        try {
            $this->object[5] = null;
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, 3, 4, 5], $this->object->r);
        }
    }
    
    public function testOffsetUnset() {
        $this->object->r = [1, 2, 3, 4, 5];
        try {
            unset($this->object[0]);
            $this->fail();
        } catch (\BadMethodCallException $ex) {
            $this->assertSame([1, 2, 3, 4, 5], $this->object->r);
        }
    }
    
    public function testOffsetGet() {
        $this->object->r = [1, 2, 3, 4, 5];
        $this->assertSame(1, $this->object[0]);
        
        $this->object->r = [1, 2, 3, 4, 5];
        $this->assertSame(3, $this->object[2]);
        
        $this->object->r = [1, 2, 3, 4, 5];
        $this->assertSame(5, $this->object[4]);
        
        $this->object->r = [1, 2, 3, 4, 5];
        try {
            $this->object[-1];
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, 3, 4, 5], $this->object->r);
        }
        
        $this->object->r = [1, 2, 3, 4, 5];
        try {
            $this->object[5];
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, 3, 4, 5], $this->object->r);
        }
    }
    
    public function testOffsetExists() {
        $this->object->r = [1, 2, null, 4, 5];
        $this->assertTrue(isset($this->object[0]));
        $this->assertTrue(isset($this->object[3]));
        $this->assertTrue(isset($this->object[4]));
        $this->assertFalse(isset($this->object[-1]));
        $this->assertFalse(isset($this->object[5]));
        $this->assertFalse(isset($this->object[2]));
    }
    
    public function testContains() {
        $this->object->r = [1, 2, null, 4, 5];
        $this->assertTrue($this->object->contains(1));
        $this->assertTrue($this->object->contains(4));
        $this->assertTrue($this->object->contains(5));
        $this->assertTrue($this->object->contains(null));
        
        $this->assertFalse($this->object->contains(0));
    }
    
    public function testContainsAll() {
        $c = clone $this->object;
        $this->object->r = [1, 2, null, 4, 5];
        
        $c->r = [2, null, 4];
        $this->assertTrue($this->object->containsAll($c));
        
        $c->r = [];
        $this->assertTrue($this->object->containsAll($c));
        
        $c->r = [1, 2, 5, 0];
        $this->assertFalse($this->object->containsAll($c));
    }
    
    public function testIndexOf() {
        $this->object->r = [1, 2, null, 4, 1, 5];
        
        $this->assertSame(0, $this->object->indexOf(1));
        $this->assertSame(2, $this->object->indexOf(null));
        $this->assertSame(5, $this->object->indexOf(5));
        
        $this->assertSame(-1, $this->object->indexOf(3));
    }
    
    public function testLastIndexOf() {
        $this->object->r = [1, 2, null, 4, 1, 5];
        
        $this->assertSame(4, $this->object->lastIndexOf(1));
        $this->assertSame(2, $this->object->lastIndexOf(null));
        $this->assertSame(5, $this->object->lastIndexOf(5));
        
        $this->assertSame(-1, $this->object->lastIndexOf(3));
    }
    
    public function testRange() {
        $this->object->r = [1, 2, null, 4, 5];
        
        $subSequence = $this->object->range(0, 5);
        $this->assertSame([1, 2, null, 4, 5], $subSequence->toArray());
        
        $subSequence = $this->object->range(1, 3);
        $this->assertSame([2, null, 4], $subSequence->toArray());
        
        try {
            $subSequence = $this->object->range(-1, 3);
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, null, 4, 5], $this->object->r);
        }
        
        try {
            $subSequence = $this->object->range(0, 6);
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, null, 4, 5], $this->object->r);
        }
        
        try {
            $subSequence = $this->object->range(2, 4);
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSame([1, 2, null, 4, 5], $this->object->r);
        }
    }
}
