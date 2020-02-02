<?php
declare(strict_types=1);


namespace App\Terminal\Command;


use App\Object\CommandOutput;
use App\Terminal\History;

class TailCommand implements Command
{

    function execute(array $params, ?History $history = null): CommandOutput
    {
        return new CommandOutput(<<<'STDOUT'
Are you looking for a tail? A 'dog' has a tail!
STDOUT
        );
    }
}
