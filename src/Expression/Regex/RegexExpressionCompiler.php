<?php


namespace PeterVanDommelen\Parser\Expression\Regex;


use PeterVanDommelen\Parser\Compiler\CompilerInterface;
use PeterVanDommelen\Parser\Expression\ExpressionInterface;

class RegexExpressionCompiler implements CompilerInterface
{
    public function compile(ExpressionInterface $expression)
    {
        /** @var RegexExpression $expression */
        return new RegexParser($expression->getRegex());
    }

}