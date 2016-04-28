<?php


namespace PeterVanDommelen\Parser\Expression;


use PeterVanDommelen\Parser\Expression\EndOfString\EndOfStringExpression;
use PeterVanDommelen\Parser\ParserHelper;

class EndOfStringTest extends \PHPUnit_Framework_TestCase
{
    public function testEndOfString() {
        $expression = new EndOfStringExpression();
        $parser = ParserHelper::compile($expression);

        $result = $parser->parse("");
        $this->assertNotNull($result);
        $result = $parser->parse("", $result);
        $this->assertNull($result);

        $result = $parser->parse("abc");
        $this->assertNull($result);
    }
}