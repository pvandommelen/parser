<?php


namespace PeterVanDommelen\Parser\Expression\Constant;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\CircularDependencyDetectedException;
use PeterVanDommelen\Parser\PotentiallyEmptyChecker\PotentiallyEmptyCheckerInterface;

class ConstantExpressionEmptyChecker implements PotentiallyEmptyCheckerInterface
{
    public function isPotentiallyEmpty(ExpressionInterface $expression)
    {
        /** @var $expression \PeterVanDommelen\Parser\Expression\Constant\ConstantExpression */
        return $expression->getString() === "";
    }

}