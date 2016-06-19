<?php


namespace PeterVanDommelen\Parser\BacktrackingStreaming\Parser;


use PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpressionResult;
use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\BacktrackingStreaming\BacktrackingStreamingParserInterface;
use PeterVanDommelen\Parser\Parser\InputStreamInterface;

class ConcatenatedParser implements BacktrackingStreamingParserInterface
{
    /** @var BacktrackingStreamingParserInterface[] */
    private $parts;

    private $index_to_key;

    private $key_to_index;

    /**
     * @param BacktrackingStreamingParserInterface[] $parts
     */
    public function __construct(array $parts)
    {
        $this->key_to_index = array_keys($parts);
        $this->index_to_key = array_flip($this->key_to_index);
        $this->parts = array_values($parts);
    }

    public function parseInputStreamWithBacktracking(InputStreamInterface $input, ExpressionResultInterface $previous_result = null)
    {
        if ($previous_result !== null && $previous_result instanceof ConcatenatedExpressionResult === false) {
            throw new \Exception("Expected " . ConcatenatedExpressionResult::class);
        }
        /** @var ConcatenatedExpressionResult|null $previous_result */

        $position = 0;
        if ($previous_result !== null) {
            $part_results = array_values($previous_result->getParts());
            $index = count($this->parts) - 1;
            for ($i = 0; $i < count($this->parts); $i += 1) {
                $position += $part_results[$i]->getLength();
            }
            $input->move($position);
        } else {
            $part_results = array_fill(0, count($this->parts), null);
            $index = 0;
        }

        for (; $index < count($this->parts);) {
            $part = $this->parts[$index];

            if ($part_results[$index] !== null) {
                $input->move(-$part_results[$index]->getLength());
                $position -= $part_results[$index]->getLength();
            }

            $part_result = $part->parseInputStreamWithBacktracking($input, $part_results[$index]);

            if ($part_result === null) {
                if ($index === 0) {
                    $input->move(-$position);
                    return null;
                }
                $part_results[$index] = $part_result;
                $index -= 1;
            } else {
                $position += $part_result->getLength();
                $part_results[$index] = $part_result;
                $index += 1;
            }
        }

        $corrected_part_results = array_combine($this->key_to_index, $part_results);

        return new ConcatenatedExpressionResult($corrected_part_results);
    }


}