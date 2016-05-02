<?php


namespace PeterVanDommelen\Parser\Expression\Repeater;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Parser\StringUtil;

class LazyRepeaterParser extends AbstractRepeaterParser
{
    /**
     * @param string $string
     * @param RepeaterExpressionResult $previous_result
     * @return ExpressionResultInterface|null
     */
    private function matchNext($string, RepeaterExpressionResult $previous_result) {
        $part_results = $previous_result->getResults();
        $length = count($part_results);

        if ($length === $this->maximum) {
            return null;
        }

        $position = $previous_result->getLength();

        $last_part = $this->inner->parse(StringUtil::slice($string, $position), null);

        if ($last_part === null) {
            return null;
        }

        //add the result
        $part_results[] = $last_part;
        return new RepeaterExpressionResult($part_results);
    }

    private function parseWithPreviousResult($string, RepeaterExpressionResult $previous_result) {
        $part_results = $previous_result->getResults();
        $length = count($part_results);

        if ($length > 0) {
            //we can backtrack the previous result
            $index = $length - 1;
            $position = $previous_result->getLength() - $part_results[$index]->getLength();

            $last_part = $this->inner->parse(StringUtil::slice($string, $position), $part_results[$index]);
            if ($last_part !== null) {
                //succesfully backtracked, replace the entry
                $part_results[$index] = $last_part;
                return new RepeaterExpressionResult($part_results);
            }
            //failed to backtrack, fallthrough and find the next entry
        }

        return $this->matchNext($string, $previous_result);
    }

    public function parse($string, ExpressionResultInterface $previous_result = null)
    {
        if ($previous_result !== null) {
            /** @var RepeaterExpressionResult|null $previous_result */
            return $this->parseWithPreviousResult($string, $previous_result);
        }

        $part_results = array();
        $position = 0;

        while (count($part_results) < $this->minimum) {
            $current_part_result = $this->inner->parse(StringUtil::slice($string, $position), null);

            if ($current_part_result === null) {
                break;
            }

            $part_results[] = $current_part_result;
            $position += $current_part_result->getLength();
        }

        if (count($part_results) < $this->minimum) {
            return null;
        }

        return new RepeaterExpressionResult($part_results);
    }

}