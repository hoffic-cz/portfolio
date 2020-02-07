<?php
declare(strict_types=1);


namespace App\Terminal\Command;


use App\Object\CommandOutput;
use App\Terminal\History;

interface Trigger
{
    function trigger(array $params, ?History $history = null): CommandOutput;
}
