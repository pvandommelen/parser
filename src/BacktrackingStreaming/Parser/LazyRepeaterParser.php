<?php


namespace PeterVanDommelen\Parser\BacktrackingStreaming\Parser;


use PeterVanDommelen\Parser\BacktrackingStreaming\Parser\AbstractRepeaterParser;
use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Expression\Repeater\RepeaterExpressionResult;
use PeterVanDommelen\Parser\Parser\InputStreamInterface;
use PeterVanDommelen\Parser\Parser\StringUtil;

class LazyRepeaterParser extends AbstractRepeaterParser
{

    private function parseWithPreviousResult(InputStreamInterface $input, RepeaterExpressionResult $previous_result) {
        $part_results = $previous_result->getResults();
        $length = count($part_results);

        if ($length > 0) {
            //we can backtrack the previous result
            $index = $length - 1;
            $position = $previous_result->getLength() - $part_results[$index]->getLength();
            $input->move($position);

            $last_part = $this->inner->parseInputStreamWithBacktracking($input, $part_results[$index]);
            if ($last_part !== null) {
                //succesfully backtracked, replace the entry
                $part_results[$index] = $last_part;
                return new RepeaterExpressionResult($part_results);
            }
            //failed to backtrack, fallthrough and find the next entry
            $input->move($part_results[$index]->getLength());
        }

        if ($length >= $this->maximum) {
            return null;
        }

        $next_result = $this->inner->parseInputStreamWithBacktracking($input);
        if ($next_result === null) {
            $input->move(-$previous_result->getLength());
            return null;
        }

        $part_results[] = $next_result;
        return new RepeaterExpressionResult($part_results);
    }

    public function parseInputStreamWithBacktracking(InputStreamInterface $input, ExpressionResultInterface $previous_result = null)
    {
        if ($previous_result !== null) {
            /** @var RepeaterExpressionResult|null $previous_result */
            return $this->parseWithPreviousResult($input, $previous_result);
        }

        $part_results = array();
        $position = 0;

        while (count($part_results) < $this->minimum) {
            $current_part_result = $this->inner->parseInputStreamWithBacktracking($input, null);

            if ($current_part_result === null) {
                break;
            }

            $part_results[] = $current_part_result;
            $position += $current_part_result->getLength();
        }

        if (count($part_results) < $this->minimum) {
            $input->move(-$position);
            return null;
        }

        return new RepeaterExpressionResult(new \ArrayIterator($part_results));
    }

}