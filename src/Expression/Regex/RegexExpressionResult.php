<?php


namespace PeterVanDommelen\Parser\Expression\Regex;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;

class RegexExpressionResult implements ExpressionResultInterface
{
    /** @var array */
    private $matches;

    /**
     * @param array $matches
     */
    public function __construct(array $matches)
    {
        $this->matches = $matches;
    }


    public function getLength()
    {
        return strlen($this->matches[0]);
    }

    public function getString()
    {
        return $this->matches[0];
    }

    /**
     * @return string[]
     */
    public function getMatches()
    {
        return $this->matches;
    }

}