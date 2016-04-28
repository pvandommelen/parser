<?php


namespace PeterVanDommelen\Parser\Rewriter;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\HandlerInterface;

/**
 * Rewrites an expression.
 */
interface ExpressionRewriterInterface
{
    /**
     * @param ExpressionInterface $expression
     * @return ExpressionInterface
     */
    public function rewriteExpression(ExpressionInterface $expression);

    /**
     * The parsed results needs to be transformed its original ExpressionResult
     *
     * @param ExpressionInterface $expression
     * @return ExpressionResultRewriterInterface
     */
    public function getExpressionResultRewriter(ExpressionInterface $expression);

}