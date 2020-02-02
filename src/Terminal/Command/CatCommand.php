<?php
declare(strict_types=1);


namespace App\Terminal\Command;


use App\Object\CommandOutput;
use App\Terminal\History;

class CatCommand implements Command
{

    function execute(array $params, ?History $history = null): CommandOutput
    {
        return new CommandOutput('dog!');
    }
}
