<?php
declare(strict_types=1);


namespace App\Terminal\Command;


use App\Object\CommandOutput;
use App\Terminal\History;

class BugsCommand implements Command
{

    function execute(array $params, ?History $history = null): CommandOutput
    {
        $output = new CommandOutput();

        $output->setTrigger('captcha');

        return $output;
    }
}
