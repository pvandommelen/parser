<?php


namespace PeterVanDommelen\Parser\Expression\Not;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareTrait;
use PeterVanDommelen\Parser\Rewriter\ExpressionRewriterInterface;
use PeterVanDommelen\Parser\Rewriter\TerminateExpressionResultRewriter;

class NotExpressionRewriter implements ExpressionRewriterInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;

    public function rewriteExpression(ExpressionInterface $expression)
    {
        /** @var NotExpression $expression */
        $inner = $this->getRecursiveHandler()->rewriteExpression($expression->getExpression());
        return new NotExpression($inner);
    }

    public function getExpressionResultRewriter(ExpressionInterface $expression)
    {
        return new TerminateExpressionResultRewriter();
    }

}