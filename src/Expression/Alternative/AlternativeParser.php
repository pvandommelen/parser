<?php


namespace PeterVanDommelen\Parser\Expression\Alternative;

use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Parser\ParserInterface;

class AlternativeParser implements ParserInterface
{
    /** @var ParserInterface[] */
    private $alternatives;

    /**
     * @param ParserInterface[] $alternatives
     */
    public function __construct($alternatives)
    {
        $this->alternatives = $alternatives;
    }

    public function parse($string, ExpressionResultInterface $previous_result = null)
    {
        /** @var AlternativeExpressionResult $previous_result */
        if ($previous_result !== null && $previous_result instanceof AlternativeExpressionResult === false) {
            throw new \Exception("Expected " . AlternativeExpressionResult::class);
        }

        $alternatives = $this->alternatives;

        /** @var ParserInterface|false $current_alternative */
        $current_alternative = reset($alternatives);

        $active = ($previous_result === null);
        while ($active === false) {
            $active = key($alternatives) === $previous_result->getKey();
            if ($active === true) {
                $alternative_result = $current_alternative->parse($string, $previous_result->getResult());
                if ($alternative_result !== null) {
                    return new AlternativeExpressionResult($alternative_result, key($alternatives));
                }
            }
            $current_alternative = next($alternatives);
        }

        while ($current_alternative !== false) {
            $current_alternative = current($alternatives);

            $alternative_result = $current_alternative->parse($string);
            if ($alternative_result !== null) {
                if ($previous_result !== null && $previous_result->getLength() === $alternative_result->getLength()) {
                    return null;
                }
                return new AlternativeExpressionResult($alternative_result, key($alternatives));
            }
            $current_alternative = next($alternatives);
        }

        return null;
    }

}