<?php


namespace PeterVanDommelen\Parser\Expression;


use PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpression;
use PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpression;
use PeterVanDommelen\Parser\Expression\Constant\ConstantExpression;
use PeterVanDommelen\Parser\Expression\Named\Grammar;
use PeterVanDommelen\Parser\Expression\Named\NamedExpression;
use PeterVanDommelen\Parser\Expression\Not\NotExpression;
use PeterVanDommelen\Parser\Expression\Repeater\RepeaterExpression;
use PeterVanDommelen\Parser\ParserHelper;
use PeterVanDommelen\Parser\Rewriter\InvalidExpressionException;

class NamedTest extends \PHPUnit_Framework_TestCase
{

    public function testStringMatches() {
        $target = "abcdefg";
        $parser = ParserHelper::compileWithGrammar(new NamedExpression("a"), new Grammar(array(
            "a" => new ConstantExpression("abc")
        )));

        $result = $parser->parse($target);

        $this->assertNotNull($result);

        $this->assertEquals(3, $result->getLength());
        $this->assertEquals("abc", $result->getString());
    }

    public function testStringDoesNotMatch() {
        $parser = ParserHelper::compileWithGrammar(new NamedExpression("a"), new Grammar(array(
            "a" => new ConstantExpression("abc")
        )));

        $result = $parser->parse("xabcdefg");

        $this->assertNull($result);
    }

    public function testLeftRecursion() {
        $this->expectException(InvalidExpressionException::class);

        $parser = ParserHelper::compileWithGrammar(new NamedExpression("a"), new Grammar(array(
            "a" => new AlternativeExpression(array(
                new NamedExpression("a"),
                new ConstantExpression("abc")
            ))
        )));
    }

    public function testLeftRecursion2() {
        $this->expectException(InvalidExpressionException::class);

        $parser = ParserHelper::compileWithGrammar(new NamedExpression("a"), new Grammar(array(
            "a" => new AlternativeExpression(array(
                new ConstantExpression("abc"),
                new NamedExpression("a"),
            ))
        )));
    }
    public function testLeftRecursion3() {
        $this->expectException(InvalidExpressionException::class);

        $parser = ParserHelper::compileWithGrammar(new NamedExpression("a"), new Grammar(array(
            "a" => new ConcatenatedExpression(array(
                new NamedExpression("a"),
                new ConstantExpression("abc")
            ))
        )));
    }

    public function testRightRecursion() {
        $parser = ParserHelper::compileWithGrammar(new NamedExpression("a"), new Grammar(array(
            "a" => new ConcatenatedExpression(array(
                new ConstantExpression("a"),
                new AlternativeExpression(array(
                    new NamedExpression("a"),
                    new ConstantExpression("abc"),
                ))
            ))
        )));

        $target = "aaaabc";
        $result = $parser->parse($target);

        $this->assertNotNull($result);

        $this->assertEquals(6, $result->getLength());
        $this->assertEquals("aaaabc", $result->getString());
    }

    public function testRightConcatRecursion2() {
        $parser = ParserHelper::compileWithGrammar(new NamedExpression("a"), new Grammar(array(
            "a" => new ConcatenatedExpression(array(
                new ConstantExpression("a"),
                new AlternativeExpression(array(
                    new ConstantExpression("abc"),
                    new NamedExpression("a"),
                ))
            ))
        )));

        $target = "aaaabc";
        $result = $parser->parse($target);

        $this->assertNotNull($result);

        $this->assertEquals(6, $result->getLength());
        $this->assertEquals("aaaabc", $result->getString());
    }

    public function testBraces() {
        $grammar = new Grammar(array(
            "expression" => new ConcatenatedExpression(array(
                new RepeaterExpression(new NotExpression(new AlternativeExpression(array(
                    new ConstantExpression("(")
                )))),
                new AlternativeExpression(array(
                    new ConcatenatedExpression(array(
                        new ConstantExpression("("),
                        new NamedExpression("expression"),
                        new ConstantExpression(")"),
                    )),
                    new RepeaterExpression(new NotExpression(new AlternativeExpression(array(
                        new ConstantExpression("(")
                    )))),
//                    new ConstantExpression(""),
                )),
                new RepeaterExpression(new NotExpression(new AlternativeExpression(array(
                    new ConstantExpression("(")
                )))),
            ))
        ));

        $no_opening_brace = new RepeaterExpression(new NotExpression(new AlternativeExpression(array(
            new ConstantExpression("(")
        ))));
        $grammar = new Grammar(array(
            "expression" => new AlternativeExpression(array(
                new ConcatenatedExpression(array(
                    $no_opening_brace,
                    new ConstantExpression("("),
                    new NamedExpression("expression"),
                    new ConstantExpression(")"),
                    $no_opening_brace,
                )),
                $no_opening_brace,
            )),
        ));
        $parser = ParserHelper::compileWithGrammar(new NamedExpression("expression"), $grammar);

        $result = $parser->parse("ab");
        $this->assertNotNull($result);
        $this->assertEquals("ab", $result->getString());

        $result = $parser->parse("ab(");
        $this->assertNotNull($result);
        $this->assertEquals("ab", $result->getString());

        $result = $parser->parse("ab(c(d)ef)g");
        $this->assertNotNull($result);
        $this->assertEquals("ab(c(d)ef)g", $result->getString());

        $this->assertEquals("abg", $result->getResult()->getPart(0)->getString() . $result->getResult()->getPart(4)->getString());
    }
}