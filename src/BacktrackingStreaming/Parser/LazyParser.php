<?php


namespace PeterVanDommelen\Parser\BacktrackingStreaming\Parser;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Handler\LazyResult;
use PeterVanDommelen\Parser\BacktrackingStreaming\BacktrackingStreamingParserInterface;
use PeterVanDommelen\Parser\Parser\InputStreamInterface;
use PeterVanDommelen\Parser\Parser\ParserInterface;
use PeterVanDommelen\Parser\Parser\NoBacktrackingStreamingParserInterface;

class LazyParser extends LazyResult implements BacktrackingStreamingParserInterface
{
    /**
     * @return BacktrackingStreamingParserInterface
     */
    public function getParser() {
        return $this->getResult();
    }

    public function parseInputStreamWithBacktracking(InputStreamInterface $input, ExpressionResultInterface $previous_result = null)
    {
        return $this->getParser()->parseInputStreamWithBacktracking($input, $previous_result);
    }

}