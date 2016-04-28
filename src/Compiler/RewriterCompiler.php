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
        $parser = $this->compiler->compile($this->rewriter->rewriteExpression($expression));

        return new RewriterParser($parser, $this->rewriter->getExpressionResultRewriter($expression));
    }

}