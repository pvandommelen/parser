<?php


namespace PeterVanDommelen\Parser\Expression\Not;


use PeterVanDommelen\Parser\Compiler\CompilerInterface;
use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareTrait;

class NotExpressionCompiler implements CompilerInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;

    /** @var string */
    private $encoding;

    /**
     * @param string $encoding
     */
    public function __construct($encoding)
    {
        $this->encoding = $encoding;
    }

    public function compile(ExpressionInterface $expression)
    {
        /** @var $expression NotExpression */
        return new NotParser($this->getRecursiveHandler()->compile($expression->getExpression()), $this->encoding);
    }

}