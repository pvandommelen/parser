<?php


namespace PeterVanDommelen\Parser\Parser;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;

interface ParserInterface
{
    /**
     * @param string $string
     * @param ExpressionResultInterface|null $previous_result
     * @return ExpressionResultInterface
     */
    public function parse($string, ExpressionResultInterface $previous_result = null);
}