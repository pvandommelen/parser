<?php


namespace PeterVanDommelen\Parser\BacktrackingStreaming\Compiler;


use PeterVanDommelen\Parser\BacktrackingStreaming\BacktrackingStreamingCompilerInterface;
use PeterVanDommelen\Parser\BacktrackingStreaming\Parser\AlternativeParser;
use PeterVanDommelen\Parser\Compiler\CompilerInterface;
use PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpression;
use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareTrait;

class AlternativeExpressionCompiler implements BacktrackingStreamingCompilerInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;

    public function compile(ExpressionInterface $expression)
    {
        /** @var $expression AlternativeExpression */
        return new AlternativeParser(array_map(array($this->getRecursiveHandler(), "compile"), $expression->getAlternatives()));
    }

}