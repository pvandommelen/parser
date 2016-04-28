<?php


namespace PeterVanDommelen\Parser\Handler;


interface RecursionAwareInterface
{
    public function setRecursiveHandler($handler);
}