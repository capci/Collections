<?php
namespace Capci\Collections;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-11-21 at 16:31:59.
 */
class AbstractSetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractSet
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        require '../src/Capci/Collections/autoload.php';
        $this->object = new class extends AbstractSet {
            
            private $r = [];
            
            public function add($e): bool {
                foreach ($this->r as $element) {
                    if($this->elementsEquals($e, $element)) {
                        return false;
                    }
                }
                $this->r[] = $e;
                return true;
            }

            public function getIterator(): \Traversable {
                return new \ArrayIterator($this->r);
            }

            public function remove($e): bool {
                $index = -1;
                foreach ($this->r as $i => $element) {
                    if($this->elementsEquals($e, $element)) {
                        $index = $i;
                        break;
                    }
                }
                if($index === -1) {
                    return false;
                }
                array_splice($this->r, $index, 1);
                return true;
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
    
    private function assertSameSet(array $expected, array $actual) {
        $set = $this->object;
        
        $comparer = function($e1, $e2) use($set) {
            $h1 = $set->getEqualityComparer()->elementHashCode($e1);
            $h2 = $set->getEqualityComparer()->elementHashCode($e2);
            if($h1 !== $h2) {
                if($h1 > $h2) {
                    return 1;
                }
                return -1;
            }
            
            $h1 = $set->getEqualityComparer()->elementHashCode(gettype($e1));
            $h2 = $set->getEqualityComparer()->elementHashCode(gettype($e2));
            return $h1 - $h2;
        };
        usort($expected, $comparer);
        usort($actual, $comparer);
        $this->assertSame($expected, $actual);
    }

    public function testClear() {
        $this->object->clear();
        $this->assertSame(0, $this->object->count());
        
        $this->assertTrue($this->object->add(1));
        $this->assertTrue($this->object->add(2));
        $this->assertTrue($this->object->add(3));
        $this->object->clear();
        $this->assertSame(0, $this->object->count());
    }
    
    public function testAddAll() {
        $c = clone $this->object;
        
        $this->object->clear();
        $c->clear();
        $this->object->addAll($c);
        $this->assertSameSet([], $this->object->toArray());
        
        $this->object->clear();
        $c->clear();
        $c->add(1);
        $c->add(2);
        $c->add(3);
        $this->assertTrue($this->object->addAll($c));
        $this->assertSameSet([1, 2, 3], $this->object->toArray());
        
        $this->object->clear();
        $c->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $c->add(4);
        $c->add(5);
        $c->add(6);
        $this->assertTrue($this->object->addAll($c));
        $this->assertSameSet([1, 2, 3, 4, 5, 6], $this->object->toArray());
        
        $this->object->clear();
        $c->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $c->add(1);
        $c->add(2);
        $c->add(3);
        $this->assertFalse($this->object->addAll($c));
        $this->assertSameSet([1, 2, 3], $this->object->toArray());
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->assertTrue($this->object->addAll([4, 5, 6]));
        $this->assertSameSet([1, 2, 3, 4, 5, 6], $this->object->toArray());
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(3);
        $this->assertFalse($this->object->addAll([1, 2, 3]));
        $this->assertSameSet([1, 2, 3], $this->object->toArray());
    }
    
    public function testContains() {
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(null);
        $this->object->add(4);
        $this->object->add(5);
        $this->assertTrue($this->object->contains(1));
        $this->assertTrue($this->object->contains(4));
        $this->assertTrue($this->object->contains(5));
        $this->assertTrue($this->object->contains(null));
        
        $this->assertFalse($this->object->contains(0));
    }
    
    public function testContainsAll() {
        $c = clone $this->object;
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(null);
        $this->object->add(4);
        $this->object->add(5);
        
        $c->clear();
        $c->add(2);
        $c->add(null);
        $c->add(4);
        $this->assertTrue($this->object->containsAll($c));
        
        $c->clear();
        $this->assertTrue($this->object->containsAll($c));
        
        $c->clear();
        $c->add(1);
        $c->add(2);
        $c->add(5);
        $c->add(0);
        $this->assertFalse($this->object->containsAll($c));
    }
    
    public function testContainsAll2() {
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(null);
        $this->object->add(4);
        $this->object->add(5);
        
        $this->assertTrue($this->object->containsAll([2, null, 4]));
        
        $this->assertTrue($this->object->containsAll([]));
        
        $this->assertFalse($this->object->containsAll([1, 2, 5, 0]));
    }
    
    public function testRemoveAll() {
        $c = clone $this->object;
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(null);
        $this->object->add(4);
        $this->object->add(5);
        $c->clear();
        $c->add(2);
        $c->add(null);
        $c->add(4);
        $this->assertTrue($this->object->removeAll($c));
        $this->assertSameSet([1, 5], $this->object->toArray());
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(null);
        $this->object->add(4);
        $this->object->add(5);
        $c->clear();
        $this->assertFalse($this->object->removeAll($c));
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(null);
        $this->object->add(4);
        $this->object->add(5);
        $c->clear();
        $c->add(1);
        $c->add(2);
        $c->add(5);
        $c->add(0);
        $this->assertTrue($this->object->removeAll($c));
        $this->assertSameSet([null, 4], $this->object->toArray());
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(null);
        $this->object->add(4);
        $this->object->add(5);
        $c->clear();
        $c->add(0);
        $c->add(-1);
        $this->assertFalse($this->object->removeAll($c));
        $this->assertSameSet([1, 2, null, 4, 5], $this->object->toArray());
    }
    
    public function testRemoveAll2() {
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(null);
        $this->object->add(4);
        $this->object->add(5);
        $this->assertTrue($this->object->removeAll([2, null, 4]));
        $this->assertSameSet([1, 5], $this->object->toArray());
        
        $this->assertFalse($this->object->removeAll([]));
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(null);
        $this->object->add(4);
        $this->object->add(5);
        $this->assertTrue($this->object->removeAll([1, 2, 5, 0]));
        $this->assertSameSet([null, 4], $this->object->toArray());
        
        $this->object->clear();
        $this->object->add(1);
        $this->object->add(2);
        $this->object->add(null);
        $this->object->add(4);
        $this->object->add(5);
        $this->assertFalse($this->object->removeAll([0, -1]));
        $this->assertSameSet([1, 2, null, 4, 5], $this->object->toArray());
    }
    
    public function testFilter() {
        $this->object->addAll(range(-10, 10));
        $this->object->filter(function($e) {
            return $e % 2 === 0;
        });
        $this->assertSameSet([-10, -8, -6, -4, -2, 0, 2, 4, 6, 8, 10], $this->object->toArray());
    }
    
    public function testMap() {
        $r = range(-10, 10);
        $this->object->addAll($r);
        $this->object->map(function($e) {
            return intval($e / 2);
        });
        $this->assertSameSet(range(-5, 5), $this->object->toArray());
    }
}
