<?php


namespace PeterVanDommelen\Parser\BacktrackingStreaming\Parser;

use PeterVanDommelen\Parser\Expression\Alternative\AlternativeExpressionResult;
use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\BacktrackingStreaming\BacktrackingStreamingParserInterface;
use PeterVanDommelen\Parser\Parser\InputStreamInterface;

class AlternativeParser implements BacktrackingStreamingParserInterface
{
    /** @var \PeterVanDommelen\Parser\BacktrackingStreaming\BacktrackingStreamingParserInterface[] */
    private $alternatives;

    /**
     * @param BacktrackingStreamingParserInterface[] $alternatives
     */
    public function __construct($alternatives)
    {
        $this->alternatives = $alternatives;
    }

    public function parseInputStreamWithBacktracking(InputStreamInterface $input, ExpressionResultInterface $previous_result = null)
    {
        /** @var AlternativeExpressionResult $previous_result */
        if ($previous_result !== null && $previous_result instanceof AlternativeExpressionResult === false) {
            throw new \Exception("Expected " . AlternativeExpressionResult::class);
        }

        $alternatives = $this->alternatives;

        /** @var BacktrackingStreamingParserInterface|false $current_alternative */
        $current_alternative = reset($alternatives);

        $active = ($previous_result === null);
        while ($active === false) {
            $active = key($alternatives) === $previous_result->getKey();
            if ($active === true) {
                $alternative_result = $current_alternative->parseInputStreamWithBacktracking($input, $previous_result->getResult());
                if ($alternative_result !== null) {
                    return new AlternativeExpressionResult($alternative_result, key($alternatives));
                }
            }
            $current_alternative = next($alternatives);
        }

        while ($current_alternative !== false) {
            $current_alternative = current($alternatives);

            $alternative_result = $current_alternative->parseInputStreamWithBacktracking($input);
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