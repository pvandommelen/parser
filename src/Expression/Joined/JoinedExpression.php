<?php


namespace PeterVanDommelen\Parser\Expression\Joined;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Expression\Repeater\RepeaterExpression;

class JoinedExpression extends RepeaterExpression implements ExpressionInterface
{
    /** @var ExpressionInterface */
    private $seperator;

    /**
     * @param ExpressionInterface $inner
     * @param ExpressionInterface $seperator
     * @param bool $is_lazy
     * @param int $minimum
     * @param int|null $maximum
     */
    public function __construct(ExpressionInterface $inner, ExpressionInterface $seperator, $is_lazy = false, $minimum = 0, $maximum = null)
    {
        parent::__construct($inner, $is_lazy, $minimum, $maximum);
        $this->seperator = $seperator;
    }

    /**
     * @return ExpressionInterface
     */
    public function getSeperator()
    {
        return $this->seperator;
    }


}