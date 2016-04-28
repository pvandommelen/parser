<?php


namespace PeterVanDommelen\Parser\Expression\EndOfString;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\PotentiallyEmptyChecker\CircularDependencyException;
use PeterVanDommelen\Parser\PotentiallyEmptyChecker\PotentiallyEmptyCheckerInterface;

class EndOfStringEmptyChecker implements PotentiallyEmptyCheckerInterface
{
    public function isPotentiallyEmpty(ExpressionInterface $expression)
    {
        //this makes very little sense
        
        return true;
    }

}