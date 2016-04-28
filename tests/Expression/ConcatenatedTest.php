<?php


namespace PeterVanDommelen\Parser\Expression;


use PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpression;
use PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpressionResult;
use PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpression;
use PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpressionResult;
use PeterVanDommelen\Parser\Expression\Constant\ConstantExpression;
use PeterVanDommelen\Parser\Expression\Constant\ConstantExpressionResult;
use PeterVanDommelen\Parser\ParserHelper;

class ConcatenatedTest extends \PHPUnit_Framework_TestCase
{

    public function testEmptyAlwaysMatches() {
        $target = "abcdefg";

        $parser = ParserHelper::compile(new ConcatenatedExpression(array()));
        $result = $parser->parse($target);

        $this->assertNotNull($result);
        $this->assertEquals(0, $result->getLength());
    }

    public function testMultipleMatches() {
        $target = "abcdefg";

        $parser = ParserHelper::compile(new ConcatenatedExpression(array(
            new ConstantExpression("abc"),
            new ConstantExpression("de")
        )));
        $result = $parser->parse($target);

        $this->assertNotNull($result);
        $this->assertEquals(5, $result->getLength());

        $results = $result->getParts();
        $this->assertCount(2, $results);
        $this->assertEquals(array(
            new ConstantExpressionResult("abc"),
            new ConstantExpressionResult("de")
        ), $results);
    }

    public function testBacktracking() {
        $target = "abcdefg";

        $parser = ParserHelper::compile(new ConcatenatedExpression(array(
            new AlternativeExpression(array(
                new ConstantExpression("abc"),
                new ConstantExpression("ab")
            )),
            new ConstantExpression("cd")
        )));
        $result = $parser->parse($target);

        $this->assertNotNull($result);
        $this->assertEquals(4, $result->getLength());

        $results = $result->getParts();
        $this->assertCount(2, $results);
        $this->assertEquals(array(
            new AlternativeExpressionResult(new ConstantExpressionResult("ab"), 1),
            new ConstantExpressionResult("cd")
        ), $results);
    }

    public function testUseKeyAsIndex() {
        $target = "abcdefg";

        $parser = ParserHelper::compile(new ConcatenatedExpression(array(
            "key" => new ConstantExpression("abc")
        )));
        /** @var ConcatenatedExpressionResult $result */
        $result = $parser->parse($target);

        $this->assertNotNull($result);
        $this->assertEquals(3, $result->getLength());

        /** @var ConstantExpressionResult $part_result */
        $part_result = $result->getPart("key");
        $this->assertEquals("abc", $part_result->getString());
    }
}