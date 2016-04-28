<?php


namespace PeterVanDommelen\Parser\Rewriter;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;

/**
 * Expressions rewritten using ExpressionRewriterInterface may also need to have their results returned back to what they would originally be.
 */
interface ExpressionResultRewriterInterface
{
    /**
     * Takes the parsed result and transforms it into the original expression result.
     *
     * @param ExpressionResultInterface $result
     * @return ExpressionResultInterface
     */
    public function reverseRewriteExpressionResult(ExpressionResultInterface $result);

    /**
     * Takes results in the original form and transforms it to the rewritten expression result.
     * Only used when backtracking with the exposed parser. Internally, backtracking uses the transformed structure.
     *
     * @param ExpressionResultInterface $result
     * @return ExpressionResultInterface
     */
    public function rewriteExpressionResult(ExpressionResultInterface $result);
}