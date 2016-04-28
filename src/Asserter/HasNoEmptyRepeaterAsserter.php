<?php


namespace PeterVanDommelen\Parser\Asserter;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Expression\Repeater\RepeaterExpression;
use PeterVanDommelen\Parser\ExpressionFlattener\ExpressionFlattenerInterface;
use PeterVanDommelen\Parser\Handler\CircularDependencyDetectedException;
use PeterVanDommelen\Parser\PotentiallyEmptyChecker\PotentiallyEmptyCheckerInterface;
use PeterVanDommelen\Parser\Rewriter\InvalidExpressionException;

class HasNoEmptyRepeaterAsserter implements ExpressionAsserterInterface
{
    /** @var ExpressionFlattenerInterface */
    private $expression_flattener;

    /** @var PotentiallyEmptyCheckerInterface */
    private $is_potentially_empty;

    /**
     * @param ExpressionFlattenerInterface $expression_flattener
     * @param PotentiallyEmptyCheckerInterface $is_potentially_empty
     */
    public function __construct(ExpressionFlattenerInterface $expression_flattener, PotentiallyEmptyCheckerInterface $is_potentially_empty)
    {
        $this->expression_flattener = $expression_flattener;
        $this->is_potentially_empty = $is_potentially_empty;
    }

    public function assertExpression(ExpressionInterface $expression)
    {
        foreach ($this->expression_flattener->flattenExpression($expression) as $expr) {
            if ($expr instanceof RepeaterExpression === true) {
                /** @var \PeterVanDommelen\Parser\Expression\Repeater\RepeaterExpression $expr */
                if ($this->is_potentially_empty->isPotentiallyEmpty($expr->getExpression()) === true) {
                    throw new InvalidExpressionException("Contains a repeater expression that can be empty");
                }
            }
        }
    }


}