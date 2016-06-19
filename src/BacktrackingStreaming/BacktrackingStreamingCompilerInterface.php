<?php


namespace PeterVanDommelen\Parser\BacktrackingStreaming;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;

interface BacktrackingStreamingCompilerInterface
{
    /**
     * @param ExpressionInterface $expression
     * @return BacktrackingStreamingParserInterface
     */
    public function compile(ExpressionInterface $expression);
}