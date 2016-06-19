<?php


namespace PeterVanDommelen\Parser\BacktrackingStreaming\Adapter;


use PeterVanDommelen\Parser\BacktrackingStreaming\BacktrackingStreamingCompilerInterface;
use PeterVanDommelen\Parser\BacktrackingStreaming\Adapter\StringToBacktrackingStreamingParserAdapter;
use PeterVanDommelen\Parser\Compiler\CompilerInterface;
use PeterVanDommelen\Parser\Expression\ExpressionInterface;

class StringToBacktrackingStreamingCompilerAdapter implements BacktrackingStreamingCompilerInterface
{
    /** @var CompilerInterface */
    private $string_parser_compiler;

    /**
     * @param CompilerInterface $string_parser_compiler
     */
    public function __construct(CompilerInterface $string_parser_compiler)
    {
        $this->string_parser_compiler = $string_parser_compiler;
    }

    public function compile(ExpressionInterface $expression)
    {
        return new StringToBacktrackingStreamingParserAdapter($this->string_parser_compiler->compile($expression));
    }

}