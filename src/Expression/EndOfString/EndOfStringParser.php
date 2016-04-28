<?php


namespace PeterVanDommelen\Parser\Expression\EndOfString;


use PeterVanDommelen\Parser\Expression\Constant\ConstantExpressionResult;
use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Parser\ParserInterface;

class EndOfStringParser implements ParserInterface
{
    public function parse($string, ExpressionResultInterface $previous_result = null)
    {
        if ($previous_result !== null) {
            return null;
        }
        if ($string !== "") {
            return null;
        }
        return new ConstantExpressionResult("");
    }


}