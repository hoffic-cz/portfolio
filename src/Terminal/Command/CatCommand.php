<?php
declare(strict_types=1);


namespace App\Terminal\Command;


use App\Object\CommandOutput;

class CatCommand implements Command
{

    function execute(array $params): CommandOutput
    {
        return new CommandOutput('dog!');
    }
}
