<?php


namespace PeterVanDommelen\Parser\Expression\Repeater;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Parser\StringUtil;

class LazyRepeaterParser extends AbstractRepeaterParser
{
    /**
     * @param ExpressionResultInterface[] $part_results
     * @param string $string
     * @return ExpressionResultInterface|null
     */
    private function matchNext(array $part_results, $string) {
        $length = count($part_results);

        if ($length === $this->maximum) {
            return null;
        }

        $position = 0;
        for ($i = 0; $i < $length; $i += 1) {
            $position += $part_results[$i]->getLength();
        }

        $last_part = $this->inner->parse(StringUtil::slice($string, $position), null);
        while ($last_part !== null && $last_part->getLength() === 0) {
            $last_part = $this->inner->parse(StringUtil::slice($string, $position), $last_part);
        }

        if ($last_part === null) {
            return null;
        }

        $part_results[] = $last_part;
        return new RepeaterExpressionResult($part_results);
    }

    private function parseWithPreviousResult($string, RepeaterExpressionResult $previous_result) {
        $part_results = $previous_result->getResults();
        $length = count($part_results);

        if ($length > 0) {
            $position = 0;
            for ($i = 0; $i < $length; $i += 1) {
                $position += $part_results[$i]->getLength();
            }
            $index = $length - 1;

            $position -= $part_results[$index]->getLength();
            $last_part = $this->inner->parse(StringUtil::slice($string, $position), $part_results[$index]);
            if ($last_part !== null) {
                $part_results[$index] = $last_part;
                return new RepeaterExpressionResult($part_results);
            }
        }

        return $this->matchNext($part_results, $string);
    }

    public function parse($string, ExpressionResultInterface $previous_result = null)
    {
        if ($previous_result !== null && $previous_result instanceof RepeaterExpressionResult === false) {
            throw new \Exception("Expected " . RepeaterExpressionResult::class);
        }

        if ($previous_result !== null) {
            /** @var RepeaterExpressionResult|null $previous_result */
            return $this->parseWithPreviousResult($string, $previous_result);
        }

        $part_results = array();
        $position = 0;

        if ($this->minimum === 0) {
            return new RepeaterExpressionResult(array());
        }

        do {
            $current_part_result = $this->inner->parse(StringUtil::slice($string, $position), null);

            while ($current_part_result !== null && $current_part_result->getLength() === 0) {
                //if a part has no size, we immediately ask for the next one
                $current_part_result = $this->inner->parse(StringUtil::slice($string, $position), $current_part_result);
            }
            if ($current_part_result !== null) {
                $part_results[] = $current_part_result;
                $position += $current_part_result->getLength();
            }
        } while ($current_part_result !== null && count($part_results) !== $this->minimum);

        if (count($part_results) < $this->minimum) {
            return null;
        }

        return new RepeaterExpressionResult($part_results);
    }

}