<?php


namespace PeterVanDommelen\Parser\Expression;


use PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpression;
use PeterVanDommelen\Parser\Expression\Any\AnyExpression;
use PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpression;
use PeterVanDommelen\Parser\Expression\Constant\ConstantExpression;
use PeterVanDommelen\Parser\Expression\Not\NotExpression;
use PeterVanDommelen\Parser\Expression\Repeater\RepeaterExpression;
use PeterVanDommelen\Parser\ParserHelper;

class NotTest extends \PHPUnit_Framework_TestCase
{
    public function testBasic() {
        $expression = new RepeaterExpression(new NotExpression(new ConstantExpression("c")));

        $parser = ParserHelper::compile($expression);

        $result = $parser->parse("abc");
        $this->assertNotNull($result);
        $this->assertEquals("ab", $result->getString());

        $result = $parser->parse("abc", $result);
        $this->assertNotNull($result);
        $this->assertEquals("a", $result->getString());

        $result = $parser->parse("abc", $result);
        $this->assertNotNull($result);
        $this->assertEquals("", $result->getString());

        $result = $parser->parse("abc", $result);
        $this->assertNull($result);
    }

    public function testMultiple() {
        $expression = new RepeaterExpression(new NotExpression(new AlternativeExpression(array(
            new ConstantExpression("b"),
            new ConstantExpression("c")
        ))));

        $parser = ParserHelper::compile($expression);

        $result = $parser->parse("abc");
        $this->assertNotNull($result);
        $this->assertEquals("a", $result->getString());

        $result = $parser->parse("abc", $result);
        $this->assertNotNull($result);
        $this->assertEquals("", $result->getString());

        $result = $parser->parse("abc", $result);
        $this->assertNull($result);
    }

    public function testEscapedString() {
        $quote_expression = new ConstantExpression('"');
        $backslash_expression = new ConstantExpression("\\");
        $expression = new ConcatenatedExpression(array(
            $quote_expression,
            new RepeaterExpression(new AlternativeExpression(array(
                new NotExpression(new AlternativeExpression(array(
                    $quote_expression,
                    $backslash_expression
                ))),
                new ConcatenatedExpression(array(
                    $backslash_expression,
                    new AnyExpression(),
                )),
            ))),
            $quote_expression
        ));

        $parser = ParserHelper::compile($expression);

        $target = '"ab\"cd\e"';

        $result = $parser->parse($target);
        $this->assertNotNull($result);
        $this->assertEquals('"ab\"cd\e"', $result->getString());

        $result = $parser->parse($target, $result);
        $this->assertNull($result);
    }

    public function testUtf8() {
        $expression = new AnyExpression();
        $parser = ParserHelper::compile($expression);

        $target = "€€€";

        $result = $parser->parse($target);
        $this->assertNotNull($result);
        $this->assertEquals("€", $result->getString());
    }
}