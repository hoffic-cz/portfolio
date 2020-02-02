<?php
declare(strict_types=1);


namespace App\Terminal\Command;


use App\Object\CommandOutput;
use App\Terminal\History;

class RmCommand implements Command
{

    function execute(array $params, ?History $history = null): CommandOutput
    {
        $output = new CommandOutput();

        $containsFlags = isset($params[1]) && ($params[1] === '-Rf' || $params[1] === '-fR');
        $correctDir = isset($params[2]) && ($params[2] === '/' || $params[2] === '/*');

        if ($containsFlags && $correctDir) {
            $output->setTrigger('rm');
        } else {
            $output->setStdout(<<<STDOUT
Permission denied.
Did you mean 'sudo rm -Rf /'?
STDOUT
);
        }

        return $output;
    }
}
