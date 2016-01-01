<?php
namespace Garex\Adhoc2p\Polycall;

trait Inside
{

    private function polycallGo(array $arguments, $method, callable $init)
    {
        static $methods = [];

        if (empty($methods[$method])) {
            $methods[$method] = $init();
        }

        return $methods[$method]->go($arguments);
    }
}