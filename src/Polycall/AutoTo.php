<?php
namespace Garex\Adhoc2p\Polycall;
use Garex\Adhoc2p\Polycall;

/**
 * Automatically calls to by knowing method name
 */
class AutoTo extends Polycall
{
    private $method;

    private $onLenth = 0;

    public function __construct($instance, $method)
    {
        parent::__construct($instance);
        $this->method = end(explode('::', $method));
    }

    protected function onArray(array $arguments)
    {
        parent::onArray($arguments);
        $this->to($this->method . $this->onLenth++);
    }
}