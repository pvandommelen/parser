<?php


namespace PeterVanDommelen\Parser\Expression\Repeater;


use PeterVanDommelen\Parser\Compiler\CompilerInterface;
use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareTrait;

class RepeaterExpressionCompiler implements CompilerInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;

    public function compile(ExpressionInterface $expression)
    {
        /** @var $expression \PeterVanDommelen\Parser\Expression\Repeater\RepeaterExpression */
        if ($expression->isLazy() === true) {
            return new LazyRepeaterParser($this->getRecursiveHandler()->compile($expression->getExpression()), $expression->getMinimum(), $expression->getMaximum());
        }
        return new GreedyRepeaterParser($this->getRecursiveHandler()->compile($expression->getExpression()), $expression->getMinimum(), $expression->getMaximum());
    }


}