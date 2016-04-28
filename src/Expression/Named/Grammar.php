<?php


namespace PeterVanDommelen\Parser\Expression\Named;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;

class Grammar
{
    /** @var ExpressionInterface[] */
    private $expressions;

    /**
     * @param \PeterVanDommelen\Parser\Expression\ExpressionInterface[] $expressions
     */
    public function __construct(array $expressions)
    {
        $this->expressions = $expressions;
    }

    /**
     * @param string $name
     * @return ExpressionInterface
     */
    public function getExpression($name) {
        return $this->expressions[$name];
    }
}