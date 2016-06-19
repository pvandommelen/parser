<?php


namespace PeterVanDommelen\Parser\Parser;


interface InputStreamInterface
{
    /**
     * @param int $amount
     * @return void
     */
    public function move($amount);

    /**
     * @param string $string
     * @return bool
     */
    public function matchesString($string);

    /**
     * @param string $char
     * @return bool
     */
    public function matchesChar($char);

    /**
     * @return bool
     */
    public function isAtEnd();

    /**
     * @param int|null $length
     * @return string
     */
    public function getRemainingString($length = null);
}