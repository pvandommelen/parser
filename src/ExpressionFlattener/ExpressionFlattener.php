<?php


namespace PeterVanDommelen\Parser\ExpressionFlattener;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\ClassMapHandler;


class ExpressionFlattener extends ClassMapHandler implements ExpressionFlattenerInterface
{
    private $stack = array();

    /**
     * @param array $handlers
     */
    public function __construct(array $handlers)
    {
        parent::__construct($handlers);
    }

    protected function getInterfaceName()
    {
        return ExpressionFlattenerInterface::class;
    }

    public function flattenExpression(ExpressionInterface $expression)
    {
        /** @var ExpressionInterface $expression */
        $expression = $this->resolveArgument($expression);

        if (in_array($expression, $this->stack, true) === true) {
            //return an empty set
            return array();
        }

        array_push($this->stack, $expression);
        $result = $this->getHandlerUsingClassMap($expression)->flattenExpression($expression);
        array_pop($this->stack);
        return $result;
    }
}