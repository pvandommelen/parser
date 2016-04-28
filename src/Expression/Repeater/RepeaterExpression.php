<?php


namespace PeterVanDommelen\Parser\Expression\Repeater;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;

class RepeaterExpression implements ExpressionInterface
{
    /** @var ExpressionInterface */
    protected $inner;

    /** @var bool */
    private $is_lazy;

    /** @var int */
    protected $minimum;

    /** @var int */
    protected $maximum;

    /**
     * @param ExpressionInterface $inner
     * @param bool $is_lazy
     * @param int $minimum
     * @param int|null $maximum
     */
    public function __construct(ExpressionInterface $inner, $is_lazy = false, $minimum = 0, $maximum = null)
    {
        if (is_bool($is_lazy) === false) {
            throw new \Exception("Expected is_lazy argument to be of type boolean");
        }
        if (is_int($minimum) === false || $minimum < 0) {
            throw new \Exception("Minimum should be a positive integer");
        }
        if ($maximum === null) {
            $maximum = PHP_INT_MAX;
        }
        if (is_int($maximum) === false || $maximum < $minimum) {
            throw new \Exception("Maximum should be an integer larger than minimum");
        }

        $this->inner = $inner;
        $this->is_lazy = $is_lazy;
        $this->minimum = $minimum;
        $this->maximum = $maximum;
    }

    /**
     * @return \PeterVanDommelen\Parser\Expression\ExpressionInterface
     */
    public function getExpression()
    {
        return $this->inner;
    }

    /**
     * @return boolean
     */
    public function isLazy()
    {
        return $this->is_lazy;
    }

    /**
     * @return int
     */
    public function getMinimum()
    {
        return $this->minimum;
    }

    /**
     * @return int
     */
    public function getMaximum()
    {
        return $this->maximum;
    }
}