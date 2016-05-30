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
        $potentially_empty = false;
        foreach ($expression->getAlternatives() as $alternative) {
            $alternative_is_potentially_empty = $this->getRecursiveHandler()->isPotentiallyEmpty($alternative) === true;
            $potentially_empty = $potentially_empty || $alternative_is_potentially_empty;
        }
        return $potentially_empty;
    }


}