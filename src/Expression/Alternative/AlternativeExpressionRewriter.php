<?php


namespace PeterVanDommelen\Parser\Expression\Alternative;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;
use PeterVanDommelen\Parser\Handler\RecursionAwareTrait;
use PeterVanDommelen\Parser\Rewriter\ExpressionRewriterInterface;
use PeterVanDommelen\Parser\Rewriter\RewrittenExpressionContainer;

class AlternativeExpressionRewriter implements ExpressionRewriterInterface, RecursionAwareInterface
{
    use RecursionAwareTrait;

    public function canRewrite(ExpressionInterface $expression)
    {
        if ($expression instanceof AlternativeExpression === false) {
            return false;
        }

        foreach ($expression->getAlternatives() as $alternative) {
            if ($this->getRecursiveHandler()->canRewrite($alternative) === true) {
                return true;
            }
        }

        return false;
    }

    public function rewriteExpression(ExpressionInterface $expression)
    {
        /** @var AlternativeExpression $expression */
        $rewritten_alternatives = array_map(array($this->getRecursiveHandler(), "rewriteExpression"), $expression->getAlternatives());

        $rewritten_alternative_expressions = array_map(function (RewrittenExpressionContainer $rewritten) {
            return $rewritten->getExpression();
        }, $rewritten_alternatives);

        $rewritten_alternative_result_rewriters = array_map(function (RewrittenExpressionContainer $rewritten) {
            return $rewritten->getResultRewriter();
        }, $rewritten_alternatives);

        return new RewrittenExpressionContainer(new AlternativeExpression($rewritten_alternative_expressions), new AlternativeExpressionResultRewriter($rewritten_alternative_result_rewriters));
    }


}