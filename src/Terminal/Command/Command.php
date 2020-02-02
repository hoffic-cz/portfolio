<?php
declare(strict_types=1);


namespace App\Terminal\Command;


use App\Object\CommandOutput;
use App\Terminal\History;

interface Command
{
    function execute(array $params, ?History $history = null): CommandOutput;
}
