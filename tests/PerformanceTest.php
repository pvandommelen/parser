<?php


namespace PeterVanDommelen\Parser;


use PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpression;
use PeterVanDommelen\Parser\Expression\Any\AnyExpression;
use PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpression;
use PeterVanDommelen\Parser\Expression\Constant\ConstantExpression;
use PeterVanDommelen\Parser\Expression\Not\NotExpression;
use PeterVanDommelen\Parser\Expression\Regex\RegexExpression;
use PeterVanDommelen\Parser\Expression\Regex\RegexParser;
use PeterVanDommelen\Parser\Expression\Repeater\RepeaterExpression;
use PeterVanDommelen\Parser\Parser\ParserInterface;

class PerformanceTest extends \PHPUnit_Framework_TestCase
{
    private function runParser(ParserInterface $parser) {
        $target = '"ab\"cd\e"';

        $n = 10000; //increase for more useful data
        for ($i = 0; $i < $n; $i += 1) {
            $result = $parser->parse($target);
        }
    }

    public function testPerformanceEscapedString() {
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
        $this->runParser($parser);
    }

    public function testPerformanceEscapedStringRegexExpression() {
        $expression = new RegexExpression('"(?:[^"\\\\]|\\\\.)*"');

        $parser = ParserHelper::compile($expression);

        $this->runParser($parser);
    }

    public function testPerformanceEscapedStringPregMatch() {
        $regex = '"(?:[^"\\\\]|\\\\.)*"';

        $parser = new RegexParser($regex);
        $this->runParser($parser);
    }
    
    public function testConstantParser() {
        $expression = new RepeaterExpression(new ConstantExpression("aa"));
        $target = str_repeat("a", 100000);

        $parser = ParserHelper::compile($expression);

        $result = $parser->parse($target);
        //var_dump(memory_get_peak_usage(true) / 1000000);
    }
}