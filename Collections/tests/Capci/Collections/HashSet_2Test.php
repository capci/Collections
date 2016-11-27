<?php
namespace Capci\Collections;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-11-21 at 16:31:59.
 */
class HashSet_2Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HashSet
     */
    protected $object;
    
    protected $elements;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        require '../src/Capci/Collections/autoload.php';
        $this->object = new HashSet();
        $this->elements = [];
        $this->object->add($this->elements[] = false);
        $this->object->add($this->elements[] = true);
        $this->object->add($this->elements[] = 0);
        $this->object->add($this->elements[] = 123);
        $this->object->add($this->elements[] = PHP_INT_MAX);
        $this->object->add($this->elements[] = PHP_INT_MIN);
        $this->object->add($this->elements[] = 0.0);
        $this->object->add($this->elements[] = 1.23);
        //$this->object->add($this->elements[] = NAN);
        $this->object->add($this->elements[] = INF);
        $this->object->add($this->elements[] = '');
        $this->object->add($this->elements[] = 'test');
        $this->object->add($this->elements[] = new \stdClass());
        $this->object->add($this->elements[] = function() {});
        $this->object->add($this->elements[] = new class extends \stdClass {});
        $this->object->add($this->elements[] = fopen("php://output", "w"));
        $r = fopen("php://output", "w");
        fclose($r);
        $this->object->add($this->elements[] = $r);
        $this->object->add($this->elements[] = null);
        $this->object->add($this->elements[] = []);
        $this->object->add($this->elements[] = [true, 1, 1.23, 'foo', new \stdClass(), fopen("php://output", "w"), null, ['a', 'b', 3]]);
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
    
    public function testCount() {
        $this->assertSame(count($this->elements), $this->object->count());
        $this->assertSame(count($this->elements), count($this->object));
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
        $this->assertSameSet($this->elements, $this->object->toArray());
    }
    
    public function testAdd() {
        $this->assertTrue($this->object->add($this->elements[] = 'bar'));
        $this->assertSameSet($this->elements, $this->object->toArray());
    }
    
    public function testAddAll() {
        $c = new HashSet();
        $c->add($this->elements[] = 'bar');
        $c->add($this->elements[] = 'bar2');
        $this->assertTrue($this->object->addAll($c));
        $this->assertSameSet($this->elements, $this->object->toArray());
    }
    
    public function testContains() {
        $index = mt_rand(0, $this->object->count() - 1);
        $this->assertTrue($this->object->contains($this->elements[$index]));
        
        $this->assertFalse($this->object->contains('bar'));
    }
    
    public function testContainsAll() {
        $this->assertTrue($this->object->containsAll($this->object));
        
        $c = new HashSet();
        $c->add('bar');
        $this->assertFalse($this->object->containsAll($c));
    }
    
    public function testRemove() {
        $index = mt_rand(0, $this->object->count() - 1);
        $this->assertTrue($this->object->remove($this->elements[$index]));
        
        array_splice($this->elements, $index, 1);
        $this->assertSameSet($this->elements, $this->object->toArray());
        
        $this->assertFalse($this->object->remove(-1));
    }
    
    public function testRemoveAll() {
        $c = new HashSet();
        $c->add('bar');
        $this->assertFalse($this->object->removeAll($c));
        
        $this->assertTrue($this->object->removeAll($this->object));
        $this->assertTrue($this->object->isEmpty());
    }
}
