<?php


namespace PeterVanDommelen\Parser\Expression;


use PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpression;
use PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpression;
use PeterVanDommelen\Parser\Expression\Constant\ConstantExpression;
use PeterVanDommelen\Parser\ParserHelper;

class AlternativeTest extends \PHPUnit_Framework_TestCase
{

    public function testAlternativeFirst() {
        $target = "abcde";
        $parser = ParserHelper::compile(new AlternativeExpression(array(
            new ConstantExpression("abc"),
            new ConstantExpression("de"),
        )));

        $result = $parser->parse($target);

        $this->assertNotNull($result);

        $this->assertEquals(3, $result->getLength());
        $this->assertEquals(0, $result->getKey());
        $this->assertEquals("abc", $result->getResult()->getString());
    }

    public function testAlternativeLast() {
        $target = "abcde";
        $parser = ParserHelper::compile(new AlternativeExpression(array(
            new ConstantExpression("de"),
            new ConstantExpression("abc"),
        )));

        $result = $parser->parse($target);

        $this->assertNotNull($result);

        $this->assertEquals(3, $result->getLength());
        $this->assertEquals(1, $result->getKey());
        $this->assertEquals("abc", $result->getResult()->getString());
    }

    public function testEmptyDoesNotMatch() {
        $target = "abcde";
        $parser = ParserHelper::compile(new AlternativeExpression(array()));

        $result = $parser->parse($target);
        $this->assertNull($result);
    }

    public function testDoesNotMatch() {
        $target = "xabcde";
        $parser = ParserHelper::compile(new AlternativeExpression(array(
            new ConstantExpression("abc"),
            new ConstantExpression("de"),
        )));

        $result = $parser->parse($target);

        $this->assertNull($result);
    }

    public function testBacktrack() {
        $target = "abcde";

        $parser = ParserHelper::compile(new ConcatenatedExpression(array(
            new AlternativeExpression(array(
                new ConstantExpression("abc"),
                new ConstantExpression("ab"),
                new ConstantExpression("a"),
            )),
            new ConstantExpression("b")
        )));

        $result = $parser->parse($target);

        $this->assertNotNull($result);
        $this->assertEquals(2, $result->getPart(0)->getKey());
    }

    public function testAlternativeKey() {
        $parser = ParserHelper::compile(new AlternativeExpression(array(
            1 => new ConstantExpression("a"),
            "key" => new ConstantExpression("b")
        )));

        $result = $parser->parse("a");
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->getKey());

        $result = $parser->parse("b");
        $this->assertNotNull($result);
        $this->assertEquals("key", $result->getKey());
    }
}