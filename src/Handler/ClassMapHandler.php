<?php


namespace PeterVanDommelen\Parser\Handler;

/**
 * Some re-usable functionality
 */
abstract class ClassMapHandler
{
    private $handlers;

    abstract protected function getInterfaceName();

    /**
     * @param mixed[] $handlers
     */
    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;

        foreach ($this->handlers as $handler) {
            if (in_array($this->getInterfaceName(), class_implements($handler)) === false) {
                throw new \Exception(sprintf("Handler %s does not implement interface %s", get_class($handler), $this->getInterfaceName()));
            }
            if ($handler instanceof RecursionAwareInterface) {
                $handler->setRecursiveHandler($this);
            }
        }
    }

    protected function resolveArgument($argument) {
        while ($argument instanceof LazyResult) {
            $argument = $argument->getResult();
        }
        return $argument;
    }

    /**
     * @param $argument
     * @return static
     */
    protected function getHandlerUsingClassMap($argument) {
        $classname = get_class($argument);
        if (isset($this->handlers[$classname]) === false) {
            throw new \Exception("No handler defined for: " . $classname);
        }
        return $this->handlers[$classname];
    }

}