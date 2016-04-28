<?php


namespace PeterVanDommelen\Parser\Expression\Regex;
use PeterVanDommelen\Parser\Expression\ExpressionInterface;

/**
 * Expression class for a regular expression
 */
class RegexExpression implements ExpressionInterface
{
    private $regex;

    /**
     * @param string $regex
     */
    public function __construct($regex)
    {
        $this->regex = $regex;
    }

    /**
     * @return string
     */
    public function getRegex()
    {
        return $this->regex;
    }
}