<?php


namespace PeterVanDommelen\Parser\Expression;


interface ExpressionResultInterface
{
    /**
     * @return int
     */
    public function getLength();

    /**
     * @return string
     */
    public function getString();
}