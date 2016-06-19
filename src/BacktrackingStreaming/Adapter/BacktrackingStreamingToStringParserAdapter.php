<?php


namespace PeterVanDommelen\Parser\BacktrackingStreaming\Adapter;


use PeterVanDommelen\Parser\BacktrackingStreaming\BacktrackingStreamingParserInterface;
use PeterVanDommelen\Parser\Parser\ParserInterface;
use PeterVanDommelen\Parser\Parser\StringInputStream;

class BacktrackingStreamingToStringParserAdapter implements ParserInterface
{
    private $inner;

    /**
     * @param BacktrackingStreamingParserInterface $inner
     */
    public function __construct(BacktrackingStreamingParserInterface $inner)
    {
        $this->inner = $inner;
    }

    public function parse($string)
    {
        $input = new StringInputStream($string);
        return $this->inner->parseInputStreamWithBacktracking($input, null);
    }

}