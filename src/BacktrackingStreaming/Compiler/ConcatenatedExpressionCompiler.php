<?php


namespace PeterVanDommelen\Parser\BacktrackingStreaming\Compiler;


use PeterVanDommelen\Parser\BacktrackingStreaming\BacktrackingStreamingCompilerInterface;
use PeterVanDommelen\Parser\BacktrackingStreaming\Parser\ConcatenatedParser;
use PeterVanDommelen\Parser\Compiler\CompilerInterface;
use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareTrait;

class ConcatenatedExpressionCompiler implements BacktrackingStreamingCompilerInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;
    
    public function compile(ExpressionInterface $expression)
    {
        /** @var $expression \PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpression */
        return new ConcatenatedParser(array_map(array($this->getRecursiveHandler(), "compile"), $expression->getParts()));
    }

}