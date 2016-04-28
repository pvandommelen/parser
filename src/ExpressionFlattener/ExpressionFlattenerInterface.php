<?php


namespace PeterVanDommelen\Parser\ExpressionFlattener;


use PeterVanDommelen\Parser\Expression\ExpressionInterface;

interface ExpressionFlattenerInterface
{
    /**
     * Returns a list of all expressions that are defined recursively inside the supplied expression
     *
     * @param ExpressionInterface $expression
     * @return ExpressionInterface[]
     */
    public function flattenExpression(ExpressionInterface $expression);
}