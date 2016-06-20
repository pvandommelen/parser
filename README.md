A parser generator written in PHP.

## Features
Supports backtracking.

Detects left recursion.

Directly matches strings so it contains no lexing (tokenizer) step

## General usage
The available helper class and its static methods should be used:

    PeterVanDommelen\Parser\ParserHelper::compile(ExpressionInterface $expression)
    PeterVanDommelen\Parser\ParserHelper::compileWithGrammar(ExpressionInterface $expression, Grammar $grammar)

These methods accept an expression (see below which ones are available) and maybe a grammar (see below). The return value is the parser which offers one method:

    PeterVanDommelen\Parser\Parser\ParserInterface::parse(string $string)

The parse method will return null if the parser was unable to match the string. The parser will always match from the start. If succesfully matched a result object corresponding to the parsed expression will be returned.

The result object implements ExpressionResultInterface which has the following two methods describing the matched string:

    PeterVanDommelen\Parser\Expression\ExpressionResultInterface::getString()
    PeterVanDommelen\Parser\Expression\ExpressionResultInterface::getLength()

The specific implementation depends on the expression used and may have additional methods available, see the examples.

## Available expressions
### Constant
PeterVanDommelen\Parser\Expression\Constant\ConstantExpression

    new ConstantExpression(string $string)
Basic terminal.

### Concatenated
PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpression

    new ConcatenatedExpression(ExpressionInterface[] $parts);

Example:

    $result = ParserHelper::compile(new ConcatenatedExpression(array(
        new ConstantExpression("a"),
        new ConstantExpression("b"),
    )))->parse("abc");

    $result->getString(); // "ab"
    $result->getPart(0)->getString(); // "a"
    $result->getPart(1)->getString(); // "b"

### Alternative
PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpression

    new AlternativeExpression(ExpressionInterface[] $alternatives)

Matches one of the expressions.

    $result = ParserHelper::compile(new AlternativeExpression(array(
        new ConstantExpression("a"),
        new ConstantExpression("b"),
    )))->parse("abc");

    $result->getString(); // "a"
    $result->getKey(); // 0
    $result->getResult()->getString(); // "a"

### Repeater
PeterVanDommelen\Parser\Expression\Repeater\RepeaterExpression

    new RepeaterExpression(ExpressionInterface $inner_expression, bool $is_lazy = false, int $minimum = 0, int|null $maximum = null)

Repeats the inner expression with the specified minimum and maximum.

    $result = ParserHelper::compile(new RepeaterExpression(new ConstantExpression("a"))->parse("aaabc");

    $result->getString(); // "aaa"
    $result->getResults()[0]->getString(); // "a"

### Joined
PeterVanDommelen\Parser\Expression\Joined\JoinedExpression

    new JoinedExpression(ExpressionInterface $inner_expression, ExpressionInterface $seperator_expression, bool $is_lazy = false, int $minimum = 0, int|null $maximum = null)

A variation of the RepeaterExpression but with a seperator between elements

    $result->getResults();
    $result->getSeperators(); //will have a size of count($result->getResults) - 1

### Not
PeterVanDommelen\Parser\Expression\Not\NotExpression

    new NotExpression(ExpressionInterface $inner_expression)
Matches a single character only if the inner expression does not match. The example below
would match any character but "a" or "b":

    new NotExpression(new AlternativeExpression(array(
        new ConstantExpression("a"),
        new ConstantExpression("b")
    ))
### Any
PeterVanDommelen\Parser\Expression\Any\AnyExpression

    new AnyExpression()
Matches any single character.
### Named
use PeterVanDommelen\Parser\Expression\Named\NamedExpression

    new NamedExpression(string $name)
See grammar below

## Grammar
The compiler supports using a grammar where entries can be referenced by using a NamedExpression.

For example, if we want to find the string not enclosed within pairs of matching brackets:

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

    $result = $parser->parse("ab(c(d)ef)g");

    $result->getString(); // "ab(c(d)ef)g"
    switch ($result->getKey()) {
        case 0:
            return $result->getResult()->getPart(0)->getString() . $result->getResult()->getPart(4)->getString(); // "abg"
        case 1:
            // this branch is not reached
            return $result->getResult()->getString(); //
    }

## Performance
Ignoring compilation, you should expect this parser to be roughly 100x slower than the native preg_match.
