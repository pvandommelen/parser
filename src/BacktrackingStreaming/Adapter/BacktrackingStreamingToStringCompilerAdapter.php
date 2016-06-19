<?php


namespace PeterVanDommelen\Parser\BacktrackingStreaming\Adapter;


use PeterVanDommelen\Parser\BacktrackingStreaming\BacktrackingStreamingCompilerInterface;
use PeterVanDommelen\Parser\BacktrackingStreaming\Adapter\BacktrackingStreamingToStringParserAdapter;
use PeterVanDommelen\Parser\Compiler\CompilerInterface;
use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Parser\ParserInterface;

class BacktrackingStreamingToStringCompilerAdapter implements CompilerInterface
{
    /** @var BacktrackingStreamingCompilerInterface */
    private $streaming_compiler;

    /**
     * @param BacktrackingStreamingCompilerInterface $streaming_compiler
     */
    public function __construct(BacktrackingStreamingCompilerInterface $streaming_compiler)
    {
        $this->streaming_compiler = $streaming_compiler;
    }

    public function compile(ExpressionInterface $expression)
    {
        return new BacktrackingStreamingToStringParserAdapter($this->streaming_compiler->compile($expression));
    }

}