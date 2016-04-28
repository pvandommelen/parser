<?php


namespace PeterVanDommelen\Parser\Compiler;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Handler\LazyResult;
use PeterVanDommelen\Parser\Parser\ParserInterface;

class LazyParser extends LazyResult implements ParserInterface
{
    /**
     * @return ParserInterface
     */
    public function getParser() {
        return $this->getResult();
    }

    public function parse($string, ExpressionResultInterface $previous_result = null)
    {
        return $this->getParser()->parse($string, $previous_result);
    }

}