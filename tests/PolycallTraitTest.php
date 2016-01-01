<?php
use Garex\Adhoc2p\Polycall;

class PolycallTraitTest extends PolycallAutoToTest
{
    use Polycall\Inside;

    public function callMe(Iterator $i)
    {
        return $this->polycallGo(func_get_args(), $method = __METHOD__, function() use ($method) {
            return (new Polycall\AutoTo($this, $method))
                ->on(ArrayIterator::class)
                ->on(DirectoryIterator::class)
            ;
        });
    }
}
