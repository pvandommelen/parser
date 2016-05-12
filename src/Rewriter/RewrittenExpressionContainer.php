<?php


namespace PeterVanDommelen\Parser\Rewriter;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;

class RewrittenExpressionContainer
{
    /** @var ExpressionInterface */
    private $expression;

    /** @var ExpressionResultRewriterInterface */
    private $result_rewriter;

    /**
     * @param ExpressionInterface $expression
     * @param ExpressionResultRewriterInterface $result_rewriter
     */
    public function __construct(ExpressionInterface $expression, ExpressionResultRewriterInterface $result_rewriter)
    {
        $this->expression = $expression;
        $this->result_rewriter = $result_rewriter;
    }

    /**
     * @return ExpressionInterface
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @return ExpressionResultRewriterInterface
     */
    public function getResultRewriter()
    {
        return $this->result_rewriter;
    }


}