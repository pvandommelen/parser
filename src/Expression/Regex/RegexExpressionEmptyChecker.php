<?php


namespace PeterVanDommelen\Parser\Expression\Regex;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\PotentiallyEmptyChecker\PotentiallyEmptyCheckerInterface;

class RegexExpressionEmptyChecker implements PotentiallyEmptyCheckerInterface
{
    public function isPotentiallyEmpty(ExpressionInterface $expression)
    {
        /** @var RegexExpression $expression */
        $parser = new RegexParser($expression->getRegex());
        $able_to_match_empty_string = $parser->parse("") !== null;
        return $able_to_match_empty_string;
    }

}