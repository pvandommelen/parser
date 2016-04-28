<?php


namespace PeterVanDommelen\Parser\Compiler;


use PeterVanDommelen\Parser\Asserter\HasNoEmptyRepeaterAsserter;
use PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpression;
use PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpression;
use PeterVanDommelen\Parser\Expression\Constant\ConstantExpression;
use PeterVanDommelen\Parser\Expression\Repeater\RepeaterExpression;
use PeterVanDommelen\Parser\ParserHelper;
use PeterVanDommelen\Parser\Rewriter\InvalidExpressionException;

class EmptyRepeaterTest extends \PHPUnit_Framework_TestCase
{
    private function getChecker() {
        return new HasNoEmptyRepeaterAsserter(
            ParserHelper::createFlattener(),
            ParserHelper::createEmptyChecker()
        );
    }

    public function testSimple() {
        $this->getChecker()->assertExpression(new ConstantExpression(""));
        $this->addToAssertionCount(1);
    }

    public function testRepeater() {
        $this->expectException(InvalidExpressionException::class);
        $this->getChecker()->assertExpression(new RepeaterExpression(new ConstantExpression("")));
    }

    public function testLazyRepeater() {
        $this->expectException(InvalidExpressionException::class);
        $this->getChecker()->assertExpression(new RepeaterExpression(new ConstantExpression(""), true));
    }

    public function testRepeaterOnAlternative() {
        $this->getChecker()->assertExpression(new RepeaterExpression(new AlternativeExpression(array(
            new ConstantExpression("a")
        ))));
        $this->addToAssertionCount(1);
    }

    public function testRepeaterOnEmptyAlternative() {
        $this->expectException(InvalidExpressionException::class);
        $this->getChecker()->assertExpression(new RepeaterExpression(new AlternativeExpression(array(
            new ConstantExpression("a"),
            new ConstantExpression(""),
        ))));
    }

    public function testConcatenatedEmptyRepeater() {
        $this->expectException(InvalidExpressionException::class);

        $this->getChecker()->assertExpression(new ConcatenatedExpression(array(
            new ConstantExpression("a"),
            new RepeaterExpression(new ConstantExpression(""))
        )));
    }

    public function testRepeatedConcatenated() {
        $this->getChecker()->assertExpression(new RepeaterExpression(new ConcatenatedExpression(array(
            new ConstantExpression("a")
        ))));

        $this->addToAssertionCount(1);
    }
}