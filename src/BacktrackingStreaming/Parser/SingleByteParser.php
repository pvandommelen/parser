<?php


namespace PeterVanDommelen\Parser\BacktrackingStreaming\Parser;


use PeterVanDommelen\Parser\BacktrackingStreaming\BacktrackingStreamingParserInterface;
use PeterVanDommelen\Parser\Expression\Constant\ConstantExpressionResult;
use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Parser\InputStreamInterface;
use PeterVanDommelen\Parser\Parser\ParserInterface;

class SingleByteParser implements BacktrackingStreamingParserInterface
{
    /** @var string */
    private $string;

    private $result;

    /**
     * @param string $string
     */
    public function __construct($string)
    {
        if (strlen($string) !== 1) {
            throw new \Exception("Expected string to only be one long");
        }
        $this->string = $string;
        $this->result = new ConstantExpressionResult($string);
    }

    public function parseInputStreamWithBacktracking(InputStreamInterface $input, ExpressionResultInterface $previous_result = null)
    {
        if ($previous_result !== null) {
            return null;
        }

        if ($input->matchesChar($this->string) === false) {
            return null;
        }
        
        $input->move(1);

        return $this->result;
    }

}