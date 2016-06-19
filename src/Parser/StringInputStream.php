<?php


namespace PeterVanDommelen\Parser\Parser;


class StringInputStream implements InputStreamInterface
{
    private $string;

    private $position;

    /**
     * @param $string
     */
    public function __construct($string)
    {
        $this->string = $string;
        $this->rewind();
    }
    
    private function rewind() {
        $this->position = 0;
    }

    public function move($amount)
    {
        $this->position += $amount;
    }

    public function matchesString($string)
    {
        return StringUtil::slice($this->string, $this->position, strlen($string)) === $string;
    }

    public function matchesChar($char)
    {
        return isset($this->string[$this->position]) === true && $this->string[$this->position] === $char;
    }

    public function isAtEnd()
    {
        return isset($this->string[$this->position]) === false;
    }

    public function getRemainingString($length = null)
    {
        $string = StringUtil::slice($this->string, $this->position, $length);
        $this->position += strlen($string);
        return $string;
    }

}