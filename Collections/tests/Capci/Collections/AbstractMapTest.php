<?php
namespace Capci\Collections;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-11-21 at 16:31:59.
 */
class AbstractMapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractMap
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        require '../src/Capci/Collections/autoload.php';
        $this->object = new class() extends AbstractMap {
            
            private $r = [];

            public function getIterator(): \Traversable {
                //return new \ArrayIterator($this->r);
                /*$i = 0;
                foreach ($this->r as $entry) {
                    yield $i++ => $entry;
                }*/
                
                foreach ($this->r as list($key, $value)) {
                    yield $key => $value;
                }
            }

            public function put($key, $value) {
                $ret = null;
                $index = count($this->r);
                foreach ($this->r as $i => list($k, $v)) {
                    if($this->elementsEquals($key, $k)) {
                        $ret = $v;
                        $index = $i;
                        break;
                    }
                }
                $this->r[$index] = [$key, $value];
                return $ret;
            }

            public function remove($key) {
                $index = -1;
                foreach ($this->r as $i => list($k, $v)) {
                    if($this->elementsEquals($key, $k)) {
                        $index = $i;
                        break;
                    }
                }
                if($index !== -1) {
                    array_splice($this->r, $index, 1);
                    return true;
                }
                return false;
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
    
    private function assertSameEntriesSet(array $expected, Map $map) {
        $keys = [];
        foreach ($expected as $entry) {
            $key = $entry[0];
            foreach ($keys as $k) {
                if($map->getEqualityComparer()->elementsEquals($key, $k)) {
                    $this->fail();
                }
            }
            $keys[] = $key;
        }
        
        $actual = $map->toArray();
        $this->assertSame(count($expected), count($actual));
        
        foreach ($expected as $key => $expectedValue) {
            $done = false;
            foreach ($actual as $k => $v) {
                if($map->getEqualityComparer()->elementsEquals($key, $k)) {
                    $this->assertSame($expectedValue, $v);
                    $done = true;
                    break;
                }
            }
            if(!$done) {
                $this->fail();
            }
        }
    }
    
    public function testClear() {
        $this->object->clear();
        $this->assertSame(0, $this->object->count());
        
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(null, null);
        $this->object->put(new \stdClass(), new \stdClass());
        $this->object->clear();
        $this->assertSame(0, $this->object->count());
    }
    
    public function testPutAll() {
        $m = clone $this->object;
        
        $this->object->clear();
        $m->clear();
        $this->object->putAll($m);
        $this->assertSameEntriesSet([], $this->object);
        
        $this->object->clear();
        $m->clear();
        $m->put(1, 1);
        $m->put(2, 2);
        $m->put(3, 3);
        $this->object->putAll($m);
        $this->assertSameEntriesSet([[1, 1], [2, 2], [3, 3]], $this->object);
        
        $this->object->clear();
        $m->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $m->put(4, 4);
        $m->put(5, 5);
        $m->put(6, 6);
        $this->object->putAll($m);
        $this->assertSameEntriesSet([[1, 1], [2, 2], [3, 3], [4, 4], [5, 5], [6, 6]], $this->object);
        
        $this->object->clear();
        $m->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $m->put(1, 4);
        $m->put(2, 5);
        $m->put(3, 6);
        $this->object->putAll($m);
        $this->assertSameEntriesSet([[1, 4], [2, 5], [3, 6]], $this->object);
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->putAll([4 => 4, 5 => 5, 6 => 6]);
        $this->assertSameEntriesSet([[1, 1], [2, 2], [3, 3], [4, 4], [5, 5], [6, 6]], $this->object);
        
        $this->object->clear();
        $m->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->putAll([1 => 4, 2 => 5, 3 => 6]);
        $this->assertSameEntriesSet([[1, 4], [2, 5], [3, 6]], $this->object);
    }
    
    public function testGet() {
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        $this->assertSame(1, $this->object->get(1));
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        $this->assertSame(3, $this->object->get(3));
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        $this->assertSame(5, $this->object->get(5));
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        try {
            $this->object->get(0);
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSameEntriesSet([[1, 1], [2, 2], [3, 3], [4, 4], [5, 5]], $this->object);
        }
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        try {
            $this->object->get(new \stdClass());
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSameEntriesSet([[1, 1], [2, 2], [3, 3], [4, 4], [5, 5]], $this->object);
        }
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        try {
            $this->object->get('3');
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSameEntriesSet([[1, 1], [2, 2], [3, 3], [4, 4], [5, 5]], $this->object);
        }
    }
    
    public function testGetOrDefault() {
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        $this->assertSame(1, $this->object->getOrDefault(1, 'default'));
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        $this->assertSame(3, $this->object->getOrDefault(3, 'default'));
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        $this->assertSame(5, $this->object->getOrDefault(5, 'default'));
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        $this->assertSame('default', $this->object->getOrDefault(0, 'default'));
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        $this->assertSame('default', $this->object->getOrDefault(new \stdClass(), 'default'));
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        $this->assertSame('default', $this->object->getOrDefault('3', 'default'));
    }
    
    public function testContainsKey() {
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        
        $this->assertTrue($this->object->containsKey(1));
        $this->assertTrue($this->object->containsKey(3));
        $this->assertTrue($this->object->containsKey(5));
        
        $this->assertFalse($this->object->containsKey(0));
        $this->assertFalse($this->object->containsKey(6));
        $this->assertFalse($this->object->containsKey('3'));
        $this->assertFalse($this->object->containsKey('foo'));
    }
    
    public function testContainsValue() {
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        
        $this->assertTrue($this->object->containsValue(1));
        $this->assertTrue($this->object->containsValue(3));
        $this->assertTrue($this->object->containsValue(5));
        
        $this->assertFalse($this->object->containsValue(0));
        $this->assertFalse($this->object->containsValue(6));
        $this->assertFalse($this->object->containsValue('3'));
        $this->assertFalse($this->object->containsValue('foo'));
    }
    
    public function testKeySet() {
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        $actual = $this->object->keySet()->toArray();
        usort($actual, function($e1, $e2) {
            return $e1 - $e2;
        });
        $this->assertSame([1, 2, 3, 4, 5], $actual);
    }
    
    public function testOffsetSet() {
        $this->object[null] = null;
        $this->assertSame([[null, null]], $this->object->toArray());
        
        $this->object->clear();
        $this->object[1] = 1;
        $this->object[2] = 2;
        $this->object[3] = 3;
        $this->object['3'] = 3;
        $actual = $this->object->toArray();
        usort($actual, function($entry1, $entry2) {
            return $entry1[0] - $entry2[0];
        });
        $this->assertSame([[1, 1], [2, 2], [3, 3]], $actual);
    }
    
    public function testOffsetUnset() {
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        unset($this->object[1]);
        $this->assertSame(4, $this->object->count());
        $this->assertSameEntriesSet([[2, 2], [3, 3], [4, 4], [5, 5]], $this->object);
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        unset($this->object[3]);
        $this->assertSame(4, $this->object->count());
        $this->assertSameEntriesSet([[1, 1], [2, 2], [4, 4], [5, 5]], $this->object);
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        unset($this->object[5]);
        $this->assertSame(4, $this->object->count());
        $this->assertSameEntriesSet([[1, 1], [2, 2], [3, 3], [4, 4]], $this->object);
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        unset($this->object[0]);
        $this->assertSameEntriesSet([[1, 1], [2, 2], [3, 3], [4, 4], [5, 5]], $this->object);
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        unset($this->object[new \stdClass()]);
        $this->assertSameEntriesSet([[1, 1], [2, 2], [3, 3], [4, 4], [5, 5]], $this->object);
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        unset($this->object['3']);
        $this->assertSameEntriesSet([[1, 1], [2, 2], [4, 4], [5, 5]], $this->object);
    }
    
    public function testOffsetGet() {
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        $this->assertSame(1, $this->object[1]);
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        $this->assertSame(3, $this->object[3]);
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        $this->assertSame(5, $this->object[5]);
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        try {
            $this->object[0];
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSameEntriesSet([[1, 1], [2, 2], [3, 3], [4, 4], [5, 5]], $this->object);
        }
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        try {
            $this->object[new \stdClass()];
            $this->fail();
        } catch (\OutOfRangeException $ex) {
            $this->assertSameEntriesSet([[1, 1], [2, 2], [3, 3], [4, 4], [5, 5]], $this->object);
        }
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        $this->assertSame(3, $this->object['3']);
    }
    
    public function testOffsetExists() {
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, null);
        $this->object->put(5, 5);
        
        $this->assertTrue(isset($this->object[1]));
        $this->assertTrue(isset($this->object[3]));
        $this->assertTrue(isset($this->object[5]));
        $this->assertTrue(isset($this->object['3']));
        
        $this->assertFalse(isset($this->object[4]));
        $this->assertFalse(isset($this->object[0]));
        $this->assertFalse(isset($this->object[6]));
        $this->assertFalse(isset($this->object['foo']));
    }
}
