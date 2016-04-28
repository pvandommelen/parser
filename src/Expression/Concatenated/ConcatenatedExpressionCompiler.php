<?php


namespace PeterVanDommelen\Parser\Expression\Concatenated;


use PeterVanDommelen\Parser\Compiler\CompilerInterface;
use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareTrait;

class ConcatenatedExpressionCompiler implements CompilerInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;
    
    public function compile(ExpressionInterface $expression)
    {
        /** @var $expression \PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpression */
        return new \PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedParser(array_map(array($this->getRecursiveHandler(), "compile"), $expression->getParts()));
    }

}