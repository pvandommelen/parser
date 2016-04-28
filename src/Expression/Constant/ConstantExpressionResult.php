<?php


namespace PeterVanDommelen\Parser\Expression\Constant;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;

class ConstantExpressionResult implements ExpressionResultInterface
{
    private $string;

    /**
     * @param string $string
     */
    public function __construct($string)
    {
        $this->string = $string;
    }

    /**
     * @return string
     */
    public function getString()
    {
        return $this->string;
    }

    public function getLength()
    {
        return strlen($this->string);
    }

}