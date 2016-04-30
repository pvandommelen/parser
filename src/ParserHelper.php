<?php


namespace PeterVanDommelen\Parser;


use PeterVanDommelen\Parser\Asserter\HasNoEmptyRepeaterAsserter;
use PeterVanDommelen\Parser\Asserter\HasNoLeftRecursionAsserter;
use PeterVanDommelen\Parser\Asserter\MultipleAsserter;
use PeterVanDommelen\Parser\Compiler\AsserterCompiler;
use PeterVanDommelen\Parser\Compiler\Compiler;
use PeterVanDommelen\Parser\Compiler\RewriterCompiler;
use PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpression;
use PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpressionCompiler;
use PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpressionEmptyChecker;
use PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpressionFlattener;
use PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpressionRewriter;
use PeterVanDommelen\Parser\Expression\Any\AnyExpression;
use PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpression;
use PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpressionCompiler;
use PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpressionEmptyChecker;
use PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpressionFlattener;
use PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpressionRewriter;
use PeterVanDommelen\Parser\Expression\Constant\ConstantExpression;
use PeterVanDommelen\Parser\Expression\Constant\ConstantExpressionCompiler;
use PeterVanDommelen\Parser\Expression\Constant\ConstantExpressionEmptyChecker;
use PeterVanDommelen\Parser\Expression\EndOfString\EndOfStringCompiler;
use PeterVanDommelen\Parser\Expression\EndOfString\EndOfStringEmptyChecker;
use PeterVanDommelen\Parser\Expression\EndOfString\EndOfStringExpression;
use PeterVanDommelen\Parser\Expression\ExpressionInterface;
use PeterVanDommelen\Parser\Expression\Joined\JoinedExpression;
use PeterVanDommelen\Parser\Expression\Joined\JoinedExpressionRewriter;
use PeterVanDommelen\Parser\Expression\Named\Grammar;
use PeterVanDommelen\Parser\Expression\Named\NamedExpression;
use PeterVanDommelen\Parser\Expression\Named\NamedExpressionRewriter;
use PeterVanDommelen\Parser\Expression\Not\NotExpression;
use PeterVanDommelen\Parser\Expression\Not\NotExpressionCompiler;
use PeterVanDommelen\Parser\Expression\Not\NotExpressionEmptyChecker;
use PeterVanDommelen\Parser\Expression\Not\NotExpressionFlattener;
use PeterVanDommelen\Parser\Expression\Not\NotExpressionRewriter;
use PeterVanDommelen\Parser\Expression\Regex\RegexExpression;
use PeterVanDommelen\Parser\Expression\Regex\RegexExpressionCompiler;
use PeterVanDommelen\Parser\Expression\Regex\RegexExpressionEmptyChecker;
use PeterVanDommelen\Parser\Expression\Repeater\RepeaterExpression;
use PeterVanDommelen\Parser\Expression\Repeater\RepeaterExpressionCompiler;
use PeterVanDommelen\Parser\Expression\Repeater\RepeaterExpressionEmptyChecker;
use PeterVanDommelen\Parser\Expression\Repeater\RepeaterExpressionFlattener;
use PeterVanDommelen\Parser\Expression\Repeater\RepeaterExpressionRewriter;
use PeterVanDommelen\Parser\ExpressionFlattener\ExpressionFlattener;
use PeterVanDommelen\Parser\ExpressionFlattener\ExpressionFlattenerInterface;
use PeterVanDommelen\Parser\ExpressionFlattener\TerminateExpressionFlattener;
use PeterVanDommelen\Parser\Parser\ParserInterface;
use PeterVanDommelen\Parser\PotentiallyEmptyChecker\PotentiallyEmptyChecker;
use PeterVanDommelen\Parser\PotentiallyEmptyChecker\PotentiallyEmptyCheckerInterface;
use PeterVanDommelen\Parser\Rewriter\ClassMapExpressionRewriter;
use PeterVanDommelen\Parser\Rewriter\TerminateExpressionRewriter;

class ParserHelper
{
    private static function createFlattenerMap() {
        $flatteners = array();
        $flatteners[ConstantExpression::class] = new TerminateExpressionFlattener();
        $flatteners[AlternativeExpression::class] = new AlternativeExpressionFlattener();
        $flatteners[ConcatenatedExpression::class] = new ConcatenatedExpressionFlattener();
        $flatteners[RepeaterExpression::class] = new RepeaterExpressionFlattener();
        $flatteners[RegexExpression::class] = new TerminateExpressionFlattener();
        $flatteners[NotExpression::class] = new NotExpressionFlattener();
        $flatteners[AnyExpression::class] = new TerminateExpressionFlattener();
        $flatteners[EndOfStringExpression::class] = new TerminateExpressionFlattener();
        return $flatteners;
    }

