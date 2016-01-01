<?php
use Garex\Adhoc2p\Polycall;

class PolycallTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataForContextRelatedMethodCalled
     */
    public function testContextRelatedMethodCalled($expected, $iterator)
    {
        $this->assertEquals($expected, $this->callMe($iterator));
    }

    public function dataForContextRelatedMethodCalled()
    {
        return [
            ['ArrayIterator', new ArrayIterator([])],
            ['DirectoryIterator', new DirectoryIterator('.')],
        ];
    }

    public function callMe(Iterator $i)
    {
        $_p = (new Polycall($this))
            ->on(ArrayIterator::class)->to('callMe0')
            ->on(DirectoryIterator::class)->to('callMe1')
        ;
        return $_p->go(func_get_args());
    }

    public function callMe0(ArrayIterator $i)
    {
        return 'ArrayIterator';
    }

    public function callMe1(DirectoryIterator $i)
    {
        return 'DirectoryIterator';
    }
}