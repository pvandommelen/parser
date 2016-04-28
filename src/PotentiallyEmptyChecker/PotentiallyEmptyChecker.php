<?php


namespace PeterVanDommelen\Parser\PotentiallyEmptyChecker;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Handler\ClassMapHandler;
use PeterVanDommelen\Parser\Handler\ThrowOnCircularDependency;

class PotentiallyEmptyChecker extends ClassMapHandler implements PotentiallyEmptyCheckerInterface
{
    private $stack = array();

    protected function getInterfaceName()
    {
        return PotentiallyEmptyCheckerInterface::class;
    }

    public function isPotentiallyEmpty(ExpressionInterface $expression)
    {
        /** @var ExpressionInterface $expression */
        $expression = $this->resolveArgument($expression);
        
        if (in_array($expression, $this->stack, true) === true) {
            throw new CircularDependencyException();
        }

        $handler = $this->getHandlerUsingClassMap($expression);

        array_push($this->stack, $expression);
        $result = $handler->isPotentiallyEmpty($expression);
        array_pop($this->stack);
        return $result;
    }
}