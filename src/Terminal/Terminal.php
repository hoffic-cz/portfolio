<?php
declare(strict_types=1);


namespace App\Terminal;


use App\Object\CommandOutput;

class Terminal
{
    public function command(string $command): CommandOutput
    {
        return new CommandOutput(<<<STDOUT
I'm a software engineer based in London/Prague specializing in building
reliable back ends, helping out with smaller scale DevOps and soaking in
knowledge like a sponge.
STDOUT
        );
    }
}
