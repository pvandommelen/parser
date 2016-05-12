<?php


namespace PeterVanDommelen\Parser\Parser;


use PeterVanDommelen\Parser\Expression\Constant\ConstantExpression;
use PeterVanDommelen\Parser\Expression\Constant\ConstantExpressionResult;
use PeterVanDommelen\Parser\Expression\Joined\JoinedExpression;
use PeterVanDommelen\Parser\Expression\Joined\JoinedExpressionResult;
use PeterVanDommelen\Parser\ParserHelper;

class JoinedTest extends \PHPUnit_Framework_TestCase
{
    public function testEmpty() {
        $parser = ParserHelper::compile(new JoinedExpression(new ConstantExpression("a"), new ConstantExpression(",")));

        /** @var \PeterVanDommelen\Parser\Expression\Joined\JoinedExpressionResult $result */
        $result = $parser->parse("x");

        $this->assertNotNull($result);
        $this->assertEquals("", $result->getString());
        $this->assertCount(0, $result->getResults());
    }

    public function testOne() {
        $parser = ParserHelper::compile(new JoinedExpression(new ConstantExpression("a"), new ConstantExpression(",")));

        /** @var \PeterVanDommelen\Parser\Expression\Joined\JoinedExpressionResult $result */
        $result = $parser->parse("a");

        $this->assertNotNull($result);
        $this->assertEquals("a", $result->getString());

        $this->assertEquals(array(
            new ConstantExpressionResult("a"),
        ), $result->getResults());
    }

    public function testEndingSeperatorIsIgnored() {
        $parser = ParserHelper::compile(new JoinedExpression(new ConstantExpression("a"), new ConstantExpression(",")));

        /** @var \PeterVanDommelen\Parser\Expression\Joined\JoinedExpressionResult $result */
        $result = $parser->parse("a,");

        $this->assertNotNull($result);
        $this->assertEquals("a", $result->getString());

        $this->assertEquals(array(
            new ConstantExpressionResult("a"),
        ), $result->getResults());
    }

    public function testMultiple() {
        $parser = ParserHelper::compile(new JoinedExpression(new ConstantExpression("a"), new ConstantExpression(",")));
        $target = "a,ab";

        /** @var JoinedExpressionResult $result */
        $result = $parser->parse($target);

        $this->assertNotNull($result);
        $this->assertEquals("a,a", $result->getString());

        $this->assertEquals(array(
            new ConstantExpressionResult("a"),
            new ConstantExpressionResult("a"),
        ), $result->getResults());
    }

    public function testRecursive() {
        $parser = ParserHelper::compile(new JoinedExpression(new JoinedExpression(new ConstantExpression("a"), new ConstantExpression(",")), new ConstantExpression(",")));

        /** @var \PeterVanDommelen\Parser\Expression\Joined\JoinedExpressionResult $result */
        $result = $parser->parse("a,a");

        $this->assertNotNull($result);
        $this->assertEquals("a,a", $result->getString());

        $expected = new JoinedExpressionResult(array(
            new JoinedExpressionResult(array(
                new ConstantExpressionResult("a"),
                new ConstantExpressionResult("a")
            ), array(
                new ConstantExpressionResult(",")
            )),
        ), array());

        $this->assertEquals($expected, $result);
    }
}