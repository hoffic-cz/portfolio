<?php
declare(strict_types=1);


namespace App\Terminal\Command;


use App\Object\CommandOutput;
use App\Terminal\History;

class IntroCommand implements Command
{
    public function execute(array $params, ?History $history = null): CommandOutput
    {
        return new CommandOutput(<<<STDOUT


        I'm a software engineer based in London/Prague specializing in building

        reliable back ends, helping out with smaller scale DevOps and training

        a neural network for best engineering practices.


        You might want to try 'ls' or 'help <command>' to get started.


STDOUT
        );
    }
}
