<?php


namespace PeterVanDommelen\Parser\Compiler;


use PeterVanDommelen\Parser\Asserter\ExpressionAsserterInterface;
use PeterVanDommelen\Parser\Expression\ExpressionInterface;

class AsserterCompiler implements CompilerInterface
{
    /** @var CompilerInterface */
    private $inner_compiler;

    /** @var ExpressionAsserterInterface */
    private $asserter;

    /**
     * @param CompilerInterface $inner_compiler
     * @param ExpressionAsserterInterface $asserter
     */
    public function __construct(CompilerInterface $inner_compiler, ExpressionAsserterInterface $asserter)
    {
        $this->inner_compiler = $inner_compiler;
        $this->asserter = $asserter;
    }

    public function compile(ExpressionInterface $expression)
    {
        $this->asserter->assertExpression($expression);
        return $this->inner_compiler->compile($expression);
    }

}