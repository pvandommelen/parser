<?php


namespace PeterVanDommelen\Parser\Expression\Repeater;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Parser\StringUtil;

class GreedyRepeaterParser extends AbstractRepeaterParser
{
    private function parseWithPreviousResult($string, RepeaterExpressionResult $previous_result) {
        $part_results = $previous_result->getResults();
        $length = count($part_results);

        if ($length === 0) {
            return null;
        }

        $index = $length - 1;

        //we need to backtrack the last result.
        //find the position where the last entry starts
        $position = $previous_result->getLength() - $part_results[$index]->getLength();

        //backtrack that entry
        $last_part = $this->inner->parse(StringUtil::slice($string, $position), $part_results[$index]);


        if ($last_part === null) {
            //it failed, our new result excludes this item. Still atleast the minimum?
            if ($index < $this->minimum) {
                return null;
            }
            return new RepeaterExpressionResult(array_slice($part_results, 0, $index));
        }

        //replace the entry
        $part_results[$index] = $last_part;
        return new RepeaterExpressionResult($part_results);
    }

    public function parse($string, ExpressionResultInterface $previous_result = null)
    {
        /** @var RepeaterExpressionResult|null $previous_result */
        if ($previous_result !== null) {
            return $this->parseWithPreviousResult($string, $previous_result);
        }

        $part_results = array();
        $position = 0;

        while (count($part_results) < $this->maximum) {
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