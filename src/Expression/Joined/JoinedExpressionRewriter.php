<?php


namespace PeterVanDommelen\Parser\Expression\Joined;


use PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpression;
use PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpression;
use PeterVanDommelen\Parser\Expression\Constant\ConstantExpression;
use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Expression\Repeater\RepeaterExpression;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareTrait;
use PeterVanDommelen\Parser\Rewriter\ExpressionRewriterInterface;

class JoinedExpressionRewriter implements ExpressionRewriterInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;

    public function rewriteExpression(ExpressionInterface $expression)
    {
        /** @var JoinedExpression $expression */
        $inner_expression = $this->getRecursiveHandler()->rewriteExpression($expression->getExpression());
        $seperator_expression = $this->getRecursiveHandler()->rewriteExpression($expression->getSeperator());

        $repeater_part = new ConcatenatedExpression(array($seperator_expression, $inner_expression));

        $maybe_part = new ConcatenatedExpression(array(
            $inner_expression,
            new RepeaterExpression($repeater_part, $expression->isLazy(), max(0, $expression->getMinimum() - 1), $expression->getMaximum() - 1)
        ));

        return new AlternativeExpression(array(
            $maybe_part,
            new ConstantExpression(""),
        ));
    }

    public function getExpressionResultRewriter(ExpressionInterface $expression)
    {
        /** @var JoinedExpression $expression */
        return new JoinedExpressionResultRewriter(
            $this->getRecursiveHandler()->getExpressionResultRewriter($expression->getExpression()),
            $this->getRecursiveHandler()->getExpressionResultRewriter($expression->getSeperator())
        );
    }

}