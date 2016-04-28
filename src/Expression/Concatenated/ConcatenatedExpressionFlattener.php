<?php


namespace PeterVanDommelen\Parser\Expression\Concatenated;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\ExpressionFlattener\ExpressionFlattenerInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareTrait;

class ConcatenatedExpressionFlattener implements ExpressionFlattenerInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;

    public function flattenExpression(ExpressionInterface $expression)
    {
        /** @var $expression \PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpression */
        $expressions = array($expression);
        foreach ($expression->getParts() as $part) {
            $expressions = array_merge($expressions, $this->getRecursiveHandler()->flattenExpression($part));
        }
        return array_unique($expressions, SORT_REGULAR);
    }

}