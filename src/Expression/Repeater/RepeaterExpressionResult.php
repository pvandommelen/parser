<?php


namespace PeterVanDommelen\Parser\Expression\Repeater;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;

class RepeaterExpressionResult implements ExpressionResultInterface
{
    /** @var ExpressionResultInterface[] */
    private $results;

    /**
     * @param ExpressionResultInterface[] $results
     */
    public function __construct(array $results)
    {
        $this->results = $results;
    }

    /**
     * @return ExpressionResultInterface[]
     */
    public function getResults()
    {
        return $this->results;
    }

    public function getLength()
    {
        $length = 0;
        foreach ($this->results as $result) {
            $length += $result->getLength();
        }
        return $length;
    }

    public function getString()
    {
        $string = "";
        foreach ($this->results as $result) {
            $string .= $result->getString();
        }
        return $string;
    }


}