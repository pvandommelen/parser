<?php


namespace PeterVanDommelen\Parser\Parser;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;

interface ParserInterface
{
    /**
     * @param string $string
     * @return ExpressionResultInterface|null
     */
    public function parse($string);
}