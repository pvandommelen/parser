<?php


namespace PeterVanDommelen\Parser\BacktrackingStreaming;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Parser\InputStreamInterface;

interface BacktrackingStreamingParserInterface
{

    /**
     * If match is not found return null and rewinds the input.
     * If found, return a result and the input will have advanced past the matched string
     *
     * @param InputStreamInterface $input
     * @param ExpressionResultInterface|null $previous_result
     * @return ExpressionResultInterface|null
     */
    public function parseInputStreamWithBacktracking(InputStreamInterface $input, ExpressionResultInterface $previous_result = null);

}