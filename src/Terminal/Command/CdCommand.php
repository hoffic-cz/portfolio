<?php
declare(strict_types=1);


namespace App\Terminal\Command;


use App\Object\CommandOutput;

class CdCommand implements Command
{

    function execute(array $params): CommandOutput
    {
        $target = '/home/visitor';

        if (isset($params[1]) >= 2) {
            if (substr($params[1], 0, 7) === 'secrets') {
                return new CommandOutput(<<<STDOUT
I can't let you in, but I can tell you the 'ls' command has an interesting flag. Look it up in the manual ('man <command>')
STDOUT
                );
            }

            $target = $params[1];
        }

        return new CommandOutput(sprintf(
            'bash: cd: %s: Permission denied',
            $target
        ));
    }
}
