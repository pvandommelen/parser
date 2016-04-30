<?php


namespace PeterVanDommelen\Parser\Expression\Repeater;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareTrait;
use PeterVanDommelen\Parser\PotentiallyEmptyChecker\PotentiallyEmptyCheckerInterface;

class RepeaterExpressionEmptyChecker implements PotentiallyEmptyCheckerInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;

    public function isPotentiallyEmpty(ExpressionInterface $expression)
    {
        /** @var $expression RepeaterExpression */
        //because this method is also used to detect left recursion we always need to access the inner expression
        $inner_expression_empty = $this->getRecursiveHandler()->isPotentiallyEmpty($expression->getExpression());

        if ($expression->getMinimum() === 0) {
            return true;
        }
        if ($inner_expression_empty === true) {
            return true;
        }
        return false;
    }
}