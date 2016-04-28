<?php


namespace PeterVanDommelen\Parser\Asserter;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;

class MultipleAsserter implements ExpressionAsserterInterface
{
    /** @var ExpressionAsserterInterface[] */
    private $asserters;

    /**
     * @param ExpressionAsserterInterface[] $asserters
     */
    public function __construct(array $asserters)
    {
        $this->asserters = $asserters;
    }

    public function assertExpression(ExpressionInterface $expression)
    {
        foreach ($this->asserters as $asserter) {
            $asserter->assertExpression($expression);
        }
    }


}