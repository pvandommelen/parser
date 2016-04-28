<?php


namespace PeterVanDommelen\Parser\Handler;


trait RecursionAwareTrait
{
    private $inner = null;

    public function setRecursiveHandler($handler)
    {
        $this->inner = $handler;
    }

    /**
     * @return static
     * @throws \Exception
     */
    protected function getRecursiveHandler() {
        if ($this->inner === null) {
            throw new \Exception("Not initialized; setRecursiveHandler is not called.");
        }
        return $this->inner;
    }
}