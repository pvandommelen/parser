<?php


namespace PeterVanDommelen\Parser\Expression\Concatenated;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;

class ConcatenatedExpressionResult implements ExpressionResultInterface
{
    /** @var ExpressionResultInterface[] */
    private $part_results;

    /**
     * @param ExpressionResultInterface[] $part_results
     */
    public function __construct(array $part_results)
    {
        $this->part_results = $part_results;
    }

    /**
     * @return ExpressionResultInterface[]
     */
    public function getParts()
    {
        return $this->part_results;
    }

    /**
     * @param string|int $key
     * @return ExpressionResultInterface
     */
    public function getPart($key) {
        return $this->part_results[$key];
    }

    public function getLength()
    {
        $length = 0;
        foreach ($this->part_results as $part_result) {
            $length += $part_result->getLength();
        }
        return $length;
    }

    public function getString()
    {
        $string = "";
        foreach ($this->part_results as $part_result) {
            $string .= $part_result->getString();
        }
        return $string;
    }


}