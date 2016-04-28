<?php


namespace PeterVanDommelen\Parser\Expression\Alternative;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\ExpressionFlattener\ExpressionFlattenerInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareTrait;

class AlternativeExpressionFlattener implements ExpressionFlattenerInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;

    public function flattenExpression(ExpressionInterface $expression)
    {
        /** @var $expression AlternativeExpression */
        $expressions = array($expression);
        foreach ($expression->getAlternatives() as $alternative) {
            $expressions = array_merge($expressions, $this->getRecursiveHandler()->flattenExpression($alternative));
        }
        return array_unique($expressions, SORT_REGULAR);
    }


}