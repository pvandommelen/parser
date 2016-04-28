<?php


namespace PeterVanDommelen\Parser\Expression\Named;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;

class NamedExpression implements ExpressionInterface
{
    /** @var string */
    private $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

}