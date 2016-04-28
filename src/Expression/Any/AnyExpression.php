<?php


namespace PeterVanDommelen\Parser\Expression\Any;


use PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpression;
use PeterVanDommelen\Parser\Expression\Not\NotExpression;

class AnyExpression extends NotExpression
{

    public function __construct()
    {
        parent::__construct(new AlternativeExpression(array()));
    }
}