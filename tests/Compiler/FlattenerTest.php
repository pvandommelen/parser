<?php


namespace PeterVanDommelen\Parser\Compiler;


use PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpression;
use PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpression;
use PeterVanDommelen\Parser\Expression\Constant\ConstantExpression;
use PeterVanDommelen\Parser\ParserHelper;

class FlattenerTest extends \PHPUnit_Framework_TestCase
{

    public function testFlattenerCircular() {
        $flattener = ParserHelper::createFlattener();

        $expression = null;

        $expression = new ConcatenatedExpression(array(
            new ConstantExpression("a"),
            new AlternativeExpression(array(
                &$expression,
                new ConstantExpression("abc"),
            ))
        ));

        $flattened = $flattener->flattenExpression($expression);
        $this->assertCount(4, $flattened);
    }
}