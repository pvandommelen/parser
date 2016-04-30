<?php


namespace PeterVanDommelen\Parser\Expression\Constant;


use PeterVanDommelen\Parser\Compiler\CompilerInterface;
use PeterVanDommelen\Parser\Expression\ExpressionInterface;

class ConstantExpressionCompiler implements CompilerInterface
{
    public function compile(ExpressionInterface $expression)
    {
        /** @var $expression \PeterVanDommelen\Parser\Expression\Constant\ConstantExpression */
        if (strlen($expression->getString()) === 1) {
            return new SingleByteParser($expression->getString());
        }
        return new ConstantParser($expression->getString());
    }

}