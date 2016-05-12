<?php


namespace PeterVanDommelen\Parser\Expression\Concatenated;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareTrait;
use PeterVanDommelen\Parser\Rewriter\ExpressionRewriterInterface;
use PeterVanDommelen\Parser\Rewriter\RewrittenExpressionContainer;

class ConcatenatedExpressionRewriter implements ExpressionRewriterInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;

    public function canRewrite(ExpressionInterface $expression) {
        if ($expression instanceof ConcatenatedExpression === false) {
            return false;
        }

        foreach ($expression->getParts() as $part) {
            if ($this->getRecursiveHandler()->canRewrite($part) === true) {
                return true;
            }
        }

        return false;
    }

    public function rewriteExpression(ExpressionInterface $expression)
    {
        /** @var ConcatenatedExpression $expression */
        $rewritten_parts = array_map(array($this->getRecursiveHandler(), "rewriteExpression"), $expression->getParts());

        $rewritten_part_expressions = array_map(function (RewrittenExpressionContainer $rewritten) {
            return $rewritten->getExpression();
        }, $rewritten_parts);

        $rewritten_part_result_rewriters = array_map(function (RewrittenExpressionContainer $rewritten) {
            return $rewritten->getResultRewriter();
        }, $rewritten_parts);

        return new RewrittenExpressionContainer(new ConcatenatedExpression($rewritten_part_expressions), new ConcatenatedExpressionResultRewriter($rewritten_part_result_rewriters));
    }


}