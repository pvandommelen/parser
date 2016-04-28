<?php


namespace PeterVanDommelen\Parser\Expression\Repeater;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Rewriter\ExpressionResultRewriterInterface;

/**
 * Valid for both the greedy and lazy versions
 */
class RepeaterExpressionResultRewriter implements ExpressionResultRewriterInterface
{
    /** @var ExpressionResultRewriterInterface */
    private $inner_rewriter;

    /**
     * @param ExpressionResultRewriterInterface $inner_rewriter
     */
    public function __construct(ExpressionResultRewriterInterface $inner_rewriter)
    {
        $this->inner_rewriter = $inner_rewriter;
    }

    public function reverseRewriteExpressionResult(ExpressionResultInterface $result)
    {
        /** @var RepeaterExpressionResult $result */
        return new RepeaterExpressionResult(array_map(array($this->inner_rewriter, "reverseRewriteExpressionResult"), $result->getResults()));
    }

    public function rewriteExpressionResult(ExpressionResultInterface $result)
    {
        /** @var RepeaterExpressionResult $result */
        return new RepeaterExpressionResult(array_map(array($this->inner_rewriter, "rewriteExpressionResult"), $result->getResults()));
    }

}