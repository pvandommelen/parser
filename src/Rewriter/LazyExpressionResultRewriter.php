<?php


namespace PeterVanDommelen\Parser\Rewriter;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Handler\LazyResult;

class LazyExpressionResultRewriter extends LazyResult implements ExpressionResultRewriterInterface
{
    public function reverseRewriteExpressionResult(ExpressionResultInterface $result)
    {
        return $this->getResult()->reverseRewriteExpressionResult($result);
    }

    public function rewriteExpressionResult(ExpressionResultInterface $result)
    {
        return $this->getResult()->rewriteExpressionResult($result);
    }

}