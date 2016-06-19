<?php


namespace PeterVanDommelen\Parser\BacktrackingStreaming\Compiler;


use PeterVanDommelen\Parser\BacktrackingStreaming\BacktrackingStreamingCompilerInterface;
use PeterVanDommelen\Parser\Compiler\CompilerInterface;
use PeterVanDommelen\Parser\BacktrackingStreaming\Parser\EndOfStringParser;
use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Parser\ParserInterface;

class EndOfStringCompiler implements BacktrackingStreamingCompilerInterface
{
    public function compile(ExpressionInterface $expression)
    {
        return new EndOfStringParser();
    }

}