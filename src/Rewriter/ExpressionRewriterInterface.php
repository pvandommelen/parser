<?php


namespace PeterVanDommelen\Parser\Rewriter;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;

/**
 * Rewrites an expression.
 */
interface ExpressionRewriterInterface
{

    /**
     * Returns true if the expression can be used in 'rewriteExpression' AND
     * will result in a different expression.
     *
     * @param ExpressionInterface $expression
     * @return bool
     */
    public function canRewrite(ExpressionInterface $expression);

    /**
     * @param ExpressionInterface $expression
     * @return RewrittenExpressionContainer
     */
    public function rewriteExpression(ExpressionInterface $expression);

}