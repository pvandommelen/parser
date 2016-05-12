<?php


namespace PeterVanDommelen\Parser\Simplifier;


use PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpression;
use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareTrait;
use PeterVanDommelen\Parser\Rewriter\ExpressionResultRewriterInterface;
use PeterVanDommelen\Parser\Rewriter\ExpressionRewriterInterface;
use PeterVanDommelen\Parser\Rewriter\RewrittenExpressionContainer;

class AlternativeSimplifier implements ExpressionRewriterInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;

    public function canRewrite(ExpressionInterface $expression)
    {
        return $expression instanceof AlternativeExpression && count($expression->getAlternatives()) === 1;
    }

    public function rewriteExpression(ExpressionInterface $expression)
    {
        /** @var AlternativeExpression $expression */
        $alternatives = $expression->getAlternatives();
        $alternative = current($alternatives);
        $key = key($alternatives);

        $rewritten = $this->getRecursiveHandler()->rewriteExpression($alternative);

        return new RewrittenExpressionContainer(
            $rewritten->getExpression(),
            new AlternativeResultSimplifier($key, $rewritten->getResultRewriter())
        );
    }
    
}