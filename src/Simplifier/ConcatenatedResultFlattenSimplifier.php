<?php


namespace PeterVanDommelen\Parser\Simplifier;


use PeterVanDommelen\Parser\Expression\Concatenated\ConcatenatedExpressionResult;
use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Rewriter\ExpressionResultRewriterInterface;

class ConcatenatedResultFlattenSimplifier implements ExpressionResultRewriterInterface
{
    private $mapping;

    /**
     * @param $mapping
     */
    public function __construct($mapping)
    {
        $this->mapping = $mapping;
    }

    public function reverseRewriteExpressionResult(ExpressionResultInterface $result)
    {
        /** @var ConcatenatedExpressionResult $result */
        $parts = array();
        $current_index = 0;
        foreach ($this->mapping as $item) {
            if (is_array($item) === true) {
                $subparts = array();
                foreach ($item[1] as $subitem) {
                    $subparts[$subitem] = $result->getPart($current_index);
                    $current_index += 1;
                }
                $parts[$item[0]] = new ConcatenatedExpressionResult($subparts);
            } else {
                $parts[$item] = $result->getPart($current_index);
                $current_index += 1;
            }
        }
        return new ConcatenatedExpressionResult($parts);
    }

}