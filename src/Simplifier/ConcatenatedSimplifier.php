<?php


namespace PeterVanDommelen\Parser\Simplifier;


use PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpression;
use PeterVanDommelen\Parser\Expression\Constant\ConstantExpression;
use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareTrait;
use PeterVanDommelen\Parser\Rewriter\ExpressionResultRewriterInterface;
use PeterVanDommelen\Parser\Rewriter\ExpressionRewriterInterface;
use PeterVanDommelen\Parser\Rewriter\RewrittenExpressionContainer;

class ConcatenatedSimplifier implements ExpressionRewriterInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;

    public function canRewrite(ExpressionInterface $expression)
    {
        return $expression instanceof ConcatenatedExpression && count($expression->getParts()) === 1;
    }

    public function rewriteExpression(ExpressionInterface $expression)
    {
        /** @var ConcatenatedExpression $expression */
        $parts = $expression->getParts();
        $key = key($parts);
        $part = current($parts);

        $rewritten_part = $this->getRecursiveHandler()->rewriteExpression($part);

        return new RewrittenExpressionContainer($rewritten_part->getExpression(), new ConcatenatedResultSimplifier($key, $rewritten_part->getResultRewriter()));
    }

}