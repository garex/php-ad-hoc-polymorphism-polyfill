<?php
use Garex\Adhoc2p\Polycall;

class PolycallAutoToTest extends PolycallTest
{

    public function callMe(Iterator $i)
    {
        $_p = (new Polycall\AutoTo($this, __METHOD__))
            ->on(ArrayIterator::class)
            ->on(DirectoryIterator::class)
        ;
        return $_p->go(func_get_args());
    }

    public function callMe1(DirectoryIterator $i)
    {
        return 'DirectoryIterator';
    }
}