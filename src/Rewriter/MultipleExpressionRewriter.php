<?php


namespace PeterVanDommelen\Parser\Rewriter;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\Cache;
use PeterVanDommelen\Parser\Handler\LazyResult;
use PeterVanDommelen\Parser\Handler\RecursionAwareInterface;

class MultipleExpressionRewriter implements ExpressionRewriterInterface
{
    /** @var ExpressionRewriterInterface[] */
    private $rewriters;

    private $expression_cache;

    private $result_rewriter_cache;

    private $rewriter_cache;

    public function __construct(array $rewriters)
    {
        $this->rewriters = $rewriters;
        $this->expression_cache = new Cache();
        $this->result_rewriter_cache = new Cache();
        $this->rewriter_cache = new Cache();
        
        foreach ($this->rewriters as $rewriter) {
            if ($rewriter instanceof RecursionAwareInterface) {
                $rewriter->setRecursiveHandler($this);
            }
        }
    }

    /**
     * @param ExpressionInterface $argument
     * @return ExpressionInterface
     */
    private function resolveArgument($argument) {
        while ($argument instanceof LazyResult) {
            $argument = $argument->getResult();
        }
        return $argument;
    }

    /**
     * @param ExpressionInterface $expression
     * @return ExpressionRewriterInterface|null
     */
    private function getRewriter($expression) {
        if ($this->rewriter_cache->has($expression) === false) {
            $lazy_result = new LazyResult();
            $this->rewriter_cache->set($expression, $lazy_result);

            $result = null;
            foreach ($this->rewriters as $rewriter) {
                if ($rewriter->canRewrite($expression) === true) {
                    $result = $rewriter;
                    break;
                }
            }

            $lazy_result->setResult($result);
            $this->rewriter_cache->set($expression, $result);
        }
        $entry = $this->rewriter_cache->get($expression);

        if ($entry instanceof LazyResult) {
            return null;
        }
        return $entry;
    }

    public function canRewrite(ExpressionInterface $expression)
    {
        if ($expression instanceof LazyResult) {
            return null;
        }
        $expression = $this->resolveArgument($expression);

        return $this->getRewriter($expression) !== null;
    }

    private function rewriteSinglePass(ExpressionInterface $expression) {
        $rewriter = $this->getRewriter($expression);
        return $rewriter->rewriteExpression($expression);
    }

    public function rewriteExpression(ExpressionInterface $expression)
    {
        $expression = $this->resolveArgument($expression);
        
        if ($this->expression_cache->has($expression) === false) {
            $lazy_expression = new LazyExpression();
            $lazy_result_rewriter = new LazyExpressionResultRewriter();
            $lazy_result = new RewrittenExpressionContainer($lazy_expression, $lazy_result_rewriter);
            $this->expression_cache->set($expression, $lazy_result);

            $rewriters = array();
            $current_expression = $expression;

            while ($this->canRewrite($current_expression) === true) {
                $rewritten = $this->rewriteSinglePass($current_expression);
                $current_expression = $rewritten->getExpression();
                $rewriters[] = $rewritten->getResultRewriter();
            }

            $result_rewriter = new ExpressionResultRewriterChain(array_reverse($rewriters));
            $result = new RewrittenExpressionContainer($current_expression, $result_rewriter);

            $lazy_expression->setResult($current_expression);
            $lazy_result_rewriter->setResult($result_rewriter);

            $this->expression_cache->set($expression, $result);
        }
        return $this->expression_cache->get($expression);
    }

}