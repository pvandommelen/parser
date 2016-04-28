<?php


namespace PeterVanDommelen\Parser\Expression\Concatenated;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;

class ConcatenatedExpression implements ExpressionInterface
{
    private $parts;

    /**
     * @param ExpressionInterface[] $parts
     */
    public function __construct($parts)
    {
        $this->parts = $parts;
    }

    /**
     * @return ExpressionInterface[]
     */
    public function getParts()
    {
        return $this->parts;
    }

}