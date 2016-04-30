<?php


namespace PeterVanDommelen\Parser\Expression\Regex;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Parser\ParserInterface;

class RegexParser implements ParserInterface
{
    private $regex;

    /**
     * @param $regex
     */
    public function __construct($regex)
    {
        $this->regex = self::wrapRegex($regex);
    }

    private static function wrapRegex($regex) {
        $regex = str_replace('/', '\\/', $regex);
        $regex = "/^(?:" . $regex . ")/s";
        return $regex;
    }

    public function parse($string, ExpressionResultInterface $previous_result = null)
    {
        if ($previous_result !== null) {
            return null;
        }
        $count = preg_match($this->regex, $string, $matches);

        if ($count === 0) {
            return null;
        }
        return new RegexExpressionResult($matches);
    }

}