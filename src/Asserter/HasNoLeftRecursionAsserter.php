<?php


namespace PeterVanDommelen\Parser\Asserter;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\ExpressionFlattener\ExpressionFlattenerInterface;
use PeterVanDommelen\Parser\PotentiallyEmptyChecker\CircularDependencyException;
use PeterVanDommelen\Parser\PotentiallyEmptyChecker\PotentiallyEmptyCheckerInterface;
use PeterVanDommelen\Parser\Rewriter\InvalidExpressionException;

class HasNoLeftRecursionAsserter implements ExpressionAsserterInterface
{
    /** @var ExpressionFlattenerInterface */
    private $expression_flattener;

    /** @var PotentiallyEmptyCheckerInterface */
    private $potentially_empty_checker;

    /**
     * @param ExpressionFlattenerInterface $expression_flattener
     * @param PotentiallyEmptyCheckerInterface $potentially_empty_checker
     */
    public function __construct(ExpressionFlattenerInterface $expression_flattener, PotentiallyEmptyCheckerInterface $potentially_empty_checker)
    {
        $this->expression_flattener = $expression_flattener;
        $this->potentially_empty_checker = $potentially_empty_checker;
    }

    public function assertExpression(ExpressionInterface $expression) {
        foreach ($this->expression_flattener->flattenExpression($expression) as $expr) {
            try {
                $this->potentially_empty_checker->isPotentiallyEmpty($expr);
            } catch (CircularDependencyException $e) {
                throw new InvalidExpressionException("Contains left recursion");
            }
        }
    }

}