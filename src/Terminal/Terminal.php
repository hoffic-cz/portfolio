<?php


namespace App\Terminal;


use App\Object\CommandOutput;

class Terminal
{
    public function command(string $command): CommandOutput
    {
        throw new \RuntimeException();
    }
}
