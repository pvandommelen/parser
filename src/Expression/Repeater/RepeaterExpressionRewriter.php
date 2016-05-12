<?php


namespace PeterVanDommelen\Parser\Expression\Repeater;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareTrait;
use PeterVanDommelen\Parser\Rewriter\ExpressionRewriterInterface;
use PeterVanDommelen\Parser\Rewriter\RewrittenExpressionContainer;

class RepeaterExpressionRewriter implements ExpressionRewriterInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;

    public function canRewrite(ExpressionInterface $expression)
    {
        if ($expression instanceof RepeaterExpression === false) {
            return false;
        }
        return $this->getRecursiveHandler()->canRewrite($expression->getExpression());
    }

    public function rewriteExpression(ExpressionInterface $expression)
    {
        /** @var RepeaterExpression $expression */
        $rewritten_inner = $this->getRecursiveHandler()->rewriteExpression($expression->getExpression());
        return new RewrittenExpressionContainer(
            new RepeaterExpression($rewritten_inner->getExpression(), $expression->isLazy(), $expression->getMinimum(), $expression->getMaximum()),
            new RepeaterExpressionResultRewriter($rewritten_inner->getResultRewriter())
        );
    }

}