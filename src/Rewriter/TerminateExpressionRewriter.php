<?php


namespace PeterVanDommelen\Parser\Rewriter;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;

/*
 * Used by ConstantExpression and others that contain no inner expressions
 */
class TerminateExpressionRewriter implements ExpressionRewriterInterface
{
    public function rewriteExpression(ExpressionInterface $expression)
    {
        return $expression;
    }

    public function getExpressionResultRewriter(ExpressionInterface $expression)
    {
        return new TerminateExpressionResultRewriter();
    }

}