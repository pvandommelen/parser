<?php


namespace PeterVanDommelen\Parser\ExpressionFlattener;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;

class TerminateExpressionFlattener implements ExpressionFlattenerInterface
{
    public function flattenExpression(ExpressionInterface $expression)
    {
        return array($expression);
    }

}