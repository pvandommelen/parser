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
        if ($expression->getMinimum() === 0) {
            return true;
        }
        if ($this->getRecursiveHandler()->isPotentiallyEmpty($expression->getExpression()) === true) {
            return true;
        }
        return false;
    }
}