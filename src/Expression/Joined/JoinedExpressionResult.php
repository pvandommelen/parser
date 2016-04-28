<?php


namespace PeterVanDommelen\Parser\Expression\Joined;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;

class JoinedExpressionResult implements ExpressionResultInterface
{
    /** @var ExpressionResultInterface[] */
    private $results;

    /** @var ExpressionResultInterface[] */
    private $seperators;

    /**
     * @param ExpressionResultInterface[] $results
     * @param ExpressionResultInterface[] $seperators
     */
    public function __construct(array $results, array $seperators)
    {
        $this->results = $results;
        $this->seperators = $seperators;
    }

    /**
     * @return ExpressionResultInterface[]
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @return \PeterVanDommelen\Parser\Expression\ExpressionResultInterface[]
     */
    public function getSeperators()
    {
        return $this->seperators;
    }

    public function getLength()
    {
        $length = 0;
        foreach ($this->results as $i => $result) {
            if ($i !== 0) {
                $length += $this->seperators[$i - 1]->getLength();
            }
            $length += $result->getLength();
        }
        return $length;
    }

    public function getString()
    {
        $string = "";
        foreach ($this->results as $i => $result) {
            if ($i !== 0) {
                $string .= $this->seperators[$i - 1]->getString();
            }
            $string .= $result->getString();
        }
        return $string;
    }
}