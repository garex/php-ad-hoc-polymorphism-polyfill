<?php
use Garex\Adhoc2p\Polycall;

class PolycallOneTimeInitTest extends PolycallTest
{

    private $counter = 0;

    /**
     * @dataProvider dataForContextRelatedMethodCalled
     */
    public function testContextRelatedMethodCalled($expected, $iterator)
    {
        parent::testContextRelatedMethodCalled($expected, $iterator);
        parent::testContextRelatedMethodCalled($expected, $iterator);
        parent::testContextRelatedMethodCalled($expected, $iterator);
    }

    public function callMe(Iterator $i)
    {
        static $_p = null;
        if (!is_null($_p)) {
            return $_p->go(func_get_args());
        }

        $this->assertEquals(1, ++$this->counter);

        $_p = (new Polycall($this))
            ->on(ArrayIterator::class)->to('callMe0')
            ->on(DirectoryIterator::class)->to(function($i) {
                return $this->callMe1($i);
            })
        ;

        return $_p->go(func_get_args());
    }
}