<?php
namespace Capci\Collections;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-11-21 at 16:31:59.
 */
class HashMap_2Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HashMap
     */
    protected $object;
    
    protected $elements;
    protected $entries;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        require '../src/Capci/Collections/autoload.php';
        $this->object = new HashMap();
        
        $this->elements = [];
        $this->elements[] = false;
        $this->elements[] = true;
        $this->elements[] = 0;
        $this->elements[] = 123;
        $this->elements[] = PHP_INT_MAX;
        $this->elements[] = PHP_INT_MIN;
        $this->elements[] = 0.0;
        $this->elements[] = 1.23;
        //$this->elements[] = NAN;
        $this->elements[] = INF;
        $this->elements[] = '';
        $this->elements[] = 'test';
        $this->elements[] = new \stdClass();
        $this->elements[] = function() {};
        $this->elements[] = new class extends \stdClass{};
        $this->elements[] = fopen("php://output", "w");
        $r = fopen("php://output", "w");
        fclose($r);
        $this->elements[] = $r;
        $this->elements[] = null;
        $this->elements[] = [];
        $this->elements[] = [true, 1, 1.23, 'foo', new \stdClass(), fopen("php://output", "w"), null, ['a', 'b', 3]];
        
        $this->entries = [];
        $elements = $this->elements;
        foreach ($this->elements as $i => $element) {
            $index = mt_rand(0, count($elements)- 1);
            $this->entries[] = [$element, $elements[$index]];
            $this->object->put($element, $elements[$index]);
        }
    }
    
    private function assertSameEntries(array $expected, array $actual) {
        $map = $this->object;
        
        $comparer = function($entry1, $entry2) use($map) {
            $h1 = $map->getEqualityComparer()->elementHashCode($entry1[0]);
            $h2 = $map->getEqualityComparer()->elementHashCode($entry2[0]);
            if($h1 !== $h2) {
                if($h1 > $h2) {
                    return 1;
                }
                return -1;
            }
            
            $h1 = $map->getEqualityComparer()->elementHashCode(gettype($entry1[0]));
            $h2 = $map->getEqualityComparer()->elementHashCode(gettype($entry2[0]));
            return $h1 - $h2;
        };
        usort($expected, $comparer);
        usort($actual, $comparer);
        $this->assertSame($expected, $actual);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    public function testCount() {
        $this->assertSame(count($this->entries), $this->object->count());
        $this->assertSame(count($this->entries), count($this->object));
    }
    
    public function testIsEmpty() {
        $this->assertFalse($this->object->isEmpty());
    }
    
    public function testClear() {
        $this->object->clear();
        $this->assertTrue($this->object->isEmpty());
        foreach ($this->object as $e) {
            $this->fail();
        }
    }
    
    public function testToArray() {
        $actual = $this->object->toArray();
        $map = $this->object;
        
        $this->assertSameEntries($this->entries, $actual);
    }
    
    public function testPut() {
        $this->object->put('bar', 999);
        $this->entries[] = ['bar', 999];
        $this->assertSameEntries($this->entries, $this->object->toArray());
    }
    
    public function testPutAll() {
        $m = new HashMap();
        $m->put('bar', 999);
        $m->put('bar2', 1000);
        $this->entries[] = ['bar', 999];
        $this->entries[] = ['bar2', 1000];
        $this->object->putAll($m);
        $this->assertSameEntries($this->entries, $this->object->toArray());
    }
    
    public function testGet() {
        $index = mt_rand(0, $this->object->count() - 1);
        $key = $this->entries[$index][0];
        $expected = $this->entries[$index][1];
        $this->assertSame($expected, $this->object->get($key));
        
        try {
            $this->object->get(-1);
            $this->fail();
        } catch (\OutOfRangeException $ex) {
        }
    }
    
    public function testGetOrDefault() {
        $index = mt_rand(0, $this->object->count() - 1);
        $key = $this->entries[$index][0];
        $expected = $this->entries[$index][1];
        $this->assertSame($expected, $this->object->getOrDefault($key));
        
        $this->assertSame('test', $this->object->getOrDefault(-1, 'test'));
    }
    
    public function testRemove() {
        $index = mt_rand(0, $this->object->count() - 1);
        $key = $this->entries[$index][0];
        
        $this->assertTrue($this->object->remove($key));
        array_splice($this->entries, $index, 1);
        
        $this->assertSameEntries($this->entries, $this->object->toArray());
        
        $this->assertFalse($this->object->remove(-1));
        $this->assertSameEntries($this->entries, $this->object->toArray());
    }
    
    public function testContainsKey() {
        $index = mt_rand(0, $this->object->count() - 1);
        $key = $this->entries[$index][0];
        $this->assertTrue($this->object->containsKey($key));
        
        $this->assertFalse($this->object->containsKey(-1));
    }
    
    public function testContainsValue() {
        $index = mt_rand(0, $this->object->count() - 1);
        $value = $this->entries[$index][1];
        $this->assertTrue($this->object->containsValue($value));
        
        $this->assertFalse($this->object->containsValue(-1));
    }
    
    public function testKeySet() {
        $actual = $this->object->keySet()->toArray();
        $map = $this->object;
        $comparer = function($e1, $e2) use($map) {
            $h1 = $map->getEqualityComparer()->elementHashCode($e1);
            $h2 = $map->getEqualityComparer()->elementHashCode($e2);
            if($h1 !== $h2) {
                if($h1 > $h2) {
                    return 1;
                }
                return -1;
            }
            
            $h1 = $map->getEqualityComparer()->elementHashCode(gettype($e1));
            $h2 = $map->getEqualityComparer()->elementHashCode(gettype($e2));
            if($h1 > $h2) {
                return 1;
            }
            if($h1 < $h2) {
                return -1;
            }
            return 0;
        };
        usort($actual, $comparer);
        usort($this->elements, $comparer);
        $this->assertSame($this->elements, $actual);
    }
    
    public function testOffsetSet() {
        $this->object['bar'] = 999;
        $this->entries[] = ['bar', 999];
        $this->assertSameEntries($this->entries, $this->object->toArray());
    }
    
    public function testOffsetUnset() {
        $index = mt_rand(0, $this->object->count() - 1);
        $key = $this->entries[$index][0];
        
        unset($this->object[$key]);
        array_splice($this->entries, $index, 1);
        
        $this->assertSameEntries($this->entries, $this->object->toArray());
        
        unset($this->object[-1]);
        $this->assertSameEntries($this->entries, $this->object->toArray());
    }
    
    public function testOffsetGet() {
        $index = mt_rand(0, $this->object->count() - 1);
        $key = $this->entries[$index][0];
        $expected = $this->entries[$index][1];
        $this->assertSame($expected, $this->object[$key]);
        
        try {
            $this->object[-1];
            $this->fail();
        } catch (\OutOfRangeException $ex) {
        }
    }
    
    public function testOffsetExists() {
        $index = mt_rand(0, $this->object->count() - 1);
        $key = $this->entries[$index][0];
        
        if($this->object->get($key) === null) {
            $this->assertFalse(isset($this->object[$key]));
        } else {
            $this->assertTrue(isset($this->object[$key]));
        }
        
        $this->assertFalse(isset($this->object[-1]));
    }
    
    public function testFilter() {
        $this->object->filter(function($key, $value) {
            return is_numeric($value);
        });
        $expected = [];
        foreach ($this->entries as list($key, $value)) {
            if(is_numeric($value)) {
                $expected[] = [$key, $value];
            }
        }
        $this->assertSameEntries($expected, $this->object->toArray());
    }
}