    /**
     * @return ExpressionFlattenerInterface
     */
    public static function createFlattener() {
        return new ExpressionFlattener(self::createFlattenerMap());
    }

    private static function createEmptyCheckerMap() {
        $empty_checkers = array();
        $empty_checkers[ConstantExpression::class] = new ConstantExpressionEmptyChecker();
        $empty_checkers[AlternativeExpression::class] = new AlternativeExpressionEmptyChecker();
        $empty_checkers[ConcatenatedExpression::class] = new ConcatenatedExpressionEmptyChecker();
        $empty_checkers[RepeaterExpression::class] = new RepeaterExpressionEmptyChecker();
        $empty_checkers[RegexExpression::class] = new RegexExpressionEmptyChecker();
        $empty_checkers[NotExpression::class] = new NotExpressionEmptyChecker();
        $empty_checkers[AnyExpression::class] = new NotExpressionEmptyChecker();
        $empty_checkers[EndOfStringExpression::class] = new EndOfStringEmptyChecker();

        return $empty_checkers;
    }

    /**
     * @return PotentiallyEmptyCheckerInterface
     */
    public static function createEmptyChecker() {
        return new PotentiallyEmptyChecker(self::createEmptyCheckerMap());
    }

    private static function createRewriterMap(array $extra = array()) {
        return array_merge(array(
            ConstantExpression::class => new TerminateExpressionRewriter(),
            AlternativeExpression::class => new AlternativeExpressionRewriter(),
            ConcatenatedExpression::class => new ConcatenatedExpressionRewriter(),
            RepeaterExpression::class => new RepeaterExpressionRewriter(),
            RegexExpression::class => new TerminateExpressionRewriter,
            AnyExpression::class => new TerminateExpressionRewriter,
            NotExpression::class => new NotExpressionRewriter(),
            EndOfStringExpression::class => new TerminateExpressionRewriter(),
        ), $extra);
    }

    private static function createRewriter(array $extra = array()) {
        return new ClassMapExpressionRewriter(self::createRewriterMap($extra));
    }

    private static function createCompilerMap() {
        $encoding = "utf-8";

        $compilers = array(
            ConstantExpression::class => new ConstantExpressionCompiler(),
            AlternativeExpression::class => new AlternativeExpressionCompiler(),
            ConcatenatedExpression::class => new ConcatenatedExpressionCompiler(),
            RepeaterExpression::class => new RepeaterExpressionCompiler(),
            RegexExpression::class => new RegexExpressionCompiler(),
            AnyExpression::class => new NotExpressionCompiler($encoding),
            NotExpression::class => new NotExpressionCompiler($encoding),
            EndOfStringExpression::class => new EndOfStringCompiler(),
        );

        return $compilers;
    }

    private static function createCompiler(Grammar $grammar = null) {
        $compiler = new Compiler(self::createCompilerMap());

        $expression_flattener = self::createFlattener();
        $potentially_empty_checker = self::createEmptyChecker();
        $compiler = new AsserterCompiler($compiler, new MultipleAsserter(array(
            new HasNoLeftRecursionAsserter($expression_flattener, $potentially_empty_checker),
            new HasNoEmptyRepeaterAsserter($expression_flattener, $potentially_empty_checker),
        )));

        $extra_rewriters = array();
        $extra_rewriters[JoinedExpression::class] = new JoinedExpressionRewriter();
        if ($grammar !== null) {
            $extra_rewriters[NamedExpression::class] = new NamedExpressionRewriter($grammar);
        }
        $rewriter = self::createRewriter($extra_rewriters);
        $compiler = new RewriterCompiler($compiler, $rewriter);

        return $compiler;
    }

    /**
     * @param ExpressionInterface $expression
     * @return ParserInterface
     */
    public static function compile(ExpressionInterface $expression) {
        return self::createCompiler()->compile($expression);
    }

    /**
     * @param ExpressionInterface $expression
     * @param Grammar $grammar
     * @return ParserInterface
     */
    public static function compileWithGrammar(ExpressionInterface $expression, Grammar $grammar) {
        return self::createCompiler($grammar)->compile($expression);
    }
}