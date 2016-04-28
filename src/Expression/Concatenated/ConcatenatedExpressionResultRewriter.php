<?php


namespace PeterVanDommelen\Parser\Expression\Concatenated;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Rewriter\ExpressionResultRewriterInterface;

class ConcatenatedExpressionResultRewriter implements ExpressionResultRewriterInterface
{
    /** @var ExpressionResultRewriterInterface[] */
    private $part_rewriters;

    /**
     * @param \PeterVanDommelen\Parser\Rewriter\ExpressionResultRewriterInterface[] $part_rewriters
     */
    public function __construct(array $part_rewriters)
    {
        $this->part_rewriters = $part_rewriters;
    }

    public function reverseRewriteExpressionResult(ExpressionResultInterface $result)
    {
        /** @var ConcatenatedExpressionResult $result */
        $rewritten_parts = array();
        foreach ($result->getParts() as $key => $result) {
            $rewritten_parts[$key] = $this->part_rewriters[$key]->reverseRewriteExpressionResult($result);
        }
        return new ConcatenatedExpressionResult($rewritten_parts);
    }

    public function rewriteExpressionResult(ExpressionResultInterface $result)
    {
        /** @var ConcatenatedExpressionResult $result */
        $rewritten_parts = array();
        foreach ($result->getParts() as $key => $result) {
            $rewritten_parts[$key] = $this->part_rewriters[$key]->rewriteExpressionResult($result);
        }
        return new ConcatenatedExpressionResult($rewritten_parts);
    }

}