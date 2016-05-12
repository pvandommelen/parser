<?php


namespace PeterVanDommelen\Parser\Rewriter;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;

class ExpressionResultRewriterChain implements ExpressionResultRewriterInterface
{
    /** @var ExpressionResultRewriterInterface[] */
    private $result_rewriters;

    /**
     * @param ExpressionResultRewriterInterface[] $result_rewriters
     */
    public function __construct($result_rewriters)
    {
        $this->result_rewriters = $result_rewriters;
    }

    public function reverseRewriteExpressionResult(ExpressionResultInterface $result)
    {
        foreach ($this->result_rewriters as $rewriter) {
            $result = $rewriter->reverseRewriteExpressionResult($result);
        }
        return $result;
    }
}