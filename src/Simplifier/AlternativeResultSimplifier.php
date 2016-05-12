<?php


namespace PeterVanDommelen\Parser\Simplifier;


use PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpressionResult;
use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Rewriter\ExpressionResultRewriterInterface;

class AlternativeResultSimplifier implements ExpressionResultRewriterInterface
{
    /** @var string */
    private $key;

    /** @var ExpressionResultRewriterInterface */
    private $alternative_rewriter;

    /**
     * @param string $key
     * @param ExpressionResultRewriterInterface $alternative_rewriter
     */
    public function __construct($key, ExpressionResultRewriterInterface $alternative_rewriter)
    {
        $this->key = $key;
        $this->alternative_rewriter = $alternative_rewriter;
    }

    public function reverseRewriteExpressionResult(ExpressionResultInterface $result)
    {
        return new AlternativeExpressionResult($this->alternative_rewriter->reverseRewriteExpressionResult($result), $this->key);
    }

}