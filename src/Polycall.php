<?php
namespace Garex\Adhoc2p;

/**
 * Some change
 * Another
 */
class Polycall
{
    private $instance;

    private $ons = [];
    private $tos = [];

    public function __construct($instance)
    {
        $this->instance = $instance;
    }

    public function on()
    {
        $this->onArray(func_get_args());
        return $this;
    }

    protected function onArray(array $arguments)
    {
        $this->ons[] = $arguments;
    }

    public function to($method)
    {
        if (is_string($method)) {
            $method = [$this->instance, $method];
        }
        $this->tos[] = $method;
        return $this;
    }

    public function go(array $arguments)
    {
        foreach ($this->ons as $o => $on) {
            foreach ($on as $t => $type) {
                if ($arguments[$t] instanceof $type) {
                    // ok
                } else {
                    continue 2;
                }
            }
            break;
        }
        return call_user_func_array($this->tos[$o], $arguments);
    }
}
