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

        $this->assertNull($parser->parse($target, $result));
    }

    public function testEmptyInnerExpression() {
        $this->expectException(InvalidExpressionException::class);

        ParserHelper::compile(new RepeaterExpression(new ConstantExpression("")));
    }

    public function testLazyEmptyInnerExpression() {
        $this->expectException(InvalidExpressionException::class);

        ParserHelper::compile(new RepeaterExpression(new ConstantExpression(""), true));
    }

    public function testGreedyAndBacktrack() {
        $target = "aaabc";

        $parser = ParserHelper::compile(new RepeaterExpression(new ConstantExpression("a")));

        $result = $parser->parse($target);
        $this->assertNotNull($result);
        $this->assertEquals(3, $result->getLength());
        $this->assertCount(3, $result->getResults());

        $result = $parser->parse($target, $result);
        $this->assertNotNull($result);
        $this->assertEquals(2, $result->getLength());
        $this->assertCount(2, $result->getResults());

        $result = $parser->parse($target, $result);
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->getLength());
        $this->assertCount(1, $result->getResults());

        $result = $parser->parse($target, $result);
        $this->assertNotNull($result);
        $this->assertEquals(0, $result->getLength());
        $this->assertCount(0, $result->getResults());

        $result = $parser->parse($target, $result);
        $this->assertNull($result);
    }

    public function testGreedyWithMinimumAndMaximumAndBacktrack() {
        $target = "aaabc";

        $parser = ParserHelper::compile(new RepeaterExpression(new ConstantExpression("a"), false, 1, 2));

        $result = $parser->parse($target);
        $this->assertNotNull($result);
        $this->assertEquals(2, $result->getLength());
        $this->assertCount(2, $result->getResults());

        $result = $parser->parse($target, $result);
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->getLength());
        $this->assertCount(1, $result->getResults());

        $result = $parser->parse($target, $result);
        $this->assertNull($result);
    }

    public function testGreedyInnerBacktrack() {
        $target = "abc";

        $parser = ParserHelper::compile(new RepeaterExpression(new AlternativeExpression(array(
            new ConstantExpression("ab"),
            new ConstantExpression("a")
        ))));

        $result = $parser->parse($target);
        $this->assertNotNull($result);
        $this->assertEquals(2, $result->getLength());
        $this->assertCount(1, $result->getResults());

        $result = $parser->parse($target, $result);
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->getLength());
        $this->assertCount(1, $result->getResults());

        $result = $parser->parse($target, $result);
        $this->assertNotNull($result);
        $this->assertEquals(0, $result->getLength());
        $this->assertCount(0, $result->getResults());

        $result = $parser->parse($target, $result);
        $this->assertNull($result);
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

    public function testLazyAndBacktrack() {
        $target = "aaabc";

        $parser = ParserHelper::compile(new RepeaterExpression(new ConstantExpression("a"), true));

        $result = $parser->parse($target);
        $this->assertNotNull($result);
        $this->assertEquals(0, $result->getLength());
        $this->assertCount(0, $result->getResults());

        $result = $parser->parse($target, $result);
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->getLength());
        $this->assertCount(1, $result->getResults());

        $result = $parser->parse($target, $result);
        $this->assertNotNull($result);
        $this->assertEquals(2, $result->getLength());
        $this->assertCount(2, $result->getResults());

        $result = $parser->parse($target, $result);
        $this->assertNotNull($result);
        $this->assertEquals(3, $result->getLength());
        $this->assertCount(3, $result->getResults());

        $result = $parser->parse($target, $result);
        $this->assertNull($result);
    }

    public function testLazyWithMinimumAndMaximumAndBacktrack() {
        $target = "aaabc";

        $parser = ParserHelper::compile(new RepeaterExpression(new ConstantExpression("a"), true, 1, 2));

        $result = $parser->parse($target);
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->getLength());
        $this->assertCount(1, $result->getResults());

        $result = $parser->parse($target, $result);
        $this->assertNotNull($result);
        $this->assertEquals(2, $result->getLength());
        $this->assertCount(2, $result->getResults());

        $result = $parser->parse($target, $result);
        $this->assertNull($result);
    }

    public function testLazyInnerBacktrack() {
        $target = "abc";

        $parser = ParserHelper::compile(new RepeaterExpression(new AlternativeExpression(array(
            new ConstantExpression("ab"),
            new ConstantExpression("a")
        )), true));

        $result = $parser->parse($target);
        $this->assertNotNull($result);
        $this->assertEquals(0, $result->getLength());
        $this->assertCount(0, $result->getResults());

        $result = $parser->parse($target, $result);
        $this->assertNotNull($result);
        $this->assertEquals(2, $result->getLength());
        $this->assertCount(1, $result->getResults());

        $result = $parser->parse($target, $result);
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->getLength());
        $this->assertCount(1, $result->getResults());

        $result = $parser->parse($target, $result);
        $this->assertNull($result);
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
}