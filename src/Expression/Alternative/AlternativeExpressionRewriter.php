<?php


namespace PeterVanDommelen\Parser\Expression\Alternative;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareTrait;
use PeterVanDommelen\Parser\Rewriter\ExpressionRewriterInterface;

class AlternativeExpressionRewriter implements ExpressionRewriterInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;

    public function rewriteExpression(ExpressionInterface $expression)
    {
        /** @var AlternativeExpression $expression */
        return new AlternativeExpression(array_map(array($this->getRecursiveHandler(), "rewriteExpression"), $expression->getAlternatives()));
    }

    public function getExpressionResultRewriter(ExpressionInterface $expression)
    {
        /** @var AlternativeExpression $expression */
        return new AlternativeExpressionResultRewriter(array_map(array($this->getRecursiveHandler(), "getExpressionResultRewriter"), $expression->getAlternatives()));
    }


}