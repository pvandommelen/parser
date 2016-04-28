<?php


namespace PeterVanDommelen\Parser\Expression\Not;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;

class NotExpression implements ExpressionInterface
{
    /** @var ExpressionInterface */
    private $inner;

    /**
     * @param ExpressionInterface $inner
     */
    public function __construct(ExpressionInterface $inner)
    {
        $this->inner = $inner;
    }

    /**
     * @return ExpressionInterface
     */
    public function getExpression()
    {
        return $this->inner;
    }


}