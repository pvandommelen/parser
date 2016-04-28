<?php


namespace PeterVanDommelen\Parser\Expression\Concatenated;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareTrait;
use PeterVanDommelen\Parser\Rewriter\ExpressionRewriterInterface;

class ConcatenatedExpressionRewriter implements ExpressionRewriterInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;

    public function rewriteExpression(ExpressionInterface $expression)
    {
        /** @var ConcatenatedExpression $expression */
        return new ConcatenatedExpression(array_map(array($this->getRecursiveHandler(), "rewriteExpression"), $expression->getParts()));
    }

    public function getExpressionResultRewriter(ExpressionInterface $expression)
    {
        /** @var ConcatenatedExpression $expression */
        return new ConcatenatedExpressionResultRewriter(array_map(array($this->getRecursiveHandler(), "getExpressionResultRewriter"), $expression->getParts()));
    }


}