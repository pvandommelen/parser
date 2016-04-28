<?php


namespace PeterVanDommelen\Parser\Expression\Repeater;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareTrait;
use PeterVanDommelen\Parser\Rewriter\ExpressionRewriterInterface;

class RepeaterExpressionRewriter implements ExpressionRewriterInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;

    public function rewriteExpression(ExpressionInterface $expression)
    {
        /** @var RepeaterExpression $expression */
        $inner = $this->getRecursiveHandler()->rewriteExpression($expression->getExpression());
        return new RepeaterExpression($inner, $expression->isLazy(), $expression->getMinimum(), $expression->getMaximum());
    }

    public function getExpressionResultRewriter(ExpressionInterface $expression)
    {
        /** @var RepeaterExpression $expression */
        $inner = $this->getRecursiveHandler()->getExpressionResultRewriter($expression->getExpression());
        return new RepeaterExpressionResultRewriter($inner);
    }


}