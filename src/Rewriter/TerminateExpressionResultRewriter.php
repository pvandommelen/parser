<?php


namespace PeterVanDommelen\Parser\Rewriter;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;

class TerminateExpressionResultRewriter implements ExpressionResultRewriterInterface
{
    public function reverseRewriteExpressionResult(ExpressionResultInterface $result)
    {
        return $result;
    }

}