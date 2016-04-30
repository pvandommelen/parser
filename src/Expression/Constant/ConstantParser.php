<?php


namespace PeterVanDommelen\Parser\Expression\Constant;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Parser\ParserInterface;
use PeterVanDommelen\Parser\Parser\StringUtil;

class ConstantParser implements ParserInterface
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

    public function parse($string, ExpressionResultInterface $previous_result = null)
    {
        if ($previous_result !== null) {
            return null;
        }

        $constant = $this->string;

        if (StringUtil::slice($string, 0, strlen($constant)) !== $constant) {
            return null;
        }
        return $this->result;
    }

}