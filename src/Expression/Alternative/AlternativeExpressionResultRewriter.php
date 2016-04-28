<?php


namespace PeterVanDommelen\Parser\Expression\Alternative;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Rewriter\ExpressionResultRewriterInterface;

class AlternativeExpressionResultRewriter implements ExpressionResultRewriterInterface
{
    /** @var ExpressionResultRewriterInterface[] */
    private $alternative_rewriters;

    /**
     * @param \PeterVanDommelen\Parser\Rewriter\ExpressionResultRewriterInterface[] $alternative_rewriters
     */
    public function __construct(array $alternative_rewriters)
    {
        $this->alternative_rewriters = $alternative_rewriters;
    }

    public function reverseRewriteExpressionResult(ExpressionResultInterface $result)
    {
        /** @var \PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpressionResult $result */

        $key = $result->getKey();
        $original_result = $this->alternative_rewriters[$key]->reverseRewriteExpressionResult($result->getResult());

        return new AlternativeExpressionResult($original_result, $key);
    }

    public function rewriteExpressionResult(ExpressionResultInterface $result)
    {
        /** @var \PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpressionResult $result */

        $key = $result->getKey();
        $original_result = $this->alternative_rewriters[$key]->rewriteExpressionResult($result->getResult());

        return new AlternativeExpressionResult($original_result, $key);
    }

}