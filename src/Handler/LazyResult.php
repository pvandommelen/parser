<?php


namespace PeterVanDommelen\Parser\Handler;


class LazyResult
{
    private $result = null;

    /**
     * @return static
     */
    public function getResult()
    {
        if (memory_get_usage(true) > 5342177) {
            throw new \Exception("Mem usage");
        }
        if ($this->result === null) {
            throw new \Exception("Result is not yet set");
        }
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        if ($this->result !== null) {
            throw new \Exception("Result cannot be set twice");
        }
        $this->result = $result;
    }


}