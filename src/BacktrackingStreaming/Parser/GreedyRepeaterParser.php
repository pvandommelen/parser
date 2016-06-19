<?php


namespace PeterVanDommelen\Parser\BacktrackingStreaming\Parser;


use PeterVanDommelen\Parser\BacktrackingStreaming\Parser\AbstractRepeaterParser;
use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Expression\Repeater\RepeaterExpressionResult;
use PeterVanDommelen\Parser\Parser\InputStreamInterface;
use PeterVanDommelen\Parser\Parser\StringUtil;

class GreedyRepeaterParser extends AbstractRepeaterParser
{
    private function parseWithPreviousResult(InputStreamInterface $input, RepeaterExpressionResult $previous_result) {
        $part_results = $previous_result->getResults();
        $length = count($part_results);

        if ($length === 0) {
            return null;
        }

        $index = $length - 1;

        //we need to backtrack the last result.
        //find the position where the last entry starts
        $position = $previous_result->getLength() - $part_results[$index]->getLength();
        $input->move($position);

        //backtrack that entry
        $last_part = $this->inner->parseInputStreamWithBacktracking($input, $part_results[$index]);


        if ($last_part === null) {
            //it failed, our new result excludes this item. Still atleast the minimum?
            if ($index < $this->minimum) {
                $input->move(-$position);
                return null;
            }
            return new RepeaterExpressionResult(array_slice($part_results, 0, $index));
        }

        //replace the entry
        $part_results[$index] = $last_part;
        return new RepeaterExpressionResult($part_results);
    }

    public function parseInputStreamWithBacktracking(InputStreamInterface $input, ExpressionResultInterface $previous_result = null)
    {
        /** @var RepeaterExpressionResult|null $previous_result */
        if ($previous_result !== null) {
            return $this->parseWithPreviousResult($input, $previous_result);
        }

        $part_results = array();
        $position = 0;

        /** @var string|null $previous_part_result */
        $previous_part_result_string = null;
        /** @var ExpressionResultInterface|null $previous_part_result */
        $previous_part_result = null;

        while (count($part_results) < $this->maximum) {
            if ($previous_part_result !== null && $input->matchesString($previous_part_result_string) === true) {
                //the previous match will work
                $current_part_result = $previous_part_result;
                $input->move(strlen($previous_part_result_string));
            } else {
                $current_part_result = $this->inner->parseInputStreamWithBacktracking($input, null);
            }

            if ($current_part_result === null) {
                break;
            }

            $part_results[] = $current_part_result;
            $position += $current_part_result->getLength();
            $previous_part_result = $current_part_result;
            $previous_part_result_string = $previous_part_result->getString();
        }

        if (count($part_results) < $this->minimum) {
            $input->move(-$position);
            return null;
        }

        return new RepeaterExpressionResult($part_results);
    }

}