<?php


namespace PeterVanDommelen\Parser\Expression\Concatenated;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareTrait;
use PeterVanDommelen\Parser\PotentiallyEmptyChecker\PotentiallyEmptyCheckerInterface;

class ConcatenatedExpressionEmptyChecker implements PotentiallyEmptyCheckerInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;

    public function isPotentiallyEmpty(ExpressionInterface $expression)
    {
        /** @var $expression ConcatenatedExpression */
        foreach ($expression->getParts() as $part) {
            if ($this->getRecursiveHandler()->isPotentiallyEmpty($part) === false) {
                return false;
            }
        }
        return true;
    }

}