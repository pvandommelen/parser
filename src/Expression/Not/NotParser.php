<?php


namespace PeterVanDommelen\Parser\Expression\Not;


use PeterVanDommelen\Parser\Expression\Constant\ConstantExpressionResult;
use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Parser\ParserInterface;

class NotParser implements ParserInterface
{
    /** @var ParserInterface */
    private $inner_parser;

    /** @var string */
    private $encoding;

    /**
     * @param ParserInterface $inner_parser
     * @param string $encoding
     */
    public function __construct(ParserInterface $inner_parser, $encoding)
    {
        $this->inner_parser = $inner_parser;
        $this->encoding = $encoding;
    }

    public function parse($string, ExpressionResultInterface $previous_result = null)
    {
        //no backtracking
        if ($previous_result !== null) {
            return null;
        }

        $inner_result = $this->inner_parser->parse($string);
        if ($inner_result !== null) {
            //there was a match, so we should not match
            return null;
        }

        // no match, return a single character
        $char = mb_substr($string, 0, 1, $this->encoding);
        if ($char === "") {
            //empty actually
            return null;
        }
        return new ConstantExpressionResult($char);
    }

}