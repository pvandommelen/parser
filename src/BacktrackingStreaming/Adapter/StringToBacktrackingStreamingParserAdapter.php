<?php


namespace PeterVanDommelen\Parser\BacktrackingStreaming\Adapter;


use PeterVanDommelen\Parser\BacktrackingStreaming\BacktrackingStreamingParserInterface;
use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Parser\InputStreamInterface;
use PeterVanDommelen\Parser\Parser\ParserInterface;

class StringToBacktrackingStreamingParserAdapter implements BacktrackingStreamingParserInterface
{
    /** @var ParserInterface */
    private $string_parser;

    /**
     * @param ParserInterface $string_parser
     */
    public function __construct(ParserInterface $string_parser)
    {
        $this->string_parser = $string_parser;
    }

    public function parseInputStreamWithBacktracking(InputStreamInterface $input, ExpressionResultInterface $previous_result = null)
    {
        if ($previous_result !== null) {
            return null;
        }

        $string = $input->getRemainingString();
        $result = $this->string_parser->parse($string);

        if ($result === null) {
            $input->move(-strlen($string));
            return null;
        }

        $input->move($result->getLength() - strlen($string));
        return $result;
    }


}