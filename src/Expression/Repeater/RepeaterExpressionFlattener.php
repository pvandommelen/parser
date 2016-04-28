<?php


namespace PeterVanDommelen\Parser\Expression\Repeater;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\ExpressionFlattener\ExpressionFlattenerInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareTrait;

class RepeaterExpressionFlattener implements ExpressionFlattenerInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;

    public function flattenExpression(ExpressionInterface $expression)
    {
        /** @var $expression \PeterVanDommelen\Parser\Expression\Repeater\RepeaterExpression */
        return array_unique(array_merge(array($expression), $this->getRecursiveHandler()->flattenExpression($expression->getExpression())), SORT_REGULAR);
    }

}