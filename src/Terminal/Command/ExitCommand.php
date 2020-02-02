<?php
declare(strict_types=1);


namespace App\Terminal\Command;


use App\Object\CommandOutput;
use App\Terminal\History;

class ExitCommand implements Command
{
    public const EXIT_TIME_KEY = 'exit_time';
    public const HAS_REOPENED = 'exit_reopened';

    function execute(array $params, ?History $history = null): CommandOutput
    {
        $history->setNote(self::EXIT_TIME_KEY, microtime(true));

        return new CommandOutput();
    }
}
