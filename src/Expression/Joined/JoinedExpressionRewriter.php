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
use PeterVanDommelen\Parser\Rewriter\RewrittenExpressionContainer;

class JoinedExpressionRewriter implements ExpressionRewriterInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;

    public function canRewrite(ExpressionInterface $expression)
    {
        return $expression instanceof JoinedExpression;
    }

    public function rewriteExpression(ExpressionInterface $expression)
    {
        /** @var JoinedExpression $expression */
        $rewritten_inner_expression = $this->getRecursiveHandler()->rewriteExpression($expression->getExpression());
        $rewritten_seperator_expression = $this->getRecursiveHandler()->rewriteExpression($expression->getSeperator());

        $repeater_part = new ConcatenatedExpression(array($rewritten_seperator_expression->getExpression(), $rewritten_inner_expression->getExpression()));

        $maybe_part = new ConcatenatedExpression(array(
            $rewritten_inner_expression->getExpression(),
            new RepeaterExpression($repeater_part, $expression->isLazy(), max(0, $expression->getMinimum() - 1), $expression->getMaximum() - 1)
        ));

        return new RewrittenExpressionContainer(new AlternativeExpression(array(
            $maybe_part,
            new ConstantExpression(""),
        )), new JoinedExpressionResultRewriter(
            $rewritten_inner_expression->getResultRewriter(),
            $rewritten_seperator_expression->getResultRewriter()
        ));
    }

}