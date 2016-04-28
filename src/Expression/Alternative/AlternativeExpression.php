<?php


namespace PeterVanDommelen\Parser\Expression\Alternative;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;

class AlternativeExpression implements ExpressionInterface
{
    /** @var ExpressionInterface[] */
    private $alternatives;

    /**
     * @param ExpressionInterface[] $alternatives
     */
    public function __construct(array $alternatives)
    {
        $this->alternatives = $alternatives;
    }

    /**
     * @return ExpressionInterface[]
     */
    public function getAlternatives()
    {
        return $this->alternatives;
    }

    /**
     * @param string|int $key
     * @return ExpressionInterface
     */
    public function getAlternative($key) {
        return $this->alternatives[$key];
    }


}