<?php


namespace PeterVanDommelen\Parser\BacktrackingStreaming\Parser;


use PeterVanDommelen\Parser\BacktrackingStreaming\BacktrackingStreamingParserInterface;

abstract class AbstractRepeaterParser implements BacktrackingStreamingParserInterface
{
    /** @var BacktrackingStreamingParserInterface */
    protected $inner;

    /** @var int */
    protected $minimum;

    /** @var int */
    protected $maximum;

    /**
     * @param BacktrackingStreamingParserInterface $inner
     * @param int $minimum
     * @param int|null $maximum
     */
    public function __construct(BacktrackingStreamingParserInterface $inner, $minimum = 0, $maximum = null)
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