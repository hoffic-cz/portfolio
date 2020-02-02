<?php
declare(strict_types=1);


namespace App\Terminal\Command;


use App\Object\CommandOutput;
use App\Terminal\History;

class ExitCommand implements Command
{

    function execute(array $params, ?History $history = null): CommandOutput
    {
        $history->setNote('exit_time', microtime(true));

        return new CommandOutput();
    }
}
