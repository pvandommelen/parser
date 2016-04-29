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