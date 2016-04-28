<?php


namespace PeterVanDommelen\Parser\Expression\Not;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\ExpressionFlattener\ExpressionFlattenerInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareTrait;

class NotExpressionFlattener implements ExpressionFlattenerInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;

    public function flattenExpression(ExpressionInterface $expression)
    {
        /** @var $expression NotExpression */
        return array_unique(array_merge(array($expression), $this->getRecursiveHandler()->flattenExpression($expression->getExpression())), SORT_REGULAR);
    }

}