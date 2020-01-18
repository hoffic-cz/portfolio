<?php
declare(strict_types=1);


namespace App\Terminal\Command;


use App\Object\CommandOutput;

interface Command
{
    function execute(array $params): CommandOutput;
}
