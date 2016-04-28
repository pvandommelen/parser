<?php


namespace PeterVanDommelen\Parser\Expression\Alternative;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\CircularDependencyDetectedException;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareTrait;
use PeterVanDommelen\Parser\PotentiallyEmptyChecker\PotentiallyEmptyCheckerInterface;

class AlternativeExpressionEmptyChecker implements PotentiallyEmptyCheckerInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;

    public function isPotentiallyEmpty(ExpressionInterface $expression)
    {
        /** @var $expression AlternativeExpression */
        foreach ($expression->getAlternatives() as $alternative) {
            if ($this->getRecursiveHandler()->isPotentiallyEmpty($alternative) === true) {
                return true;
            }
        }
        return false;
    }


}