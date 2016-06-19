<?php


namespace PeterVanDommelen\Parser\Compiler;


use PeterVanDommelen\Parser\Expression\ExpressionResultInterface;
use PeterVanDommelen\Parser\Parser\InputStreamInterface;
use PeterVanDommelen\Parser\Parser\ParserInterface;
use PeterVanDommelen\Parser\Rewriter\ExpressionResultRewriterInterface;

class RewriterParser implements ParserInterface
{
    /** @var ParserInterface */
    private $parser;

    /** @var ExpressionResultRewriterInterface */
    private $result_rewriter;

    /**
     * @param ParserInterface $parser
     * @param ExpressionResultRewriterInterface $result_rewriter
     */
    public function __construct(ParserInterface $parser, ExpressionResultRewriterInterface $result_rewriter)
    {
        $this->parser = $parser;
        $this->result_rewriter = $result_rewriter;
    }

    public function parse($string)
    {
        $result = $this->parser->parse($string);

        if ($result === null) {
            return null;
        }

        $result = $this->result_rewriter->reverseRewriteExpressionResult($result);
        return $result;
    }

}