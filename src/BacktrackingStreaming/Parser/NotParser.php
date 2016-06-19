<?php


namespace PeterVanDommelen\Parser\BacktrackingStreaming\Parser;


use PeterVanDommelen\Parser\BacktrackingStreaming\BacktrackingStreamingParserInterface;
use PeterVanDommelen\Parser\Expression\Constant\ConstantExpressionResult;
use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Parser\InputStreamInterface;
use PeterVanDommelen\Parser\Parser\ParserInterface;

class NotParser implements BacktrackingStreamingParserInterface
{
    /** @var BacktrackingStreamingParserInterface */
    private $inner_parser;

    /** @var string */
    private $encoding;

    /**
     * @param BacktrackingStreamingParserInterface $inner_parser
     * @param string $encoding
     */
    public function __construct(BacktrackingStreamingParserInterface $inner_parser, $encoding)
    {
        $this->inner_parser = $inner_parser;
        $this->encoding = $encoding;
    }

    public function parseInputStreamWithBacktracking(InputStreamInterface $input, ExpressionResultInterface $previous_result = null)
    {
        //no backtracking
        if ($previous_result !== null) {
            return null;
        }

        $inner_result = $this->inner_parser->parseInputStreamWithBacktracking($input);
        if ($inner_result !== null) {
            //there was a match, so we should not match
            $input->move(-$inner_result->getLength());
            return null;
        }

        $string = $input->getRemainingString(4); //what is the max?
        $char = mb_substr($string, 0, 1, $this->encoding);

        // no match, return a single character
        if ($char === "") {
            //empty actually
            $input->move(-strlen($string));
            return null;
        }
        
        $input->move(strlen($char) - strlen($string));
        return new ConstantExpressionResult($char);
    }

}