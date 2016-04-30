<?php


namespace PeterVanDommelen\Parser\Parser;


use PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpression;
use PeterVanDommelen\Parser\Expression\Regex\RegexExpression;
use PeterVanDommelen\Parser\ParserHelper;
use PeterVanDommelen\Parser\Rewriter\InvalidExpressionException;

class RegexParserTest extends \PHPUnit_Framework_TestCase
{
    public function testEmpty() {
        $parser = ParserHelper::compile(new RegexExpression(""));
        $result = $parser->parse("abc");

        $this->assertNotNull($result);
        $this->assertEquals("", $result->getString());
    }

    public function testSimple() {
        $parser = ParserHelper::compile(new RegexExpression("a?(b)"));
        $result = $parser->parse("abc");

        $this->assertNotNull($result);
        $this->assertEquals("ab", $result->getString());

        $this->assertEquals(array(
            'ab',
            'b'
        ), $result->getMatches());
    }

    public function testMustStartAtIndexZero() {
        $parser = ParserHelper::compile(new RegexExpression("b"));
        $result = $parser->parse("abc");

        $this->assertNull($result);
    }

    public function testDotMatchesNewline() {
        $parser = ParserHelper::compile(new RegexExpression("."));
        $result = $parser->parse("\n");

        $this->assertNotNull($result);
        $this->assertEquals("\n", $result->getString());
    }

    public function testLeftRecursion() {
        $this->expectException(InvalidExpressionException::class);

        $expression = null;
        $expression = new ConcatenatedExpression(array(
            new RegexExpression(".*"),
            &$expression,
        ));

        ParserHelper::compile($expression);
    }

    public function testForwardSlash() {
        $parser = ParserHelper::compile(new RegexExpression("/"));

        $result = $parser->parse("/");
        $this->assertNotNull($result);
        $this->assertEquals("/", $result->getString());
    }

    public function testBackwardSlash() {
        $parser = ParserHelper::compile(new RegexExpression("\\\\"));

        $result = $parser->parse("\\");
        $this->assertNotNull($result);
        $this->assertEquals("\\", $result->getString());
    }

    public function testAnchorAlternative() {
        $parser = ParserHelper::compile(new RegexExpression("ab|cd"));

        $result = $parser->parse("acd");
        $this->assertNull($result);
    }
}