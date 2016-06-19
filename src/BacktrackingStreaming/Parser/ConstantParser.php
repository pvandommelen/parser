<?php


namespace PeterVanDommelen\Parser\BacktrackingStreaming\Parser;


use PeterVanDommelen\Parser\Expression\Constant\ConstantExpressionResult;
use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\BacktrackingStreaming\BacktrackingStreamingParserInterface;
use PeterVanDommelen\Parser\Parser\InputStreamInterface;

class ConstantParser implements BacktrackingStreamingParserInterface
{
    /** @var string */
    private $string;

    private $result;

    /**
     * @param string $string
     */
    public function __construct($string)
    {
        $this->string = $string;
        $this->result = new ConstantExpressionResult($string);
    }

    public function parseInputStreamWithBacktracking(InputStreamInterface $input, ExpressionResultInterface $previous_result = null)
    {
        if ($previous_result !== null) {
            return null;
        }

        $constant = $this->string;

        if ($input->matchesString($constant) === false) {
            return null;
        }

        $input->move(strlen($constant));

        return $this->result;
    }


}