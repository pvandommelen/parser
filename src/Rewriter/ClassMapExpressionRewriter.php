<?php


namespace PeterVanDommelen\Parser\Rewriter;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\Cache;
use PeterVanDommelen\Parser\Handler\ClassMapHandler;

class ClassMapExpressionRewriter extends ClassMapHandler implements ExpressionRewriterInterface
{

    private $expression_cache;

    private $result_rewriter_cache;

    public function __construct(array $class_rewriters)
    {
        parent::__construct($class_rewriters);
        $this->expression_cache = new Cache();
        $this->result_rewriter_cache = new Cache();
    }

    protected function getInterfaceName()
    {
        return ExpressionRewriterInterface::class;
    }

    public function rewriteExpression(ExpressionInterface $expression)
    {
        /** @var ExpressionInterface $expression */
        $expression = $this->resolveArgument($expression);
        
        if ($this->expression_cache->has($expression) === false) {
            $lazy_result = new LazyExpression();
            $this->expression_cache->set($expression, $lazy_result);

            $result = $this->getHandlerUsingClassMap($expression)->rewriteExpression($expression);

            $lazy_result->setResult($result);
            $this->expression_cache->set($expression, $result);
        }
        return $this->expression_cache->get($expression);
    }

    public function getExpressionResultRewriter(ExpressionInterface $expression)
    {
        /** @var ExpressionInterface $expression */
        $expression = $this->resolveArgument($expression);

        if ($this->result_rewriter_cache->has($expression) === false) {
            $lazy_result = new LazyExpressionResultRewriter();
            $this->result_rewriter_cache->set($expression, $lazy_result);

            $result = $this->getHandlerUsingClassMap($expression)->getExpressionResultRewriter($expression);

            $lazy_result->setResult($result);
            $this->result_rewriter_cache->set($expression, $result);
        }
        return $this->result_rewriter_cache->get($expression);
    }

}