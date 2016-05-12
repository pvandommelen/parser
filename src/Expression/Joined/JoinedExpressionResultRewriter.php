<?php


namespace PeterVanDommelen\Parser\Expression\Joined;


use PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpressionResult;
use PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpressionResult;
use PeterVanDommelen\Parser\Expression\Constant\ConstantExpressionResult;
use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Expression\Repeater\RepeaterExpressionResult;
use PeterVanDommelen\Parser\Rewriter\ExpressionResultRewriterInterface;

class JoinedExpressionResultRewriter implements ExpressionResultRewriterInterface
{
    /** @var ExpressionResultRewriterInterface */
    private $inner_result_rewriter;

    /** @var ExpressionResultRewriterInterface */
    private $seperator_result_rewriter;

    /**
     * @param ExpressionResultRewriterInterface $inner_result_rewriter
     * @param ExpressionResultRewriterInterface $seperator_result_rewriter
     */
    public function __construct(ExpressionResultRewriterInterface $inner_result_rewriter, ExpressionResultRewriterInterface $seperator_result_rewriter)
    {
        $this->inner_result_rewriter = $inner_result_rewriter;
        $this->seperator_result_rewriter = $seperator_result_rewriter;
    }

    public function reverseRewriteExpressionResult(ExpressionResultInterface $result)
    {
        /** @var AlternativeExpressionResult $result */
        if ($result->getKey() === 1) {
            //empty
            return new JoinedExpressionResult(array(), array());
        }

        /** @var \PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpressionResult $maybe_part */
        $maybe_part = $result->getResult();
        /** @var \PeterVanDommelen\Parser\Expression\Repeater\RepeaterExpressionResult $repeater_part */
        $repeater_part = $maybe_part->getPart(1);

        $results = array(
            $this->inner_result_rewriter->reverseRewriteExpressionResult($maybe_part->getPart(0))
        );
        $seperators = array();
        foreach ($repeater_part->getResults() as $repeater_part_part) {
            /** @var \PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpressionResult $repeater_part_part */
            $seperators[] = $this->seperator_result_rewriter->reverseRewriteExpressionResult($repeater_part_part->getPart(0));
            $results[] = $this->inner_result_rewriter->reverseRewriteExpressionResult($repeater_part_part->getPart(1));
        }

        return new JoinedExpressionResult($results, $seperators);
    }

}