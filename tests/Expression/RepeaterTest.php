<?php


namespace PeterVanDommelen\Parser\Expression;


use PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpression;
use PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpression;
use PeterVanDommelen\Parser\Expression\Constant\ConstantExpression;
use PeterVanDommelen\Parser\Expression\Repeater\RepeaterExpression;
use PeterVanDommelen\Parser\ParserHelper;
use PeterVanDommelen\Parser\Rewriter\InvalidExpressionException;

class RepeaterTest extends \PHPUnit_Framework_TestCase
{

    public function testEmptyTarget() {
        $target = "";

        $parser = ParserHelper::compile(new RepeaterExpression(new ConstantExpression("a")));

        $result = $parser->parse($target);

        $this->assertNotNull($result);

        $this->assertEquals(0, $result->getLength());
        $this->assertCount(0, $result->getResults());
    }

    public function testEmptyInnerExpression() {
        $this->expectException(InvalidExpressionException::class);

        ParserHelper::compile(new RepeaterExpression(new ConstantExpression("")));
    }

    public function testLazyEmptyInnerExpression() {
        $this->expectException(InvalidExpressionException::class);

        ParserHelper::compile(new RepeaterExpression(new ConstantExpression(""), true));
    }

    public function testGreedy() {
        $target = "aaabc";

        $parser = ParserHelper::compile(new RepeaterExpression(new ConstantExpression("a")));

        $result = $parser->parse($target);
        $this->assertNotNull($result);
        $this->assertEquals(3, $result->getLength());
        $this->assertCount(3, $result->getResults());
    }

    public function testGreedyWithMinimumAndMaximum() {
        $target = "aaabc";

        $parser = ParserHelper::compile(new RepeaterExpression(new ConstantExpression("a"), false, 1, 2));

        $result = $parser->parse($target);
        $this->assertNotNull($result);
        $this->assertEquals(2, $result->getLength());
        $this->assertCount(2, $result->getResults());
    }

    public function testGreedyInnerBacktrack() {
        $parser = ParserHelper::compile(new ConcatenatedExpression(array(
            new RepeaterExpression(new AlternativeExpression(array(
                new ConstantExpression("ab"),
                new ConstantExpression("a")
            ))),
            new ConstantExpression("b")
        )));

        $result = $parser->parse("abc");
        $this->assertNotNull($result);
        $this->assertEquals(2, $result->getLength());
        $this->assertCount(1, $result->getPart(0)->getResults());

        $result = $parser->parse("aba");
        $this->assertNotNull($result);
        $this->assertEquals(2, $result->getLength());
        $this->assertCount(1, $result->getPart(0)->getResults());

        $result = $parser->parse("abab");
        $this->assertNotNull($result);
        $this->assertEquals(4, $result->getLength());
        $this->assertCount(2, $result->getPart(0)->getResults());
    }

    public function testConcatenatedEmpty() {
        $target = "abc";

        $parser = ParserHelper::compile(new ConcatenatedExpression(array(
            new RepeaterExpression(new ConstantExpression("a")),
            new ConstantExpression("a")
        )));

        $result = $parser->parse($target);
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->getLength());
    }

    public function testRecursiveGreedyGreedy() {
        $this->expectException(InvalidExpressionException::class);

        // greedy -> greedy -> constant
        ParserHelper::compile(new RepeaterExpression(new RepeaterExpression(new ConstantExpression("a"))));
    }

    public function testLazy() {
        $target = "aaabc";

        $parser = ParserHelper::compile(new RepeaterExpression(new ConstantExpression("a"), true));

        $result = $parser->parse($target);
        $this->assertNotNull($result);
        $this->assertEquals(0, $result->getLength());
        $this->assertCount(0, $result->getResults());
    }

    public function testLazyWithMinimumAndMaximum() {
        $target = "aaabc";

        $parser = ParserHelper::compile(new RepeaterExpression(new ConstantExpression("a"), true, 1, 2));

        $result = $parser->parse($target);
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->getLength());
        $this->assertCount(1, $result->getResults());
    }

    public function testLazyConcatenated() {
        $target = "abc";

        $parser = ParserHelper::compile(new ConcatenatedExpression(array(
            new RepeaterExpression(new ConstantExpression("a"), true),
            new ConstantExpression("b")
        )));

        $result = $parser->parse($target);
        $this->assertNotNull($result);
        $this->assertEquals(2, $result->getLength());
    }

    public function testLazyConcatenatedEmpty() {
        $target = "abc";

        $parser = ParserHelper::compile(new ConcatenatedExpression(array(
            new RepeaterExpression(new ConstantExpression("a"), true),
            new ConstantExpression("a")
        )));

        $result = $parser->parse($target);
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->getLength());
    }

    public function testRecursiveLazyLazy() {
        $this->expectException(InvalidExpressionException::class);

        $target = "abc";

        // lazy -> lazy -> constant
        $parser = ParserHelper::compile(new RepeaterExpression(new RepeaterExpression(new ConstantExpression("a"), true), true));

        $result = $parser->parse($target);
        $this->assertNotNull($result);
        $this->assertEquals(2, $result->getLength());
    }

    public function testLeftRecursionRepeater() {
        $this->expectException(InvalidExpressionException::class);

        $expression = null;
        $expression = new RepeaterExpression(new ConcatenatedExpression(array(
            &$expression,
            new ConstantExpression("a")
        )));
        ParserHelper::compile($expression)->parse("a");
    }
}