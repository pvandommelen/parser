<?php


namespace PeterVanDommelen\Parser\Expression\Constant;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Parser\ParserInterface;

class SingleByteParser implements ParserInterface
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

    public function parse($string, ExpressionResultInterface $previous_result = null)
    {
        if ($previous_result !== null) {
            return null;
        }

        if (isset($string[0]) === false || $string[0] !== $this->string) {
            return null;
        }

        return $this->result;
    }
}