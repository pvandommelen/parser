<?php


namespace PeterVanDommelen\Parser\Compiler;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Rewriter\ExpressionProcessorInterface;
use PeterVanDommelen\Parser\Rewriter\ExpressionRewriterInterface;

class RewriterCompiler implements CompilerInterface
{
    /** @var CompilerInterface */
    private $compiler;

    /** @var ExpressionRewriterInterface */
    private $rewriter;

    /**
     * @param CompilerInterface $compiler
     * @param ExpressionRewriterInterface $rewriter
     */
    public function __construct(CompilerInterface $compiler, ExpressionRewriterInterface $rewriter)
    {
        $this->compiler = $compiler;
        $this->rewriter = $rewriter;
    }

    public function compile(ExpressionInterface $expression)
    {
        $rewritten_expression = $expression;
        $result_rewriter = null;
        if ($this->rewriter->canRewrite($expression) === true) {
            $rewritten = $this->rewriter->rewriteExpression($expression);
            $rewritten_expression = $rewritten->getExpression();
            $result_rewriter = $rewritten->getResultRewriter();
        }
        $parser = $this->compiler->compile($rewritten_expression);

        if ($result_rewriter === null) {
            return $parser;
        }

        return new RewriterParser($parser, $result_rewriter);
    }

}