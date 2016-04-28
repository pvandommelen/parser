<?php


namespace PeterVanDommelen\Parser\Compiler;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\HandlerInterface;
use PeterVanDommelen\Parser\Parser\ParserInterface;

interface CompilerInterface
{
    /**
     * @param ExpressionInterface $expression
     * @return ParserInterface
     */
    public function compile(ExpressionInterface $expression);
}