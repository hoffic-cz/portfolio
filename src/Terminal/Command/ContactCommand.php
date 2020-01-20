<?php
declare(strict_types=1);


namespace App\Terminal\Command;


use App\Object\CommandOutput;

class ContactCommand implements Command
{

    function execute(array $params): CommandOutput
    {
        return new CommandOutput(<<<'STDOUT'

    The safe way to reach me is mailto:petr@hoffic.dev

    If you just wanna say hi, why not use 'say "<message>"'?

    And if you'd like to wait weeks for a reply, use www.linkedin.com/in/hoffic-cz/

STDOUT
        );
    }
}
