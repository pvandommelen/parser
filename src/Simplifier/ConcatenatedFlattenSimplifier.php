<?php


namespace PeterVanDommelen\Parser\Simplifier;


use PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpression;
use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareTrait;
use PeterVanDommelen\Parser\Rewriter\ExpressionResultRewriterInterface;
use PeterVanDommelen\Parser\Rewriter\ExpressionRewriterInterface;
use PeterVanDommelen\Parser\Rewriter\RewrittenExpressionContainer;

class ConcatenatedFlattenSimplifier implements ExpressionRewriterInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;
    
    public function canRewrite(ExpressionInterface $expression)
    {
        if ($expression instanceof ConcatenatedExpression === false) {
            return false;
        }

        /** @var ConcatenatedExpression $expression */
        foreach ($expression->getParts() as $part) {
            if ($part instanceof ConcatenatedExpression === true && $part !== $expression) {
                return true;
            }
        }
        return false;
    }

    public function rewriteExpression(ExpressionInterface $expression)
    {
        /** @var ConcatenatedExpression $expression */
        $part_expressions = array();
        $mapping = array();

        foreach ($expression->getParts() as $key => $part) {
            if ($part instanceof ConcatenatedExpression === true && $part !== $expression) {
                /** @var ConcatenatedExpression $part */

//                $rewritten_inner_parts = array_map(function (ExpressionInterface $inner_part) {
//                    return $this->getRecursiveHandler()->rewriteExpression($inner_part);
//                }, array_values($part->getParts()));
//
//                $rewritten_inner_part_expressions = array_map(function (RewrittenExpressionContainer $rewritten) {
//                    return $rewritten->getExpression();
//                }, $rewritten_inner_parts);

                $part_expressions = array_merge($part_expressions, array_values($part->getParts()));

//                $rewritten_part_expressions = array_map(function (RewrittenExpressionContainer $rewritten) {
//                    return $rewritten->getExpression();
//                }, $rewritten_parts);
                $mapping[] = array($key, array_keys($part->getParts()));
            } else {
                $part_expressions[] = $part;
                $mapping[] = $key;
            }
        }
        return new RewrittenExpressionContainer(new ConcatenatedExpression($part_expressions), new ConcatenatedResultFlattenSimplifier($mapping));
    }

}