<?php


namespace PeterVanDommelen\Parser\Expression\Not;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\PotentiallyEmptyChecker\PotentiallyEmptyCheckerInterface;

class NotExpressionEmptyChecker implements PotentiallyEmptyCheckerInterface
{
    public function isPotentiallyEmpty(ExpressionInterface $expression)
    {
        /** @var $expression NotExpression */
        return false;
    }

}