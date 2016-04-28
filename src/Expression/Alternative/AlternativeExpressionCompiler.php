<?php


namespace PeterVanDommelen\Parser\Expression\Alternative;


use PeterVanDommelen\Parser\Compiler\CompilerInterface;
use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareTrait;

class AlternativeExpressionCompiler implements CompilerInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;

    public function compile(ExpressionInterface $expression)
    {
        /** @var $expression AlternativeExpression */
        return new \PeterVanDommelen\Parser\Expression\Alternative\AlternativeParser(array_map(array($this->getRecursiveHandler(), "compile"), $expression->getAlternatives()));
    }

}