<?php


namespace PeterVanDommelen\Parser\PotentiallyEmptyChecker;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;

interface PotentiallyEmptyCheckerInterface
{
    /**
     * @param ExpressionInterface $expression
     * @throws CircularDependencyException
     * @return bool
     */
    public function isPotentiallyEmpty(ExpressionInterface $expression);
}