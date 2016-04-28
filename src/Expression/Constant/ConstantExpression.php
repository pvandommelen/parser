<?php


namespace PeterVanDommelen\Parser\Expression\Constant;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;

class ConstantExpression implements ExpressionInterface
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

}