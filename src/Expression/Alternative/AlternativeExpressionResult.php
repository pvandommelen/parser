<?php


namespace PeterVanDommelen\Parser\Expression\Alternative;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;

class AlternativeExpressionResult implements ExpressionResultInterface
{
    /** @var ExpressionResultInterface */
    private $part_result;

    /** @var string|int */
    private $key;

    /**
     * @param ExpressionResultInterface $part_result
     * @param int|string $key
     */
    public function __construct(ExpressionResultInterface $part_result, $key)
    {
        $this->part_result = $part_result;
        $this->key = $key;
    }

    /**
     * @return ExpressionResultInterface
     */
    public function getResult()
    {
        return $this->part_result;
    }

    /**
     * @return int|string
     */
    public function getKey()
    {
        return $this->key;
    }

    public function getLength()
    {
        return $this->part_result->getLength();
    }

    public function getString()
    {
        return $this->part_result->getString();
    }

}