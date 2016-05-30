<?php


namespace PeterVanDommelen\Parser\Compiler;


use PeterVanDommelen\Parser\Asserter\HasNoLeftRecursionAsserter;
use PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpression;
use PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpression;
use PeterVanDommelen\Parser\Expression\Constant\ConstantExpression;
use PeterVanDommelen\Parser\Expression\Repeater\RepeaterExpression;
use PeterVanDommelen\Parser\ParserHelper;
use PeterVanDommelen\Parser\Rewriter\InvalidExpressionException;

class LeftRecursionAsserterTest extends \PHPUnit_Framework_TestCase
{
    private function getChecker() {
        return new HasNoLeftRecursionAsserter(
            ParserHelper::createFlattener(),
            ParserHelper::createEmptyChecker()
        );
    }

    public function testSimple() {
        $this->getChecker()->assertExpression(new ConstantExpression(""));
        $this->addToAssertionCount(1);
    }

    private function getLeftRecursionExample() {
        $expression = null;
        $expression = new AlternativeExpression(array(
            &$expression,
            new ConstantExpression("a")
        ));

        return $expression;
    }

    public function testRecursiveAlternative() {
        $this->expectException(InvalidExpressionException::class);
//var_dump((new ClassMapExpressionRewriter(ClassMapExpressionRewriter::getRecursiveRewriters()))->rewriteExpression($this->getLeftRecursionExample()));
        $this->getChecker()->assertExpression($this->getLeftRecursionExample());
    }

    public function testRecursiveNonEmptyConcatenation() {
        $expression = null;
        $expression = new ConcatenatedExpression(array(
            new ConstantExpression("a"),
            &$expression,
        ));

        $this->getChecker()->assertExpression($expression);

        $this->addToAssertionCount(1);
    }

    public function testRecursiveEmptyConcatenation() {
        $this->expectException(InvalidExpressionException::class);

        $expression = null;
        $expression = new ConcatenatedExpression(array(
            new ConstantExpression(""),
            &$expression,
        ));

        $this->getChecker()->assertExpression($expression);
    }

    public function testLeftRecursionWithinConcatenation() {
        $this->expectException(InvalidExpressionException::class);

        $this->getChecker()->assertExpression(new ConcatenatedExpression(array(
            new ConstantExpression("a"),
            $this->getLeftRecursionExample()
        )));
    }

    public function testLeftRecursionRepeater() {
        $this->expectException(InvalidExpressionException::class);

        $expression = null;
        $expression = new RepeaterExpression(new ConcatenatedExpression(array(
            &$expression,
            new ConstantExpression("a")
        )));

        $this->getChecker()->assertExpression($expression);
    }

    public function testInfiniteRecursionWithinAlternative() {
        $this->expectException(InvalidExpressionException::class);

        $expression = null;
        $expression = new AlternativeExpression(array(
            "empty" => new ConstantExpression(""),
            "next" => &$expression,
        ));

        $this->getChecker()->assertExpression($expression);
    }
}