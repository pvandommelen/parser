<?php


namespace PeterVanDommelen\Parser\Expression;



use PeterVanDommelen\Parser\Expression\Constant\ConstantExpression;
use PeterVanDommelen\Parser\ParserHelper;

class ConstantTest extends \PHPUnit_Framework_TestCase
{

    public function testStringMatches() {
        $target = "abcdefg";
        $parser = ParserHelper::compile(new ConstantExpression("abc"));

        $result = $parser->parse($target);

        $this->assertNotNull($result);

        $this->assertEquals(3, $result->getLength());
        $this->assertEquals("abc", $result->getString());

        $this->assertNull($parser->parse($target, $result));
    }

    public function testStringDoesNotMatch() {
        $parser = ParserHelper::compile(new ConstantExpression("abc"));

        $result = $parser->parse("xabcdefg");

        $this->assertNull($result);
    }
}