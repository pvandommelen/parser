<?php


namespace PeterVanDommelen\Parser\BacktrackingStreaming\Compiler;


use PeterVanDommelen\Parser\BacktrackingStreaming\BacktrackingStreamingCompilerInterface;
use PeterVanDommelen\Parser\BacktrackingStreaming\Parser\ConstantParser;
use PeterVanDommelen\Parser\BacktrackingStreaming\Parser\SingleByteParser;
use PeterVanDommelen\Parser\Compiler\CompilerInterface;
use PeterVanDommelen\Parser\Expression\ExpressionInterface;

class ConstantExpressionCompiler implements BacktrackingStreamingCompilerInterface
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