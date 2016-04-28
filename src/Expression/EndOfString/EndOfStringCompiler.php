<?php


namespace PeterVanDommelen\Parser\Expression\EndOfString;


use PeterVanDommelen\Parser\Compiler\CompilerInterface;
use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Parser\ParserInterface;

class EndOfStringCompiler implements CompilerInterface
{
    public function compile(ExpressionInterface $expression)
    {
        return new EndOfStringParser();
    }

}