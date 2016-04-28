<?php


namespace PeterVanDommelen\Parser\Asserter;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Rewriter\InvalidExpressionException;

/**
 * Validates an expression.
 * Example: Target compiler does not allow for left recursion to exist
 */
interface ExpressionAsserterInterface
{
    /**
     * @param ExpressionInterface $expression
     * @return void
     * @throws InvalidExpressionException
     */
    public function assertExpression(ExpressionInterface $expression);
}