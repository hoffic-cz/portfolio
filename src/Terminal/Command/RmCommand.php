<?php
declare(strict_types=1);


namespace App\Terminal\Command;


use App\Object\CommandOutput;

class RmCommand implements Command
{

    function execute(array $params): CommandOutput
    {
        $output = new CommandOutput();

        $output->setTrigger('rm');

        return $output;
    }
}
