<?php
namespace Capci\Collections;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-11-21 at 16:31:59.
 */
class HashMapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HashMap
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        require '../src/Capci/Collections/autoload.php';
        $this->object = new HashMap();
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
    
    public function test__construct() {
        try {
            new HashMap(0);
            $this->fail();
        } catch (\InvalidArgumentException $ex) {
        }
        
        try {
            new HashMap(1, 0.0);
            $this->fail();
        } catch (\InvalidArgumentException $ex) {
        }
        
        try {
            new HashMap(1, NAN);
            $this->fail();
        } catch (\InvalidArgumentException $ex) {
        }
    }

    public function testCount() {
        $this->assertSame(0, $this->object->count());
        
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->assertSame(3, $this->object->count());
    }
    
    public function testIsEmpty() {
        $this->assertTrue($this->object->isEmpty());
        
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->assertFalse($this->object->isEmpty());
    }
    
    public function testClear() {
        $this->object->clear();
        $this->assertSame(0, $this->object->count());
        
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->clear();
        $this->assertSame(0, $this->object->count());
    }
    
    public function testGetIterator() {
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        $actual = [];
        foreach ($this->object as $key => $value) {
            $actual[] = [$key, $value];
        }
        usort($actual, function($entry1, $entry2) {
            return $entry1[0] - $entry2[0];
        });
        $this->assertSame([[1, 1], [2, 2], [3, 3], [4, 4], [5, 5]], $actual);
    }
    
    public function testPut() {
        $this->object->put(null, null);
        $this->assertSame([[null, null]], $this->object->toArray());
        
        $this->object->clear();
        $this->assertSame(null, $this->object->put(1, 1));
        $this->assertSame(null, $this->object->put(2, 20));
        $this->assertSame(null, $this->object->put(3, 3));
        $this->assertSame(20, $this->object->put(2, 2));
        
        $actual = $this->object->toArray();
        usort($actual, function($entry1, $entry2) {
            return $entry1[0] - $entry2[0];
        });
        $this->assertSame([[1, 1], [2, 2], [3, 3]], $actual);
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
    
    public function testRemove() {
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        $this->assertSame(true, $this->object->remove(1));
        $this->assertSame(4, $this->object->count());
        $this->assertSameEntriesSet([[2, 2], [3, 3], [4, 4], [5, 5]], $this->object);
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        $this->assertSame(true, $this->object->remove(3));
        $this->assertSame(4, $this->object->count());
        $this->assertSameEntriesSet([[1, 1], [2, 2], [4, 4], [5, 5]], $this->object);
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        $this->assertSame(true, $this->object->remove(5));
        $this->assertSame(4, $this->object->count());
        $this->assertSameEntriesSet([[1, 1], [2, 2], [3, 3], [4, 4]], $this->object);
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        $this->assertSame(false, $this->object->remove(0));
        $this->assertSame(5, $this->object->count());
        $this->assertSameEntriesSet([[1, 1], [2, 2], [3, 3], [4, 4], [5, 5]], $this->object);
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        $this->assertSame(false, $this->object->remove(new \stdClass()));
        $this->assertSame(5, $this->object->count());
        $this->assertSameEntriesSet([[1, 1], [2, 2], [3, 3], [4, 4], [5, 5]], $this->object);
        
        $this->object->clear();
        $this->object->put(1, 1);
        $this->object->put(2, 2);
        $this->object->put(3, 3);
        $this->object->put(4, 4);
        $this->object->put(5, 5);
        $this->assertSame(false, $this->object->remove('3'));
        $this->assertSame(5, $this->object->count());
        $this->assertSameEntriesSet([[1, 1], [2, 2], [3, 3], [4, 4], [5, 5]], $this->object);
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
    
    public function testRehash() {
        ini_set('memory_limit', '512M');
        
        $numOfTrials = 10000;
        $expected = [];
        for($i = 0; $i < $numOfTrials; ++$i) {
            $this->object->put($i, $i);
            $expected[] = [$i, $i];
        }
        $actual = $this->object->toArray();
        usort($actual, function($entry1, $entry2) {
            return $entry1[0] - $entry2[0];
        });
        $this->assertSame($expected, $actual);
    }
}
