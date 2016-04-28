<?php


namespace PeterVanDommelen\Parser\Expression\Repeater;


use PeterVanDommelen\Parser\Parser\ParserInterface;

abstract class AbstractRepeaterParser implements ParserInterface
{
    /** @var ParserInterface */
    protected $inner;

    /** @var int */
    protected $minimum;

    /** @var int */
    protected $maximum;

    /**
     * @param ParserInterface $inner
     * @param int $minimum
     * @param int|null $maximum
     */
    public function __construct(ParserInterface $inner, $minimum = 0, $maximum = null)
    {
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
        $this->minimum = $minimum;
        $this->maximum = $maximum;
    }
}