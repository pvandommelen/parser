<?php


namespace PeterVanDommelen\Parser\Simplifier;


use PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpression;
use PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpressionResult;
use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Rewriter\ExpressionResultRewriterInterface;

class ConcatenatedResultSimplifier implements ExpressionResultRewriterInterface
{
    /** @var string */
    private $key;

    /** @var ExpressionResultRewriterInterface */
    private $part_rewriter;

    /**
     * @param string $key
     * @param ExpressionResultRewriterInterface $part_rewriter
     */
    public function __construct($key, ExpressionResultRewriterInterface $part_rewriter)
    {
        $this->key = $key;
        $this->part_rewriter = $part_rewriter;
    }

    public function reverseRewriteExpressionResult(ExpressionResultInterface $result)
    {
        $part_results = array();
        $part_results[$this->key] = $this->part_rewriter->reverseRewriteExpressionResult($result);
        return new ConcatenatedExpressionResult($part_results);
    }

}